@extends('frontend::layouts.user')

@section('title'){{ __('Dashboard') }}@endsection
@section('subtitle'){{ __('Your earnings, tasks and activity at a glance.') }}@endsection

@section('content')
    <div class="space-y-6">

        {{-- ================= greeting + balance ================= --}}
        <div data-reveal class="relative overflow-hidden rounded-3xl border border-[rgb(var(--line)/0.09)] bg-[rgb(var(--surface-raised))] p-6 shadow-soft sm:p-8">
            <div class="pointer-events-none absolute inset-0 opacity-[0.35]" style="background-image: radial-gradient(rgb(var(--line) / 0.16) 1px, transparent 1px); background-size: 18px 18px;"></div>
            <div class="glow-blob pointer-events-none -right-24 -top-24 h-64 w-64 opacity-40"></div>

            <div class="relative flex flex-col gap-6 sm:flex-row sm:items-center sm:justify-between">
                <div class="min-w-0">
                    <p class="text-xs font-semibold uppercase tracking-[0.14em] text-[rgb(var(--text-muted))]">
                        {{ __('Available balance') }}
                    </p>
                    <p class="mt-2 font-display text-4xl font-bold tracking-tight text-[rgb(var(--text-strong))]">
                        <span class="text-2xl text-[rgb(var(--text-muted))]">{{ $currencySymbol }}</span>{{ number_format((float) $user->profit_balance, 2) }}
                    </p>
                    <p class="mt-2 text-sm text-[rgb(var(--text-muted))]">
                        {{ __('Welcome back,') }} <span class="font-semibold text-[rgb(var(--text-strong))]">{{ $user->full_name }}</span>
                        @if($dataCount['pending_task'] > 0)
                            · <span class="font-semibold text-amber-600 dark:text-amber-400">{{ $dataCount['pending_task'] }} {{ __('awaiting review') }}</span>
                        @endif
                    </p>
                </div>

                <a href="{{ route('user.task.index') }}" class="btn-primary btn-lg shrink-0">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                    </svg>
                    {{ __('Find tasks') }}
                </a>
            </div>
        </div>

        {{-- ================= stat cards ================= --}}
        @include('frontend::user.include.__user_card')

        {{-- ================= tasks + submissions ================= --}}
        <div class="grid gap-6 lg:grid-cols-2">

            {{-- available tasks --}}
            <div data-reveal class="overflow-hidden rounded-3xl border border-[rgb(var(--line)/0.09)] bg-[rgb(var(--surface-raised))] shadow-soft">
                <div class="flex items-center justify-between border-b border-[rgb(var(--line)/0.07)] px-6 py-5">
                    <h3 class="text-base font-semibold text-[rgb(var(--text-strong))]">{{ __('Tasks you can claim') }}</h3>
                    <a href="{{ route('user.task.index') }}" class="link-arrow">{{ __('View all') }}</a>
                </div>

                @if($availableTasks->isEmpty())
                    <div class="px-6 py-12 text-center">
                        <p class="text-sm text-[rgb(var(--text-muted))]">{{ __('No tasks available right now.') }}</p>
                        <p class="mt-1 text-xs text-[rgb(var(--text-muted))]">{{ __('New tasks are posted regularly, so check back soon.') }}</p>
                    </div>
                @else
                    <div class="divide-y divide-[rgb(var(--line)/0.06)]">
                        @foreach($availableTasks as $task)
                            <a href="{{ route('user.task.show', $task->id) }}"
                               class="flex items-center gap-4 px-6 py-4 transition-colors hover:bg-[rgb(var(--line)/0.025)]">
                                <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-brand-500/10">
                                    <svg class="h-5 w-5 text-brand-600 dark:text-brand-300" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                                    </svg>
                                </span>

                                <div class="min-w-0 flex-1">
                                    <p class="truncate text-sm font-semibold text-[rgb(var(--text-strong))]">{{ $task->title }}</p>
                                    <div class="mt-1 flex flex-wrap items-center gap-x-3 gap-y-1 text-xs text-[rgb(var(--text-muted))]">
                                        <span class="font-semibold text-earn-600 dark:text-earn-400">
                                            {{ $currencySymbol }}{{ number_format((float) $task->pay_amount, 2) }}
                                        </span>
                                        @if($task->total_slots)
                                            <span>{{ $task->slotsRemaining() === -1 ? __('Unlimited') : $task->slotsRemaining().' '.__('slots left') }}</span>
                                        @endif
                                        @if($task->expires_at)
                                            <span>{{ __('Expires :date', ['date' => $task->expires_at->diffForHumans()]) }}</span>
                                        @endif
                                    </div>
                                </div>

                                <svg class="h-4 w-4 shrink-0 text-[rgb(var(--text-muted))]" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5"/>
                                </svg>
                            </a>
                        @endforeach
                    </div>
                @endif
            </div>

            {{-- my submissions --}}
            <div data-reveal data-reveal-delay="80" class="overflow-hidden rounded-3xl border border-[rgb(var(--line)/0.09)] bg-[rgb(var(--surface-raised))] shadow-soft">
                <div class="flex items-center justify-between border-b border-[rgb(var(--line)/0.07)] px-6 py-5">
                    <h3 class="text-base font-semibold text-[rgb(var(--text-strong))]">{{ __('My submissions') }}</h3>
                    <a href="{{ route('user.task.history') }}" class="link-arrow">{{ __('View all') }}</a>
                </div>

                @if($recentSubmissions->isEmpty())
                    <div class="px-6 py-12 text-center">
                        <p class="text-sm text-[rgb(var(--text-muted))]">{{ __('You have not submitted any work yet.') }}</p>
                        <p class="mt-1 text-xs text-[rgb(var(--text-muted))]">{{ __('Claim a task, follow the instructions, then send your proof.') }}</p>
                    </div>
                @else
                    <div class="divide-y divide-[rgb(var(--line)/0.06)]">
                        @foreach($recentSubmissions as $submission)
                            @php
                                $badge = match ($submission->status) {
                                    \App\Enums\TaskSubmissionStatus::Approved => 'badge-earn',
                                    \App\Enums\TaskSubmissionStatus::Rejected => 'badge-danger',
                                    default => 'badge-warn',
                                };
                            @endphp
                            <div class="flex items-center gap-4 px-6 py-4">
                                <div class="min-w-0 flex-1">
                                    <p class="truncate text-sm font-semibold text-[rgb(var(--text-strong))]">
                                        {{ $submission->task->title ?? __('Task') }}
                                    </p>
                                    <p class="mt-1 text-xs text-[rgb(var(--text-muted))]">{{ $submission->created_at }}</p>
                                </div>
                                <span class="shrink-0 text-sm font-bold text-[rgb(var(--text-strong))]">
                                    {{ $currencySymbol }}{{ number_format((float) $submission->pay_amount, 2) }}
                                </span>
                                <span class="{{ $badge }} shrink-0">{{ $submission->status->name }}</span>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>

        {{-- ================= referral link ================= --}}
        @if(setting('sign_up_referral','permission') && $referral)
            <div data-reveal class="flex flex-col gap-4 rounded-3xl border border-[rgb(var(--line)/0.09)] bg-[rgb(var(--surface-raised))] p-6 shadow-soft sm:flex-row sm:items-center">
                <div class="min-w-0 flex-1">
                    <h3 class="text-base font-semibold text-[rgb(var(--text-strong))]">{{ __('Invite and earn') }}</h3>
                    <p class="mt-1 text-sm text-[rgb(var(--text-muted))]">
                        {{ __('Share your link. You earn a share of what your referrals earn on tasks.') }}
                    </p>
                    <p class="mt-2 text-xs text-[rgb(var(--text-muted))]">
                        <span class="font-semibold text-[rgb(var(--text-strong))]">{{ $referral->relationships()->count() }}</span>
                        {{ __('peoples are joined by using this URL') }}
                    </p>
                </div>

                <div class="flex w-full gap-2 sm:w-auto sm:min-w-[320px]">
                    <input id="refLink" type="text" readonly value="{{ $referral->link }}"
                           class="field flex-1 font-mono text-xs"/>
                    <button type="button" onclick="copyRef()" class="btn-primary shrink-0">
                        <span id="copy">{{ __('Copy') }}</span>
                    </button>
                </div>
            </div>
        @endif

        {{-- ================= recent earnings ================= --}}
        @include('frontend::user.include.__recent_transaction')

    </div>
@endsection

@section('script')
    <script>
        function copyRef() {
            var copyApi = document.getElementById('refLink');
            if (!copyApi) return;

            copyApi.select();
            copyApi.setSelectionRange(0, 999999999);

            if (navigator.clipboard && window.isSecureContext) {
                navigator.clipboard.writeText(copyApi.value);
            } else {
                document.execCommand('copy');
            }

            var btn = document.getElementById('copy');
            if (btn) {
                var previous = btn.textContent;
                btn.textContent = '{{ __('Copied') }}';
                setTimeout(function () { btn.textContent = previous; }, 1800);
            }
        }
    </script>
@endsection
