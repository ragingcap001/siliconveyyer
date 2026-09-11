<?php

namespace App\Http\Controllers\Frontend;

use App\Enums\TaskProofType;
use App\Enums\TaskStatus;
use App\Enums\TaskSubmissionStatus;
use App\Http\Controllers\Controller;
use App\Models\Task;
use App\Models\TaskSubmission;
use App\Models\WithdrawAccount;
use App\Traits\ImageUpload;
use App\Traits\NotifyTrait;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;

class TaskController extends Controller
{
    use ImageUpload, NotifyTrait;

    /**
     * Browse every task the logged in user is eligible to take.
     */
    public function index(Request $request): View
    {
        $user = auth()->user();

        $tasks = Task::open()
            ->withCount('submissions')
            ->when($request->filled('category'), fn ($q) => $q->where('category', $request->category))
            ->when($request->filled('query'), function ($q) use ($request) {
                $q->where('title', 'like', '%' . $request->query . '%');
            })
            ->latest()
            ->paginate(12)
            ->withQueryString();

        // annotate with eligibility without mutating the model
        $tasks->getCollection()->transform(function (Task $task) use ($user) {
            $task->eligibility_error = $task->eligibilityErrorFor($user);
            $task->my_submission = $task->submissions()
                ->where('user_id', $user->id)
                ->latest()
                ->first();

            return $task;
        });

        $categories = Task::open()
            ->whereNotNull('category')
            ->distinct()
            ->pluck('category')
            ->filter()
            ->values();

        return view('frontend::task.index', compact('tasks', 'categories'));
    }

    /**
     * Full task detail, including the claim/submit form.
     */
    public function show($id): View
    {
        $user = auth()->user();
        $task = Task::withCount('submissions')->findOrFail($id);

        $submission = TaskSubmission::where('task_id', $task->id)
            ->where('user_id', $user->id)
            ->latest()
            ->first();

        $eligibilityError = $task->eligibilityErrorFor($user);

        $payoutMethods = $task->payoutMethods();

        // the worker's saved accounts, keyed by payout method
        $accounts = WithdrawAccount::where('user_id', $user->id)
            ->whereIn('withdraw_method_id', $payoutMethods->pluck('id'))
            ->get()
            ->groupBy('withdraw_method_id');

        return view('frontend::task.show', compact(
            'task',
            'submission',
            'eligibilityError',
            'payoutMethods',
            'accounts'
        ));
    }

    /**
     * Claim a task: reserve a slot and choose how to be paid.
     */
    public function take(Request $request, $id): RedirectResponse
    {
        $task = Task::findOrFail($id);
        $user = auth()->user();

        // platform-wide cap on how many tasks one worker may claim per day
        $dailyLimit = (int) setting('task_submission_daily_limit', null, 0);

        if ($dailyLimit > 0) {
            $claimedToday = TaskSubmission::where('user_id', $user->id)
                ->whereDate('created_at', today())
                ->count();

            if ($claimedToday >= $dailyLimit) {
                notify()->error(
                    __('You have reached the daily limit of :limit tasks. Please come back tomorrow.', ['limit' => $dailyLimit]),
                    'Error'
                );

                return redirect()->back();
            }
        }

        $validator = Validator::make($request->all(), [
            'payout_method_id' => 'required|exists:withdraw_methods,id',
            'withdraw_account_id' => 'required|exists:withdraw_accounts,id',
        ]);

        if ($validator->fails()) {
            notify()->error($validator->errors()->first(), 'Error');

            return redirect()->back();
        }

        // the account must belong to the user and match the chosen method
        $account = WithdrawAccount::where('id', $request->withdraw_account_id)
            ->where('user_id', $user->id)
            ->where('withdraw_method_id', $request->payout_method_id)
            ->first();

        if (! $account) {
            notify()->error(__('Please choose a payout account you have already saved.'), 'Error');

            return redirect()->back();
        }

        if (! $task->payoutMethods()->contains('id', (int) $request->payout_method_id)) {
            notify()->error(__('That payout option is not available for this task.'), 'Error');

            return redirect()->back();
        }

        try {
            DB::transaction(function () use ($task, $user, $request) {
                // re-read under lock so two workers cannot take the last slot
                $locked = Task::where('id', $task->id)->lockForUpdate()->first();

                if ($locked->eligibilityErrorFor($user) !== null) {
                    throw new \RuntimeException($locked->eligibilityErrorFor($user));
                }

                $attempt = $locked->submissions()
                        ->where('user_id', $user->id)
                        ->max('attempt') + 1;

                TaskSubmission::create([
                    'task_id' => $locked->id,
                    'user_id' => $user->id,
                    'attempt' => $attempt,
                    'payout_method_id' => $request->payout_method_id,
                    'withdraw_account_id' => $request->withdraw_account_id,
                    'pay_amount' => $locked->pay_amount,
                    'status' => TaskSubmissionStatus::Pending,
                ]);

                if ($locked->total_slots > 0) {
                    $locked->increment('filled_slots');
                }
            });
        } catch (\RuntimeException $e) {
            notify()->error($e->getMessage(), 'Error');

            return redirect()->route('user.task.index');
        }

        notify()->success(__('Task taken. Submit your proof to get paid.'), 'success');

        return redirect()->route('user.task.show', $task->id);
    }

    /**
     * Attach (or replace) the proof for a claimed task.
     */
    public function submitProof(Request $request, $id): RedirectResponse
    {
        $submission = TaskSubmission::where('id', $id)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        if ($submission->status !== TaskSubmissionStatus::Pending) {
            notify()->warning(__('This submission has already been reviewed.'), 'warning');

            return redirect()->back();
        }

        $task = $submission->task;
        $proofType = $task->proof_type;

        $rules = [];
        if ($proofType === TaskProofType::Text) {
            $rules['proof_text'] = 'required|string|max:5000';
        } elseif ($proofType === TaskProofType::Link) {
            $rules['proof_link'] = 'required|url|max:500';
        } elseif ($proofType === TaskProofType::Screenshot) {
            $rules['proof_file'] = 'required|file|mimes:jpeg,png,jpg,gif,webp|max:5120';
        } else {
            $rules['proof_file'] = 'required|file|max:5120';
        }

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            notify()->error($validator->errors()->first(), 'Error');

            return redirect()->back();
        }

        $data = [];

        if ($proofType === TaskProofType::Text) {
            $data['proof_text'] = $request->proof_text;
        } elseif ($proofType === TaskProofType::Link) {
            $data['proof_link'] = $request->proof_link;
        } else {
            $data['proof_file'] = self::fileUploadTrait(
                $request->file('proof_file'),
                $proofType === TaskProofType::Screenshot
                    ? ['jpeg', 'png', 'jpg', 'gif', 'webp']
                    : null,
                $submission->proof_file
            );
        }

        $submission->update($data);

        $this->pushNotify('task_submitted', [
            '[[full_name]]' => auth()->user()->full_name,
            '[[task_name]]' => $task->title,
            '[[site_title]]' => setting('site_title', 'global'),
            '[[site_url]]' => route('home'),
        ], route('admin.task.submission.pending'), auth()->id());

        notify()->success(__('Proof submitted. An admin will review it shortly.'), 'success');

        return redirect()->route('user.task.history');
    }

    /**
     * The worker's own task history.
     */
    public function history(Request $request): View
    {
        $submissions = TaskSubmission::with(['task', 'payoutMethod'])
            ->where('user_id', auth()->id())
            ->when($request->filled('status'), fn ($q) => $q->where('status', $request->status))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('frontend::task.history', compact('submissions'));
    }
}
