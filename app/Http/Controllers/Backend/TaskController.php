<?php

namespace App\Http\Controllers\Backend;

use App\Enums\TaskProofType;
use App\Enums\TaskStatus;
use App\Http\Controllers\Controller;
use App\Models\Ranking;
use App\Models\Task;
use App\Models\WithdrawMethod;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Enum;
use Illuminate\View\View;

class TaskController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:task-list', ['only' => ['index']]);
        $this->middleware('permission:task-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:task-edit', ['only' => ['edit', 'update', 'statusUpdate']]);
        $this->middleware('permission:task-delete', ['only' => ['destroy']]);
    }

    /**
     * All tasks, newest first.
     */
    public function index(Request $request): View
    {
        $query = Task::withCount('submissions')->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('query')) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', '%' . $request->query . '%')
                    ->orWhere('category', 'like', '%' . $request->query . '%');
            });
        }

        $tasks = $query->paginate(15)->withQueryString();

        return view('backend.task.index', compact('tasks'));
    }

    /**
     * Create form (rendered into a modal).
     */
    public function create(): View
    {
        return view('backend.task.include.__add_new', [
            'proofTypes' => TaskProofType::cases(),
            'statuses' => TaskStatus::cases(),
            'levels' => Ranking::orderBy('level')->get(),
            'payoutMethods' => WithdrawMethod::where('status', true)->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|max:191',
            'category' => 'nullable|max:100',
            'description' => 'required',
            'instructions' => 'nullable',
            'pay_amount' => 'required|regex:/^\d+(\.\d{1,8})?$/',
            'proof_type' => ['required', new Enum(TaskProofType::class)],
            'total_slots' => 'nullable|integer|min:0',
            'per_user_limit' => 'required|integer|min:1',
            'min_level' => 'nullable|integer|min:1',
            'min_balance' => 'nullable|regex:/^\d+(\.\d{1,8})?$/',
            'expires_at' => 'nullable|date',
            'status' => ['required', new Enum(TaskStatus::class)],
            'payout_method_ids' => 'nullable|array',
            'payout_method_ids.*' => 'exists:withdraw_methods,id',
        ]);

        if ($validator->fails()) {
            notify()->error($validator->errors()->first(), 'Error');

            return redirect()->back();
        }

        $data = $this->buildData($request);
        $data['created_by'] = auth()->id();

        Task::create($data);

        notify()->success(__('Task created successfully'));

        return redirect()->route('admin.task.index');
    }

    /**
     * Edit form (rendered into a modal).
     */
    public function edit($id): View
    {
        $task = Task::findOrFail($id);

        return view('backend.task.include.__edit', [
            'task' => $task,
            'proofTypes' => TaskProofType::cases(),
            'statuses' => TaskStatus::cases(),
            'levels' => Ranking::orderBy('level')->get(),
            'payoutMethods' => WithdrawMethod::where('status', true)->get(),
        ]);
    }

    public function update(Request $request, $id): RedirectResponse
    {
        $task = Task::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'title' => 'required|max:191',
            'category' => 'nullable|max:100',
            'description' => 'required',
            'instructions' => 'nullable',
            'pay_amount' => 'required|regex:/^\d+(\.\d{1,8})?$/',
            'proof_type' => ['required', new Enum(TaskProofType::class)],
            'total_slots' => 'nullable|integer|min:0',
            'per_user_limit' => 'required|integer|min:1',
            'min_level' => 'nullable|integer|min:1',
            'min_balance' => 'nullable|regex:/^\d+(\.\d{1,8})?$/',
            'expires_at' => 'nullable|date',
            'status' => ['required', new Enum(TaskStatus::class)],
            'payout_method_ids' => 'nullable|array',
            'payout_method_ids.*' => 'exists:withdraw_methods,id',
        ]);

        if ($validator->fails()) {
            notify()->error($validator->errors()->first(), 'Error');

            return redirect()->back();
        }

        $task->update($this->buildData($request));

        notify()->success(__('Task updated successfully'));

        return redirect()->route('admin.task.index');
    }

    /**
     * Quick status switch from the listing.
     */
    public function statusUpdate(Request $request): JsonResponse
    {
        $task = Task::findOrFail($request->id);
        $task->update(['status' => $request->status]);

        return response()->json(['message' => __('Task status updated successfully')]);
    }

    public function destroy($id): RedirectResponse
    {
        $task = Task::withTrashed()->findOrFail($id);
        $task->submissions()->delete();
        $task->forceDelete();

        notify()->success(__('Task deleted successfully'));

        return redirect()->route('admin.task.index');
    }

    /**
     * Normalise the request into the task payload.
     */
    private function buildData(Request $request): array
    {
        // an empty selection means "every payout method the admin has enabled"
        $payoutIds = array_filter((array) $request->payout_method_ids);

        return [
            'title' => $request->title,
            'category' => $request->category,
            'description' => $request->description,
            'instructions' => $request->instructions,
            'pay_amount' => (float) $request->pay_amount,
            'proof_type' => $request->proof_type,
            'proof_required' => $request->boolean('proof_required', true),
            'total_slots' => (int) ($request->total_slots ?: 0),
            'per_user_limit' => (int) ($request->per_user_limit ?: 1),
            'min_level' => (int) ($request->min_level ?: 1),
            'require_kyc' => $request->boolean('require_kyc'),
            'min_balance' => (float) ($request->min_balance ?: 0),
            'payout_method_ids' => empty($payoutIds) ? null : array_values($payoutIds),
            'status' => $request->status,
            'expires_at' => $request->filled('expires_at') ? $request->expires_at : null,
        ];
    }
}
