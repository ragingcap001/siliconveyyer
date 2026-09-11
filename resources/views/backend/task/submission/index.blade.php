@extends('backend.layouts.app')
@section('title')
    {{ __('Task Submissions') }}
@endsection
@section('content')
    <div class="main-content">
        <div class="page-title">
            <div class="container-fluid">
                <div class="row">
                    <div class="col">
                        <div class="title-content">
                            <h2 class="title">{{ __('Task Submissions') }}</h2>
                            <a href="{{ route('admin.task.submission.pending') }}"
                               class="title-btn">{{ __('Pending Review') }}</a>
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
                                <div class="col-md-3">
                                    <input type="text" name="query" class="form-control"
                                           placeholder="{{ __('Search username or email') }}"
                                           value="{{ request('query') }}">
                                </div>
                                <div class="col-md-3">
                                    <select name="status" class="form-control">
                                        <option value="">{{ __('All Statuses') }}</option>
                                        @foreach(\App\Enums\TaskSubmissionStatus::cases() as $status)
                                            <option value="{{ $status->value }}"
                                                @selected(request('status') === $status->value)>{{ $status->label() }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <select name="task_id" class="form-control">
                                        <option value="">{{ __('All Tasks') }}</option>
                                        @foreach($tasks as $task)
                                            <option value="{{ $task->id }}"
                                                @selected(request('task_id') == $task->id)>{{ $task->title }}</option>
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
                                        <th>{{ __('Worker') }}</th>
                                        <th>{{ __('Task') }}</th>
                                        <th>{{ __('Pay') }}</th>
                                        <th>{{ __('Payout To') }}</th>
                                        <th>{{ __('Proof') }}</th>
                                        <th>{{ __('Submitted') }}</th>
                                        <th>{{ __('Status') }}</th>
                                        <th>{{ __('Action') }}</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @forelse($submissions as $submission)
                                        <tr>
                                            <td>
                                                <strong>{{ safe($submission->user->username) }}</strong>
                                                <div class="small text-muted">{{ safe($submission->user->email) }}</div>
                                            </td>
                                            <td>{{ $submission->task->title }}</td>
                                            <td>
                                                <strong>{{ $currencySymbol }}{{ $submission->pay_amount }}</strong>
                                                @if($submission->attempt > 1)
                                                    <div class="small text-muted">
                                                        {{ __('Attempt') }} {{ $submission->attempt }}
                                                    </div>
                                                @endif
                                            </td>
                                            <td>{{ $submission->payoutMethod->name ?: '—' }}</td>
                                            <td>
                                                @if($submission->proof_file)
                                                    <a href="{{ asset('assets/'.$submission->proof_file) }}"
                                                       target="_blank" class="site-badge info">{{ __('File') }}</a>
                                                @elseif($submission->proof_link)
                                                    <a href="{{ $submission->proof_link }}" target="_blank"
                                                       class="site-badge info">{{ __('Link') }}</a>
                                                @elseif($submission->proof_text)
                                                    <span class="small">{{ Str::limit($submission->proof_text, 40) }}</span>
                                                @else
                                                    <span class="text-muted">{{ __('No proof yet') }}</span>
                                                @endif
                                            </td>
                                            <td>{{ $submission->created_at->format('d M Y h:i') }}</td>
                                            <td>
                                                <div class="site-badge {{ $submission->status->color() }}">
                                                    {{ $submission->status->label() }}
                                                </div>
                                            </td>
                                            <td>
                                                <a href="{{ route('admin.task.submission.show', $submission->id) }}"
                                                   class="round-icon-btn primary-btn" title="{{ __('Review') }}">
                                                    <i icon-name="eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="8" class="text-center">{{ __('No submissions found') }}</td>
                                        </tr>
                                    @endforelse
                                    </tbody>
                                </table>
                            </div>

                            <div class="mt-3">
                                {{ $submissions->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
