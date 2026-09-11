<?php

namespace App\Http\Controllers\Backend;

use App\Enums\TaskStatus;
use App\Enums\TaskSubmissionStatus;
use App\Enums\TxnStatus;
use App\Enums\TxnType;
use App\Http\Controllers\Controller;
use App\Models\LevelReferral;
use App\Models\Task;
use App\Models\TaskSubmission;
use App\Traits\NotifyTrait;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;
use Txn;

class TaskSubmissionController extends Controller
{
    use NotifyTrait;

    public function __construct()
    {
        $this->middleware('permission:task-submission-list', ['only' => ['index', 'pending', 'show']]);
        $this->middleware('permission:task-submission-action', ['only' => ['approve', 'reject']]);
    }

    /**
     * Every submission, optionally filtered by status or task.
     */
    public function index(Request $request): View
    {
        $query = TaskSubmission::with(['task', 'user', 'payoutMethod'])->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('task_id')) {
            $query->where('task_id', $request->task_id);
        }

        if ($request->filled('query')) {
            $query->whereHas('user', function ($q) use ($request) {
                $q->where('username', 'like', '%' . $request->query . '%')
                    ->orWhere('email', 'like', '%' . $request->query . '%');
            });
        }

        $submissions = $query->paginate(20)->withQueryString();
        $tasks = Task::orderBy('title')->get();

        return view('backend.task.submission.index', compact('submissions', 'tasks'));
    }

    /**
     * The review queue.
     */
    public function pending(Request $request): View
    {
        $request->merge(['status' => TaskSubmissionStatus::Pending->value]);

        return $this->index($request);
    }

    /**
     * Submission detail with the worker's proof.
     */
    public function show($id): View
    {
        $submission = TaskSubmission::with(['task', 'user', 'payoutMethod', 'withdrawAccount'])->findOrFail($id);

        return view('backend.task.submission.show', compact('submission'));
    }

    /**
     * Approve a submission, pay the worker and release the slot.
     */
    public function approve(Request $request, $id): RedirectResponse
    {
        $submission = TaskSubmission::findOrFail($id);

        if (! $submission->isPending()) {
            notify()->warning(__('This submission has already been reviewed.'), 'warning');

            return redirect()->back();
        }

        $validator = Validator::make($request->all(), [
            'admin_note' => 'nullable|max:500',
        ]);

        if ($validator->fails()) {
            notify()->error($validator->errors()->first(), 'Error');

            return redirect()->back();
        }

        $task = $submission->task;
        $user = $submission->user;
        $amount = (float) $submission->pay_amount;

        if ($amount <= 0) {
            notify()->error(__('This submission has no payable amount.'), 'Error');

            return redirect()->back();
        }

        DB::transaction(function () use ($submission, $task, $user, $amount, $request) {
            // credit the worker's main wallet
            $user->increment('balance', $amount);

            $txnInfo = Txn::new(
                $amount,
                0,
                $amount,
                'system',
                'Task Reward: ' . $task->title,
                TxnType::TaskReward,
                TxnStatus::Success,
                null,
                null,
                $user->id
            );

            $submission->update([
                'status' => TaskSubmissionStatus::Approved,
                'admin_note' => $request->admin_note,
                'reviewed_by' => auth()->id(),
                'reviewed_at' => now(),
                'paid_at' => now(),
            ]);

            // keep the denormalised slot counter in step
            if ($task && $task->total_slots > 0) {
                $task->increment('filled_slots');
            }

            // level based referral commission on task earnings
            if (setting('site_referral', 'global') == 'level' && setting('task_level')) {
                $level = LevelReferral::where('type', 'task')->max('the_order') + 1;
                creditReferralBonus($txnInfo->user, 'task', $amount, $level);
            }
        });

        $this->notifyWorker($submission, 'task_approved');

        notify()->success(__('Submission approved and payment released'));

        return redirect()->back();
    }

    /**
     * Reject a submission. A rejection does not consume an attempt, so the worker
     * may correct and resubmit while slots remain.
     */
    public function reject(Request $request, $id): RedirectResponse
    {
        $submission = TaskSubmission::findOrFail($id);

        if (! $submission->isPending()) {
            notify()->warning(__('This submission has already been reviewed.'), 'warning');

            return redirect()->back();
        }

        $validator = Validator::make($request->all(), [
            'admin_note' => 'nullable|max:500',
        ]);

        if ($validator->fails()) {
            notify()->error($validator->errors()->first(), 'Error');

            return redirect()->back();
        }

        $submission->update([
            'status' => TaskSubmissionStatus::Rejected,
            'admin_note' => $request->admin_note,
            'reviewed_by' => auth()->id(),
            'reviewed_at' => now(),
        ]);

        $this->notifyWorker($submission, 'task_rejected');

        notify()->success(__('Submission rejected'));

        return redirect()->back();
    }

    /**
     * Mail / push / sms the outcome to the worker.
     */
    private function notifyWorker(TaskSubmission $submission, string $code): void
    {
        $submission->loadMissing(['task', 'user']);

        $shortcodes = [
            '[[full_name]]' => $submission->user->full_name,
            '[[task_name]]' => $submission->task->title,
            '[[pay_amount]]' => $submission->pay_amount . ' ' . setting('site_currency', 'global'),
            '[[status]]' => $submission->status->label(),
            '[[message]]' => $submission->admin_note ?? '',
            '[[site_title]]' => setting('site_title', 'global'),
            '[[site_url]]' => route('home'),
        ];

        $this->mailNotify($submission->user->email, $code, $shortcodes);
        $this->pushNotify($code, $shortcodes, route('user.task.history'), $submission->user->id);
        $this->smsNotify($code, $shortcodes, $submission->user->phone);
    }
}
