@extends('frontend::layouts.user')
@section('title')
    {{ __('My Tasks') }}
@endsection
@section('content')
    <div class="row">
        <div class="col-xl-12">
            <div class="site-card">
                <div class="site-card-header">
                    <h3 class="title">{{ __('My Task History') }}</h3>
                </div>
                <div class="site-card-body">
                    <form action="{{ route('user.task.history') }}" method="get" class="row g-2 mb-3">
                        <div class="col-md-4">
                            <select name="status" class="form-control">
                                <option value="">{{ __('All Statuses') }}</option>
                                @foreach(\App\Enums\TaskSubmissionStatus::cases() as $status)
                                    <option value="{{ $status->value }}"
                                        @selected(request('status') === $status->value)>{{ $status->label() }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="site-btn grad-btn">{{ __('Filter') }}</button>
                        </div>
                    </form>

                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                            <tr>
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
                                        <strong>{{ $submission->task->title }}</strong>
                                        @if($submission->attempt > 1)
                                            <div class="small text-muted">
                                                {{ __('Attempt') }} {{ $submission->attempt }}
                                            </div>
                                        @endif
                                    </td>
                                    <td>
                                        <strong class="green-color">
                                            {{ $currencySymbol }}{{ $submission->pay_amount }}
                                        </strong>
                                    </td>
                                    <td>
                                        {{ $submission->payoutMethod->name ?: '—' }}
                                        @if($submission->withdrawAccount->method_name)
                                            <div class="small text-muted">
                                                {{ $submission->withdrawAccount->method_name }}
                                            </div>
                                        @endif
                                    </td>
                                    <td>
                                        @if($submission->proof_file)
                                            <a href="{{ asset('assets/'.$submission->proof_file) }}"
                                               target="_blank" class="site-badge info">{{ __('File') }}</a>
                                        @elseif($submission->proof_link)
                                            <a href="{{ $submission->proof_link }}" target="_blank"
                                               class="site-badge info">{{ __('Link') }}</a>
                                        @elseif($submission->proof_text)
                                            <span class="small">{{ Str::limit($submission->proof_text, 30) }}</span>
                                        @else
                                            <span class="text-muted">{{ __('Not submitted') }}</span>
                                        @endif
                                    </td>
                                    <td>{{ $submission->created_at->format('d M Y h:i') }}</td>
                                    <td>
                                        <div class="site-badge {{ $submission->status->color() }}">
                                            {{ $submission->status->label() }}
                                        </div>
                                        @if($submission->isRejected() && $submission->admin_note)
                                            <div class="small text-danger mt-1">{{ $submission->admin_note }}</div>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('user.task.show', $submission->task_id) }}"
                                           class="site-badge info">{{ __('Open') }}</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center text-muted">
                                        {{ __('You have not taken any tasks yet.') }}
                                        <a href="{{ route('user.task.index') }}">{{ __('Browse tasks') }}</a>
                                    </td>
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
@endsection
