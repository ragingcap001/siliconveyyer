<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Task;
use App\Models\Transaction;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function dashboard(Request $request)
    {
        $user = auth()->user();
        $transactions = Transaction::where('user_id', $user->id);

        $recentTransactions = $transactions->latest()->take(5)->get();

        $referral = $user->getReferrals()->first();

        $dataCount = [
            'total_transaction' => $transactions->count(),
            'total_deposit' => $user->totalDeposit(),
            'total_task_earning' => $user->totalTaskEarning(),
            'completed_task' => $user->taskSubmissions()->count(),
            'pending_task' => $user->pendingSubmissions()->count(),
            'total_profit' => $user->totalProfit(),
            'profit_last_7_days' => $user->totalProfit(7),
            'total_withdraw' => $user->totalWithdraw(),
            'total_transfer' => $user->totalTransfer(),
            'total_referral_profit' => $user->totalReferralProfit(),
            'total_referral' => $referral->relationships()->count(),
            'deposit_bonus' => $user->totalDepositBonus(),
            'task_bonus' => $user->totalTaskEarning(),
            'rank_achieved' => $user->rankAchieved(),
            'total_ticket' => $user->ticket->count(),
        ];

        $availableTasks = Task::open()
            ->with('creator')
            ->latest()
            ->take(5)
            ->get()
            ->filter(fn (Task $task) => $task->eligibilityErrorFor($user) === null)
            ->values();

        return view('frontend::user.dashboard', compact(
            'dataCount',
            'recentTransactions',
            'referral',
            'availableTasks',
        ));
    }
}
