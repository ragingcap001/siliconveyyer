<?php

namespace App\Http\Controllers;

use App\Enums\TaskStatus;
use App\Enums\TxnStatus;
use App\Enums\TxnType;
use App\Models\Ranking;
use App\Models\Referral;
use App\Models\ReferralLink;
use App\Models\ReferralRelationship;
use App\Models\Task;
use App\Models\Transaction;
use App\Models\User;
use Artisan;
use Txn;

class CronJobController extends Controller
{
    /**
     * Pay out target/milestone based referral rewards.
     *
     * Only runs when the site is in milestone mode; level mode is paid inline at
     * the moment the qualifying event happens (see creditReferralBonus()).
     *
     * @return string
     */
    public function referralCronJob()
    {
        if (setting('site_referral', 'global') == 'level') {
            return '....';
        }

        $referrals = Referral::all();
        $referralRelationship = ReferralRelationship::all();

        foreach ($referralRelationship as $relationship) {
            $provider = ReferralLink::find($relationship->referral_link_id)->user;
            $user = User::find($relationship->user_id);

            if (! $provider || ! $user) {
                continue;
            }

            $totalDeposit = $user->totalDeposit();
            $completedTasks = $user->taskSubmissions()->count();

            // drop milestones this pair has already been paid for
            $unpaid = $referrals->reject(function ($referral) use ($user, $provider) {
                return Transaction::where('target_id', '!=', null)
                    ->where('user_id', $provider->id)
                    ->where('from_user_id', $user->id)
                    ->where('target_id', $referral->referral_target_id)
                    ->where('target_type', $referral->type)
                    ->where('is_level', 0)
                    ->exists();
            });

            foreach ($unpaid as $referral) {
                $referralBonus = ($referral->bounty * $referral->target_amount) / 100;
                $targetName = $referral->target->name;

                if ($referral->type == 'deposit'
                    && $referral->target_amount <= $totalDeposit
                    && setting('deposit_referral_bounty', 'permission')) {
                    $this->payReferral($provider, $user, $referralBonus, $targetName, $referral);
                }

                // "task" milestones are measured in completed tasks
                if ($referral->type == 'task'
                    && $referral->target_amount <= $completedTasks
                    && setting('task_referral_bounty', 'permission')) {
                    $this->payReferral($provider, $user, $referralBonus, $targetName, $referral);
                }
            }
        }

        return '....referral job successfully completed';
    }

    /**
     * Re-evaluate every active user against the ranking ladder and grant any rank
     * they have newly qualified for, paying its one-off bonus.
     *
     * @return string
     */
    public function userRanking()
    {
        $rankings = Ranking::where('status', '=', true)->get();

        foreach (User::where('status', true)->get() as $user) {
            $eligibleRanks = $rankings->reject(function ($rank) use ($user) {
                return $rank->minimum_earnings > $user->totalProfit()
                    || $rank->minimum_deposit > $user->totalDeposit()
                    || $rank->minimum_tasks > $user->taskSubmissions()->count()
                    || $rank->minimum_task_earning > $user->totalTaskEarning()
                    || $rank->minimum_referral > $user->referrals->count()
                    || $rank->minimum_referral_deposit > $user->referrals->sum('total_deposit')
                    || in_array($rank->id, json_decode($user->rankings) ?: []);
            });

            if ($eligibleRanks->isEmpty()) {
                continue;
            }

            $maxEarnings = $eligibleRanks->max('minimum_earnings');
            $highestRank = $eligibleRanks->where('minimum_earnings', $maxEarnings)->first();

            foreach ($eligibleRanks as $rank) {
                Txn::new($rank->bonus, 0, $rank->bonus, 'system', 'Referral Bonus by ' . $rank->ranking, TxnType::Bonus, TxnStatus::Success, null, null, $user->id);
                $user->profit_balance += $rank->bonus;

                if ($rank->id === $highestRank->id) {
                    $user->ranking_id = $rank->id;
                    $user->rankings = json_encode(array_merge(json_decode($user->rankings) ?: [], [$rank->id]));
                }
            }

            $user->save();
        }

        return '....user ranking job successfully completed';
    }

    /**
     * Close tasks whose deadline has passed.
     *
     * @return string
     */
    public function taskCronJob()
    {
        Task::where('status', TaskStatus::Active)
            ->whereNotNull('expires_at')
            ->where('expires_at', '<=', now())
            ->update(['status' => TaskStatus::Closed]);

        return '....task job successfully completed';
    }

    public function queueWork()
    {
        Artisan::call('queue:work');

        return '.... Running successfully';
    }

    /**
     * @return \App\Models\Transaction
     */
    private function payReferral($provider, $user, $referralBonus, $targetName, $referral)
    {
        $txn = Txn::new(
            $referralBonus,
            0,
            $referralBonus,
            'system',
            'Referral Bonus with ' . $targetName . ' Via ' . $user->full_name,
            TxnType::Referral,
            TxnStatus::Success,
            null,
            null,
            $provider->id,
            $user->id,
            'User',
            [],
            'none',
            $referral->referral_target_id,
            $referral->type
        );

        $provider->increment('profit_balance', $referralBonus);

        return $txn;
    }
}
