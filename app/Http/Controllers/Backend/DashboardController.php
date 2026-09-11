<?php

namespace App\Http\Controllers\Backend;

use App\Enums\KYCStatus;
use App\Enums\TaskSubmissionStatus;
use App\Enums\TxnStatus;
use App\Enums\TxnType;
use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Gateway;
use App\Models\LoginActivities;
use App\Models\ReferralRelationship;
use App\Models\Task;
use App\Models\TaskSubmission;
use App\Models\Ticket;
use App\Models\Transaction;
use App\Models\User;
use Carbon\Carbon;

class DashboardController extends Controller
{
    //admin dashboard
    public function dashboard()
    {
        $transaction = new Transaction();
        $user = new User();
        $admin = new Admin();

        $totalDeposit = $transaction->totalDeposit();

        $totalSend = Transaction::where('status', TxnStatus::Success)->where(function ($query) {
            $query->where('type', TxnType::SendMoney);
        })->sum('amount');

        $activeUser = $user->where('status', 1)->count();

        $totalStaff = $admin->count();

        $latestUser = $user->latest()->take(5)->get();

        $pendingSubmissions = TaskSubmission::with(['task', 'user'])
            ->where('status', TaskSubmissionStatus::Pending)
            ->latest()
            ->take(5)
            ->get();

        $totalGateway = Gateway::where('status', true)->count();

        $withdrawCount = Transaction::where(function ($query) {
            $query->where('type', TxnType::Withdraw)
                ->where('status', 'pending');
        })->count();

        $kycCount = $user->where('kyc', KYCStatus::Pending)->count();

        $depositCount = Transaction::where(function ($query) {
            $query->where('type', TxnType::ManualDeposit)
                ->where('status', 'pending');
        })->count();

        $totalReferral = ReferralRelationship::count();

        $totalTask = Task::count();
        $openTask = Task::open()->count();

        // ============================= Start dashboard statistics =============================================

        $taskStatistics = TaskSubmission::with('task')->get()
            ->groupBy(fn ($submission) => $submission->task->title)
            ->map(fn ($group) => $group->count())
            ->toArray();

        $startDate = request()->start_date ? Carbon::createFromDate(request()->start_date) : Carbon::now()->subDays(7);
        $endDate = request()->end_date ? Carbon::createFromDate(request()->end_date) : Carbon::now();
        $dateArray = array_fill_keys(generate_date_range_array($startDate, $endDate), 0);

        $dateFilter = [request()->start_date ? $startDate : $startDate->subDays(1), $endDate->addDays(1)];

        $depositStatistics = $totalDeposit->whereBetween('created_at', $dateFilter)->get()->groupBy('day')->map(function ($group) {
            return $group->sum('amount');
        })->toArray();

        $depositStatistics = array_replace($dateArray, $depositStatistics);

        $taskEarningStatistics = $transaction->totalTaskReward()->whereBetween('created_at', $dateFilter)->get()->groupBy('day')->map(function ($group) {
            return $group->sum('amount');
        })->toArray();

        $taskEarningStatistics = array_replace($dateArray, $taskEarningStatistics);

        $withdrawStatistics = $transaction->totalWithdraw()->whereBetween('created_at', $dateFilter)->get()->groupBy('day')->map(function ($group) {
            return $group->sum('amount');
        })->toArray();
        $withdrawStatistics = array_replace($dateArray, $withdrawStatistics);

        $profitStatistics = $transaction->totalProfit()->whereBetween('created_at', $dateFilter)->get()->groupBy('day')->map(function ($group) {
            return $group->sum('amount');
        })->toArray();
        $profitStatistics = array_replace($dateArray, $profitStatistics);

        // ============================= End dashboard statistics =============================================

        $browser = LoginActivities::all()->groupBy('browser')->map(function ($browser) {
            return $browser->count();
        })->toArray();
        $platform = LoginActivities::all()->groupBy('platform')->map(function ($platform) {
            return $platform->count();
        })->toArray();

        $country = User::all()->groupBy('country')->map(function ($country) {
            return $country->count();
        })->toArray();
        arsort($country);
        $country = array_slice($country, 0, 5);

        $symbol = setting('currency_symbol', 'global');

        $data = [
            'withdraw_count' => $withdrawCount,
            'kyc_count' => $kycCount,
            'deposit_count' => $depositCount,
            'submission_count' => TaskSubmission::where('status', TaskSubmissionStatus::Pending)->count(),

            'register_user' => $user->count(),
            'active_user' => $activeUser,
            'latest_user' => $latestUser,
            'pending_submissions' => $pendingSubmissions,

            'total_staff' => $totalStaff,

            'total_deposit' => $transaction->totalDeposit()->sum('amount'),
            'total_send' => $totalSend,
            'total_task' => $totalTask,
            'open_task' => $openTask,
            'total_task_earning' => $transaction->totalTaskReward()->sum('amount'),
            'total_withdraw' => $transaction->totalWithdraw()->sum('amount'),
            'total_referral' => $totalReferral,

            'date_label' => $dateArray,
            'deposit_statistics' => $depositStatistics,
            'task_earning_statistics' => $taskEarningStatistics,
            'withdraw_statistics' => $withdrawStatistics,
            'profit_statistics' => $profitStatistics,

            'start_date' => isset(request()->start_date) ? $startDate : $startDate->addDays(1)->format('m/d/Y'),
            'end_date' => isset(request()->end_date) ? $endDate : $endDate->subDays(1)->format('m/d/Y'),

            'task_statistics' => $taskStatistics,
            'deposit_bonus' => $transaction->totalDepositBonus(),
            'task_bonus' => $transaction->totalTaskBonus(),
            'total_gateway' => $totalGateway,
            'total_ticket' => Ticket::count(),

            'browser' => $browser,
            'platform' => $platform,
            'country' => $country,
            'symbol' => $symbol,
        ];

        if (request()->ajax()) {
            $date = [
                'date_label' => $dateArray,
                'deposit_statistics' => $depositStatistics,
                'task_earning_statistics' => $taskEarningStatistics,
                'withdraw_statistics' => $withdrawStatistics,
                'profit_statistics' => $profitStatistics,
                'symbol' => $symbol,
            ];

            return response()->json($date);
        }

        return view('backend.dashboard', compact('data'));
    }
}
