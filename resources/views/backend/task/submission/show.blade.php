@extends('backend.layouts.app')
@section('title')
    {{ __('Review Submission') }}
@endsection
@section('content')
    <div class="main-content">
        <div class="page-title">
            <div class="container-fluid">
                <div class="row">
                    <div class="col">
                        <div class="title-content">
                            <h2 class="title">{{ __('Review Submission') }}</h2>
                            <a href="{{ route('admin.task.submission.index') }}"
                               class="title-btn">{{ __('Back To List') }}</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="container-fluid">
            <div class="row">
                <div class="col-xl-7">
                    <div class="site-card">
                        <div class="site-card-header">
                            <h4>{{ __('Submitted Proof') }}</h4>
                        </div>
                        <div class="site-card-body">
                            <dl class="row mb-0">
                                <dt class="col-sm-4">{{ __('Task') }}</dt>
                                <dd class="col-sm-8">{{ $submission->task->title }}</dd>

                                <dt class="col-sm-4">{{ __('Proof Required') }}</dt>
                                <dd class="col-sm-8">{{ $submission->task->proof_type->label() }}</dd>

                                <dt class="col-sm-4">{{ __('Instructions') }}</dt>
                                <dd class="col-sm-8">{!! $submission->task->instructions ?: '—' !!}</dd>
                            </dl>

                            <hr>

                            @if($submission->proof_file)
                                @if(Str::endsWith(strtolower($submission->proof_file), ['jpg','jpeg','png','gif','webp']))
                                    <a href="{{ asset('assets/'.$submission->proof_file) }}" target="_blank">
                                        <img src="{{ asset('assets/'.$submission->proof_file) }}"
                                             alt="proof" class="img-fluid rounded">
                                    </a>
                                @else
                                    <a href="{{ asset('assets/'.$submission->proof_file) }}" target="_blank"
                                       class="site-btn grad-btn">{{ __('Download Proof File') }}</a>
                                @endif
                            @elseif($submission->proof_link)
                                <a href="{{ $submission->proof_link }}" target="_blank">{{ $submission->proof_link }}</a>
                            @elseif($submission->proof_text)
                                <div class="p-3 bg-light rounded">{{ $submission->proof_text }}</div>
                            @else
                                <p class="text-muted mb-0">{{ __('The worker has not submitted proof yet.') }}</p>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="col-xl-5">
                    <div class="site-card">
                        <div class="site-card-header">
                            <h4>{{ __('Worker & Payout') }}</h4>
                        </div>
                        <div class="site-card-body">
                            <dl class="row mb-0">
                                <dt class="col-sm-5">{{ __('Worker') }}</dt>
                                <dd class="col-sm-7">{{ $submission->user->full_name }}
                                    <div class="small text-muted">{{ safe($submission->user->email) }}</div>
                                </dd>

                                <dt class="col-sm-5">{{ __('Payout Method') }}</dt>
                                <dd class="col-sm-7">{{ $submission->payoutMethod->name ?: '—' }}</dd>

                                <dt class="col-sm-5">{{ __('Payout Account') }}</dt>
                                <dd class="col-sm-7">
                                    {{ $submission->withdrawAccount->method_name ?: '—' }}
                                </dd>

                                <dt class="col-sm-5">{{ __('Amount') }}</dt>
                                <dd class="col-sm-7">
                                    <strong>{{ $currencySymbol }}{{ $submission->pay_amount }}</strong>
                                </dd>

                                <dt class="col-sm-5">{{ __('Status') }}</dt>
                                <dd class="col-sm-7">
                                    <div class="site-badge {{ $submission->status->color() }}">
                                        {{ $submission->status->label() }}
                                    </div>
                                </dd>

                                <dt class="col-sm-5">{{ __('Attempt') }}</dt>
                                <dd class="col-sm-7">{{ $submission->attempt }}</dd>
                            </dl>
                        </div>
                    </div>

                    @if($submission->isPending())
                        <div class="site-card mt-3">
                            <div class="site-card-header">
                                <h4>{{ __('Decision') }}</h4>
                            </div>
                            <div class="site-card-body">
                                <p class="text-muted small">
                                    {{ __('Approving credits the worker wallet immediately and releases the slot. Rejecting returns the attempt to the worker so they can try again.') }}
                                </p>

                                <form method="POST"
                                      action="{{ route('admin.task.submission.approve', $submission->id) }}">
                                    @csrf
                                    <div class="mb-3">
                                        <label class="form-label">{{ __('Note (optional)') }}</label>
                                        <textarea name="admin_note" class="form-control" rows="2"></textarea>
                                    </div>
                                    @can('task-submission-action')
                                        <button type="submit" class="site-btn grad-btn w-100 mb-2"
                                                onclick="return confirm('{{ __('Approve and pay this worker?') }}')">
                                            {{ __('Approve & Pay') }} {{ $currencySymbol }}{{ $submission->pay_amount }}
                                        </button>
                                    @endcan
                                </form>

                                <form method="POST"
                                      action="{{ route('admin.task.submission.reject', $submission->id) }}">
                                    @csrf
                                    <div class="mb-3">
                                        <label class="form-label">{{ __('Reason for rejection') }}</label>
                                        <textarea name="admin_note" class="form-control" rows="2"
                                                  placeholder="{{ __('Explain what was wrong so the worker can fix it.') }}"></textarea>
                                    </div>
                                    @can('task-submission-action')
                                        <button type="submit" class="site-btn red-btn w-100"
                                                onclick="return confirm('{{ __('Reject this submission?') }}')">
                                            {{ __('Reject') }}
                                        </button>
                                    @endcan
                                </form>
                            </div>
                        </div>
                    @else
                        <div class="site-card mt-3">
                            <div class="site-card-body">
                                <p class="mb-1">
                                    <strong>{{ __('Reviewed by') }}:</strong>
                                    {{ $submission->reviewer->name ?: __('System') }}
                                </p>
                                <p class="mb-1">
                                    <strong>{{ __('Reviewed at') }}:</strong>
                                    {{ $submission->reviewed_at?->format('d M Y h:i') }}
                                </p>
                                @if($submission->paid_at)
                                    <p class="mb-1">
                                        <strong>{{ __('Paid at') }}:</strong>
                                        {{ $submission->paid_at->format('d M Y h:i') }}
                                    </p>
                                @endif
                                @if($submission->admin_note)
                                    <hr>
                                    <p class="mb-0"><strong>{{ __('Note') }}:</strong> {{ $submission->admin_note }}</p>
                                @endif
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
