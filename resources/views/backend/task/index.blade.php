@extends('backend.layouts.app')
@section('title')
    {{ __('Manage Tasks') }}
@endsection
@section('content')
    <div class="main-content">
        <div class="page-title">
            <div class="container-fluid">
                <div class="row">
                    <div class="col">
                        <div class="title-content">
                            <h2 class="title">{{ __('Tasks') }}</h2>
                            @can('task-create')
                                <a href="" class="title-btn" type="button" data-bs-toggle="modal"
                                   data-bs-target="#addNewTask">
                                    <i icon-name="plus-circle"></i>{{ __('Add New Task') }}</a>
                            @endcan
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="container-fluid">
            <div class="row">
                <div class="col-xl-12">
                    <div class="site-card">
                        <div class="site-card-body">
                            <form method="GET" class="row mb-3 g-2">
                                <div class="col-md-4">
                                    <input type="text" name="query" class="form-control"
                                           placeholder="{{ __('Search by title or category') }}"
                                           value="{{ request('query') }}">
                                </div>
                                <div class="col-md-3">
                                    <select name="status" class="form-control">
                                        <option value="">{{ __('All Statuses') }}</option>
                                        @foreach(\App\Enums\TaskStatus::cases() as $status)
                                            <option value="{{ $status->value }}"
                                                @selected(request('status') === $status->value)>{{ $status->label() }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <button class="btn primary-btn" type="submit">{{ __('Filter') }}</button>
                                </div>
                            </form>

                            <div class="site-table table-responsive">
                                <table class="table">
                                    <thead>
                                    <tr>
                                        <th>{{ __('Task') }}</th>
                                        <th>{{ __('Pay') }}</th>
                                        <th>{{ __('Slots') }}</th>
                                        <th>{{ __('Recommended For') }}</th>
                                        <th>{{ __('Proof') }}</th>
                                        <th>{{ __('Deadline') }}</th>
                                        <th>{{ __('Status') }}</th>
                                        <th>{{ __('Action') }}</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @forelse($tasks as $task)
                                        <tr>
                                            <td>
                                                <strong>{{ $task->title }}</strong>
                                                @if($task->category)
                                                    <div class="site-badge info">{{ $task->category }}</div>
                                                @endif
                                                <div class="small text-muted">
                                                    {{ $task->submissions_count }} {{ __('submission(s)') }}
                                                </div>
                                            </td>
                                            <td><strong>{{ $currencySymbol }}{{ $task->pay_amount }}</strong></td>
                                            <td>
                                                @if($task->hasUnlimitedSlots())
                                                    {{ __('Unlimited') }}
                                                @else
                                                    {{ $task->filled_slots }} / {{ $task->total_slots }}
                                                @endif
                                                <div class="small text-muted">
                                                    {{ $task->per_user_limit }}x {{ __('per user') }}
                                                </div>
                                            </td>
                                            <td>
                                                <div class="small">
                                                    {{ __('Level') }} {{ $task->min_level }}+
                                                </div>
                                                @if($task->require_kyc)
                                                    <div class="site-badge warning">{{ __('KYC') }}</div>
                                                @endif
                                                @if($task->min_balance > 0)
                                                    <div class="small text-muted">
                                                        {{ __('Min') }} {{ $currencySymbol }}{{ $task->min_balance }}
                                                    </div>
                                                @endif
                                            </td>
                                            <td>{{ $task->proof_type->label() }}</td>
                                            <td>
                                                {{ $task->expires_at ? $task->expires_at->format('d M Y') : '—' }}
                                            </td>
                                            <td>
                                                <div class="site-badge {{ $task->status->color() }}">
                                                    {{ $task->status->label() }}
                                                </div>
                                            </td>
                                            <td>
                                                @can('task-edit')
                                                    <button type="button" class="round-icon-btn primary-btn"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#editTask{{ $task->id }}"
                                                            title="{{ __('Edit') }}">
                                                        <i icon-name="edit-3"></i>
                                                    </button>
                                                @endcan
                                                @can('task-submission-list')
                                                    <a href="{{ route('admin.task.submission.index', ['task_id' => $task->id]) }}"
                                                       class="round-icon-btn info-btn" title="{{ __('Submissions') }}">
                                                        <i icon-name="eye"></i>
                                                    </a>
                                                @endcan
                                                @can('task-delete')
                                                    <form method="POST"
                                                          action="{{ route('admin.task.delete', $task->id) }}"
                                                          class="d-inline"
                                                          onsubmit="return confirm('{{ __('Delete this task and all its submissions?') }}')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button class="round-icon-btn red-btn" type="submit">
                                                            <i icon-name="trash-2"></i>
                                                        </button>
                                                    </form>
                                                @endcan
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="8" class="text-center">{{ __('No tasks found') }}</td>
                                        </tr>
                                    @endforelse
                                    </tbody>
                                </table>
                            </div>

                            <div class="mt-3">
                                {{ $tasks->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @can('task-edit')
        @foreach($tasks as $task)
            <div class="modal fade" id="editTask{{ $task->id }}" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-lg modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">{{ __('Edit Task') }}: {{ $task->title }}</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            @include('backend.task.include.__edit', ['task' => $task])
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    @endcan

    @can('task-create')
        <div class="modal fade" id="addNewTask" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">{{ __('Add New Task') }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        @include('backend.task.include.__add_new')
                    </div>
                </div>
            </div>
        </div>
    @endcan
@endsection
