@extends('frontend::layouts.user')

@section('title'){{ $task->title }}@endsection

@section('content')
    <div class="grid gap-6 lg:grid-cols-12">

        {{-- ===================== main ===================== --}}
        <div class="lg:col-span-8">
            <article class="overflow-hidden rounded-3xl border border-[rgb(var(--line)/0.09)] bg-[rgb(var(--surface-raised))] shadow-soft">

                <div class="border-b border-[rgb(var(--line)/0.07)] px-6 py-6 sm:px-8">
                    <div class="flex flex-wrap items-center gap-2">
                        @if($task->category)
                            <span class="badge-brand">{{ $task->category }}</span>
                        @endif
                        @if($task->require_kyc)
                            <span class="badge-warn">{{ __('KYC required') }}</span>
                        @endif
                        <span class="badge-neutral">{{ $task->proof_type->label() }}</span>
                    </div>

                    <h2 class="mt-4 text-2xl font-bold leading-snug tracking-tight text-[rgb(var(--text-strong))]">
                        {{ $task->title }}
                    </h2>
                </div>

                <div class="px-6 py-6 sm:px-8">
                    <div class="prose-task">{!! $task->description !!}</div>

                    @if($task->instructions)
                        <div class="mt-8 rounded-2xl border border-brand-500/20 bg-brand-500/[0.05] p-5 sm:p-6">
                            <h3 class="flex items-center gap-2.5 text-base font-semibold text-[rgb(var(--text-strong))]">
                                <span class="flex h-7 w-7 items-center justify-center rounded-lg bg-brand-500/15 text-brand-600 dark:text-brand-300">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 6.75h12M8.25 12h12m-12 5.25h12M3.75 6.75h.007v.008H3.75V6.75Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0ZM3.75 12h.007v.008H3.75V12Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm-.375 5.25h.007v.008H3.75v-.008Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z"/>
                                    </svg>
                                </span>
                                {{ __('What you need to do') }}
                            </h3>
                            <div class="prose-task mt-3">{!! $task->instructions !!}</div>
                        </div>
                    @endif
                </div>
            </article>
        </div>

        {{-- ===================== sidebar ===================== --}}
        <div class="space-y-6 lg:col-span-4">

            {{-- summary --}}
            <div class="overflow-hidden rounded-3xl border border-[rgb(var(--line)/0.09)] bg-[rgb(var(--surface-raised))] shadow-soft">
                <div class="border-b border-[rgb(var(--line)/0.07)] px-6 py-5">
                    <h3 class="text-base font-semibold text-[rgb(var(--text-strong))]">{{ __('Task Summary') }}</h3>
                </div>

                <dl class="divide-y divide-[rgb(var(--line)/0.06)] px-6">
                    <div class="flex items-center justify-between py-3.5">
                        <dt class="text-sm text-[rgb(var(--text-muted))]">{{ __('Pay') }}</dt>
                        <dd class="font-display text-lg font-bold text-earn-600 dark:text-earn-400">{{ $currencySymbol }}{{ $task->pay_amount }}</dd>
                    </div>
                    <div class="flex items-center justify-between py-3.5">
                        <dt class="text-sm text-[rgb(var(--text-muted))]">{{ __('Proof Required') }}</dt>
                        <dd class="text-sm font-medium text-[rgb(var(--text-strong))]">{{ $task->proof_type->label() }}</dd>
                    </div>
                    <div class="flex items-center justify-between py-3.5">
                        <dt class="text-sm text-[rgb(var(--text-muted))]">{{ __('Minimum Level') }}</dt>
                        <dd class="text-sm font-medium text-[rgb(var(--text-strong))]">{{ $task->min_level }}</dd>
                    </div>
                    @if($task->min_balance > 0)
                        <div class="flex items-center justify-between py-3.5">
                            <dt class="text-sm text-[rgb(var(--text-muted))]">{{ __('Minimum Balance') }}</dt>
                            <dd class="text-sm font-medium text-[rgb(var(--text-strong))]">{{ $currencySymbol }}{{ $task->min_balance }}</dd>
                        </div>
                    @endif
                    <div class="flex items-center justify-between py-3.5">
                        <dt class="text-sm text-[rgb(var(--text-muted))]">{{ __('KYC') }}</dt>
                        <dd class="text-sm font-medium text-[rgb(var(--text-strong))]">{{ $task->require_kyc ? __('Required') : __('Not required') }}</dd>
                    </div>
                    <div class="flex items-center justify-between py-3.5">
                        <dt class="text-sm text-[rgb(var(--text-muted))]">{{ __('Slots') }}</dt>
                        <dd class="text-sm font-medium text-[rgb(var(--text-strong))]">
                            @if($task->hasUnlimitedSlots())
                                {{ __('Unlimited') }}
                            @else
                                {{ $task->slotsRemaining() }} / {{ $task->total_slots }}
                            @endif
                        </dd>
                    </div>
                    <div class="flex items-center justify-between py-3.5">
                        <dt class="text-sm text-[rgb(var(--text-muted))]">{{ __('Attempts') }}</dt>
                        <dd class="text-sm font-medium text-[rgb(var(--text-strong))]">{{ $task->per_user_limit }} {{ __('per user') }}</dd>
                    </div>
                    @if($task->expires_at)
                        <div class="flex items-center justify-between py-3.5">
                            <dt class="text-sm text-[rgb(var(--text-muted))]">{{ __('Deadline') }}</dt>
                            <dd class="text-sm font-medium text-[rgb(var(--text-strong))]">{{ $task->expires_at->format('d M Y') }}</dd>
                        </div>
                    @endif
                </dl>
            </div>

            {{-- ================= not yet taken ================= --}}
            @unless($submission)
                <div class="overflow-hidden rounded-3xl border border-[rgb(var(--line)/0.09)] bg-[rgb(var(--surface-raised))] shadow-soft">
                    <div class="border-b border-[rgb(var(--line)/0.07)] px-6 py-5">
                        <h3 class="text-base font-semibold text-[rgb(var(--text-strong))]">{{ __('Take This Task') }}</h3>
                    </div>

                    <div class="p-6">
                        @if($eligibilityError)
                            <div class="flex items-start gap-3 rounded-2xl border border-amber-500/25 bg-amber-500/[0.07] p-4">
                                <svg class="mt-0.5 h-5 w-5 shrink-0 text-amber-600 dark:text-amber-400" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z"/>
                                </svg>
                                <p class="text-sm leading-relaxed text-amber-700 dark:text-amber-300">{{ $eligibilityError }}</p>
                            </div>
                            <a href="{{ route('user.task.index') }}" class="btn-outline btn-block mt-4">{{ __('Back To Tasks') }}</a>
                        @else
                            {{-- $accounts is a collection grouped by withdraw_method_id --}}
                            <form action="{{ route('user.task.take', $task->id) }}" method="post"
                                  x-data="{
                                      accounts: {{ json_encode($accounts->map(fn($g) => $g->map(fn($a) => ['id' => $a->id, 'label' => $a->method_name]))) }},
                                      methodId: '',
                                      get options() { return this.accounts[this.methodId] || []; }
                                  }">

                                @csrf

                                <label class="field-label" for="payout_method_id">{{ __('Choose how you want to be paid') }}</label>
                                <select name="payout_method_id" id="payout_method_id" x-model="methodId" class="field" required>
                                    <option value="">{{ __('Select payout method') }}</option>
                                    @foreach($payoutMethods as $method)
                                        <option value="{{ $method->id }}">{{ $method->name }}</option>
                                    @endforeach
                                </select>
                                <p class="mt-2 text-xs text-[rgb(var(--text-muted))]">
                                    {{ __('Only payout methods enabled by the admin are offered.') }}
                                </p>

                                <div class="mt-5">
                                    <label class="field-label" for="withdraw_account_id">{{ __('Payout account') }}</label>
                                    <select name="withdraw_account_id" id="withdraw_account_id" class="field" required
                                            :disabled="!methodId || options.length === 0">
                                        <option value="" x-text="!methodId
                                            ? '{{ __('Select a payout method first') }}'
                                            : (options.length ? '{{ __('Select account') }}' : '{{ __('No saved account for this method') }}')"></option>
                                        <template x-for="acc in options" :key="acc.id">
                                            <option :value="acc.id" x-text="acc.label"></option>
                                        </template>
                                    </select>

                                    @if($accounts->isEmpty())
                                        <p class="mt-2 text-xs text-rose-600 dark:text-rose-400">
                                            {{ __('You have no saved payout accounts yet.') }}
                                            <a href="{{ route('user.withdraw.account.create') }}" class="font-semibold underline">{{ __('Add one') }}</a>
                                        </p>
                                    @endif
                                </div>

                                <button type="submit" class="btn-primary btn-block mt-6">{{ __('Take Task') }}</button>
                            </form>
                        @endif
                    </div>
                </div>
            @endunless

            {{-- ================= taken: submit or show proof ================= --}}
            @if($submission)
                <div class="overflow-hidden rounded-3xl border border-[rgb(var(--line)/0.09)] bg-[rgb(var(--surface-raised))] shadow-soft">
                    <div class="flex items-center justify-between border-b border-[rgb(var(--line)/0.07)] px-6 py-5">
                        <h3 class="text-base font-semibold text-[rgb(var(--text-strong))]">{{ __('Your Submission') }}</h3>
                        <span class="badge {{ $submission->status->color() === 'success' ? 'badge-earn'
                                            : ($submission->status->color() === 'danger' ? 'badge-danger' : 'badge-warn') }}">
                            {{ $submission->status->label() }}
                        </span>
                    </div>

                    <div class="p-6">
                        @if($submission->isRejected() && $submission->admin_note)
                            <div class="rounded-2xl border border-rose-500/25 bg-rose-500/[0.07] p-4">
                                <p class="text-sm font-semibold text-rose-700 dark:text-rose-300">{{ __('Rejected') }}</p>
                                <p class="mt-1 text-sm leading-relaxed text-rose-700/90 dark:text-rose-300/90">{{ $submission->admin_note }}</p>
                                <p class="mt-2 text-xs text-[rgb(var(--text-muted))]">
                                    {{ __('A rejection does not use one of your attempts, so you can correct the work and submit again.') }}
                                </p>
                            </div>
                        @endif

                        @if($submission->isApproved())
                            <div class="flex items-center gap-3 rounded-2xl border border-earn-500/25 bg-earn-500/[0.07] p-4">
                                <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-earn-500/15 text-earn-600 dark:text-earn-400">
                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2.2" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5"/>
                                    </svg>
                                </span>
                                <div>
                                    <p class="text-sm font-semibold text-earn-700 dark:text-earn-300">
                                        {{ __('Approved and paid') }} {{ $currencySymbol }}{{ $submission->pay_amount }}
                                    </p>
                                    @if($submission->paid_at)
                                        <p class="text-xs text-[rgb(var(--text-muted))]">{{ $submission->paid_at->format('d M Y') }}</p>
                                    @endif
                                </div>
                            </div>
                        @endif

                        @if($submission->isPending())
                            <form action="{{ route('user.task.proof', $submission->id) }}" method="post" enctype="multipart/form-data">
                                @csrf

                                <p class="mb-4 text-sm leading-relaxed text-[rgb(var(--text-muted))]">{{ $task->proof_type->hint() }}</p>

                                @if($task->proof_type->value === 'text')
                                    <textarea name="proof_text" rows="5" class="field" required
                                              placeholder="{{ __('Describe what you did') }}">{{ $submission->proof_text }}</textarea>

                                @elseif($task->proof_type->value === 'link')
                                    <input type="url" name="proof_link" class="field" required
                                           placeholder="https://" value="{{ $submission->proof_link }}"/>

                                @else
                                    <input type="file" name="proof_file" class="field"
                                           @unless($submission->proof_file) required @endunless/>
                                    @if($submission->proof_file)
                                        <p class="mt-2 text-xs text-[rgb(var(--text-muted))]">
                                            {{ __('Currently uploaded') }}:
                                            <a href="{{ asset('assets/'.$submission->proof_file) }}" target="_blank"
                                               class="font-semibold text-brand-600 underline dark:text-brand-300">{{ __('view file') }}</a>
                                        </p>
                                    @endif
                                @endif

                                <button type="submit" class="btn-primary btn-block mt-5">
                                    {{ $submission->proofValue() ? __('Resubmit Proof') : __('Submit Proof') }}
                                </button>
                            </form>
                        @elseif($submission->proofValue())
                            <div class="rounded-2xl border border-[rgb(var(--line)/0.08)] bg-[rgb(var(--surface-muted))] p-4">
                                <p class="mb-2 text-xs font-semibold uppercase tracking-wider text-[rgb(var(--text-muted))]">{{ __('Your proof') }}</p>
                                @if($submission->proof_file)
                                    <a href="{{ asset('assets/'.$submission->proof_file) }}" target="_blank"
                                       class="text-sm font-semibold text-brand-600 underline dark:text-brand-300">{{ __('view file') }}</a>
                                @elseif($submission->proof_link)
                                    <a href="{{ $submission->proof_link }}" target="_blank"
                                       class="break-all text-sm font-semibold text-brand-600 underline dark:text-brand-300">{{ $submission->proof_link }}</a>
                                @else
                                    <p class="whitespace-pre-line text-sm leading-relaxed text-[rgb(var(--text-body))]">{{ $submission->proof_text }}</p>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection
