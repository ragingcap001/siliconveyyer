@extends('frontend::layouts.user')
@section('title')
    {{ $task->title }}
@endsection
@section('content')
    <div class="row">
        <div class="col-xl-8">
            <div class="site-card">
                <div class="site-card-header">
                    <h3 class="title">{{ $task->title }}</h3>
                    @if($task->category)
                        <span class="site-badge info">{{ $task->category }}</span>
                    @endif
                </div>
                <div class="site-card-body">
                    <div class="task-description">
                        {!! $task->description !!}
                    </div>

                    @if($task->instructions)
                        <hr>
                        <h5>{{ __('What you need to do') }}</h5>
                        <div class="task-instructions">
                            {!! $task->instructions !!}
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-xl-4">
            <div class="site-card mb-3">
                <div class="site-card-header">
                    <h3 class="title">{{ __('Task Summary') }}</h3>
                </div>
                <div class="site-card-body">
                    <dl class="row mb-0">
                        <dt class="col-6">{{ __('Pay') }}</dt>
                        <dd class="col-6">
                            <strong class="green-color">{{ $currencySymbol }}{{ $task->pay_amount }}</strong>
                        </dd>

                        <dt class="col-6">{{ __('Proof Required') }}</dt>
                        <dd class="col-6">{{ $task->proof_type->label() }}</dd>

                        <dt class="col-6">{{ __('Minimum Level') }}</dt>
                        <dd class="col-6">{{ $task->min_level }}</dd>

                        @if($task->min_balance > 0)
                            <dt class="col-6">{{ __('Minimum Balance') }}</dt>
                            <dd class="col-6">{{ $currencySymbol }}{{ $task->min_balance }}</dd>
                        @endif

                        <dt class="col-6">{{ __('KYC') }}</dt>
                        <dd class="col-6">{{ $task->require_kyc ? __('Required') : __('Not required') }}</dd>

                        <dt class="col-6">{{ __('Slots') }}</dt>
                        <dd class="col-6">
                            @if($task->hasUnlimitedSlots())
                                {{ __('Unlimited') }}
                            @else
                                {{ $task->slotsRemaining() }} / {{ $task->total_slots }}
                            @endif
                        </dd>

                        <dt class="col-6">{{ __('Attempts') }}</dt>
                        <dd class="col-6">{{ $task->per_user_limit }} {{ __('per user') }}</dd>

                        @if($task->expires_at)
                            <dt class="col-6">{{ __('Deadline') }}</dt>
                            <dd class="col-6">{{ $task->expires_at->format('d M Y') }}</dd>
                        @endif
                    </dl>
                </div>
            </div>

            {{-- ================= not yet taken ================= --}}
            @if(!$submission)
                <div class="site-card">
                    <div class="site-card-header">
                        <h3 class="title">{{ __('Take This Task') }}</h3>
                    </div>
                    <div class="site-card-body">
                        @if($eligibilityError)
                            <div class="site-badge warning w-100 mb-3">{{ $eligibilityError }}</div>
                            <a href="{{ route('user.task.index') }}" class="site-btn outline-btn w-100">
                                {{ __('Back To Tasks') }}
                            </a>
                        @else
                            <form action="{{ route('user.task.take', $task->id) }}" method="post">
                                @csrf
                                <div class="mb-3">
                                    <label class="form-label">{{ __('Choose how you want to be paid') }}</label>
                                    <select name="payout_method_id" id="payout_method_id" class="form-control" required>
                                        <option value="">{{ __('Select payout method') }}</option>
                                        @foreach($payoutMethods as $method)
                                            <option value="{{ $method->id }}">{{ $method->name }}</option>
                                        @endforeach
                                    </select>
                                    <small class="text-muted">
                                        {{ __('Only payout methods enabled by the admin are offered.') }}
                                    </small>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">{{ __('Payout account') }}</label>
                                    <select name="withdraw_account_id" id="withdraw_account_id" class="form-control"
                                            required disabled>
                                        <option value="">{{ __('Select a payout method first') }}</option>
                                    </select>
                                    @if($accounts->isEmpty())
                                        <small class="text-danger d-block mt-1">
                                            {{ __('You have no saved payout accounts yet.') }}
                                            <a href="{{ route('user.withdraw.account.create') }}">{{ __('Add one') }}</a>
                                        </small>
                                    @endif
                                </div>

                                <button type="submit" class="site-btn grad-btn w-100">
                                    {{ __('Take Task') }}
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            @endif

            {{-- ================= taken: submit or show proof ================= --}}
            @if($submission)
                <div class="site-card">
                    <div class="site-card-header">
                        <h3 class="title">{{ __('Your Submission') }}</h3>
                        <span class="site-badge {{ $submission->status->color() }}">
                            {{ $submission->status->label() }}
                        </span>
                    </div>
                    <div class="site-card-body">
                        @if($submission->isRejected() && $submission->admin_note)
                            <div class="site-badge danger w-100 mb-3">
                                {{ __('Rejected') }}: {{ $submission->admin_note }}
                            </div>
                        @endif

                        @if($submission->isApproved())
                            <div class="site-badge success w-100 mb-3">
                                {{ __('Approved and paid') }}
                                {{ $currencySymbol }}{{ $submission->pay_amount }}
                                @if($submission->paid_at)
                                    &middot; {{ $submission->paid_at->format('d M Y') }}
                                @endif
                            </div>
                        @endif

                        @if($submission->isPending())
                            <form action="{{ route('user.task.proof', $submission->id) }}" method="post"
                                  enctype="multipart/form-data">
                                @csrf
                                <p class="small text-muted">{{ $task->proof_type->hint() }}</p>

                                @if($task->proof_type->value === 'text')
                                    <div class="mb-3">
                                        <textarea name="proof_text" class="form-control" rows="4" required
                                                  placeholder="{{ __('Describe what you did') }}">{{ $submission->proof_text }}</textarea>
                                    </div>
                                @elseif($task->proof_type->value === 'link')
                                    <div class="mb-3">
                                        <input type="url" name="proof_link" class="form-control" required
                                               placeholder="https://"
                                               value="{{ $submission->proof_link }}">
                                    </div>
                                @else
                                    <div class="mb-3">
                                        <input type="file" name="proof_file" class="form-control"
                                               @if(!$submission->proof_file) required @endif>
                                        @if($submission->proof_file)
                                            <small class="text-muted d-block mt-1">
                                                {{ __('Currently uploaded') }}:
                                                <a href="{{ asset('assets/'.$submission->proof_file) }}"
                                                   target="_blank">{{ __('view file') }}</a>
                                            </small>
                                        @endif
                                    </div>
                                @endif

                                <button type="submit" class="site-btn grad-btn w-100">
                                    {{ $submission->proofValue() ? __('Resubmit Proof') : __('Submit Proof') }}
                                </button>
                            </form>
                        @elseif($submission->proofValue())
                            <div class="mb-2">
                                <strong>{{ __('Your proof') }}:</strong>
                                @if($submission->proof_file)
                                    <a href="{{ asset('assets/'.$submission->proof_file) }}"
                                       target="_blank">{{ __('view file') }}</a>
                                @elseif($submission->proof_link)
                                    <a href="{{ $submission->proof_link }}"
                                       target="_blank">{{ $submission->proof_link }}</a>
                                @else
                                    <div class="small">{{ $submission->proof_text }}</div>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection

@section('script')
    <script>
        (function ($) {
            "use strict";

            // payout accounts the worker has saved, grouped by method
            var accounts = @json($accounts);

            $('#payout_method_id').on('change', function () {
                var methodId = $(this).val();
                var $account = $('#withdraw_account_id');

                $account.empty();
                $account.prop('disabled', true);

                if (!accounts[methodId] || accounts[methodId].length === 0) {
                    $account.append($('<option>').val('').text('{{ __("No saved account for this method") }}'));
                    return;
                }

                $account.append($('<option>').val('').text('{{ __("Select account") }}'));
                accounts[methodId].forEach(function (acc) {
                    $account.append($('<option>').val(acc.id).text(acc.method_name));
                });
                $account.prop('disabled', false);
            });
        })(jQuery);
    </script>
@endsection
