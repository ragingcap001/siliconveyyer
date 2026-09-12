<?php

namespace App\Http\Controllers\Frontend;

use App\Enums\TaskSubmissionStatus;
use App\Http\Controllers\Controller;
use App\Models\Task;
use App\Models\TaskSubmission;
use App\Models\Transaction;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function dashboard(Request $request)
    {
        $user = auth()->user();
        $transactions = Transaction::where('user_id', $user->id);

        $recentTransactions = $transactions->latest()->take(5)->get();

        // getReferrals() maps over the referral_program rows, so this is null on
        // an install with no program seeded. Every read is null-safe.
        $referral = $user->getReferrals()->first();

        $dataCount = [
            'total_transaction' => $transactions->count(),
            'total_deposit' => $user->totalDeposit(),
            'total_task_earning' => $user->totalTaskEarning(),
            // "Completed" means approved, not merely submitted - taskSubmissions()
            // includes pending and rejected work.
            'completed_task' => $user->taskSubmissions()->where('status', TaskSubmissionStatus::Approved)->count(),
            'pending_task' => $user->pendingSubmissions()->count(),
            'rejected_task' => $user->taskSubmissions()->where('status', TaskSubmissionStatus::Rejected)->count(),
            'total_profit' => $user->totalProfit(),
            'profit_last_7_days' => $user->totalProfit(7),
            'total_withdraw' => $user->totalWithdraw(),
            'total_transfer' => $user->totalTransfer(),
            'total_referral_profit' => $user->totalReferralProfit(),
            'total_referral' => $referral?->relationships()->count() ?? 0,
            'deposit_bonus' => $user->totalDepositBonus(),
            'task_bonus' => $user->totalTaskEarning(),
            'rank_achieved' => $user->rankAchieved(),
            'total_ticket' => $user->ticket->count(),
        ];

        // Work that is live right now and the user is actually allowed to claim.
        $availableTasks = Task::open()
            ->with('creator')
            ->latest()
            ->take(5)
            ->get()
            ->filter(fn (Task $task) => $task->eligibilityErrorFor($user) === null)
            ->values();

        // The user's own proof submissions - what they are waiting on.
        $recentSubmissions = TaskSubmission::with('task')
            ->forUser($user->id)
            ->latest()
            ->take(5)
            ->get();

        return view('frontend::user.dashboard', compact(
            'dataCount',
            'recentTransactions',
            'referral',
            'availableTasks',
            'recentSubmissions',
        ));
    }
}
