@php
    $earnings = \App\Models\Transaction::where('type', \App\Enums\TxnType::TaskReward)->with('user')->take(6)->latest()->get();
    $completed = \App\Models\TaskSubmission::where('status', \App\Enums\TaskSubmissionStatus::Approved)
        ->with(['user', 'task'])->take(6)->latest()->get();
@endphp

@php
    $initial = function ($name) {
        return mb_strtoupper(mb_substr(trim((string) $name) ?: 'U', 0, 1));
    };
@endphp

<section class="section relative overflow-hidden">
    <div class="glow-blob -right-40 bottom-0 h-96 w-96 opacity-35"></div>

    <div class="shell relative">
        <div class="section-head-center" data-reveal>
            @if(!empty($data['title_small']))
                <span class="eyebrow">{{ $data['title_small'] }}</span>
            @endif
            <h2 class="section-title">{{ $data['title_big'] ?? '' }}</h2>
        </div>

        <div class="mt-12 grid gap-6 lg:grid-cols-2">

            {{-- earnings feed --------------------------------------------- --}}
            <div class="overflow-hidden rounded-3xl border border-[rgb(var(--line)/0.09)] bg-[rgb(var(--surface-raised))] shadow-soft"
                 data-reveal>
                <div class="flex items-center justify-between border-b border-[rgb(var(--line)/0.07)] px-6 py-5">
                    <div class="flex items-center gap-3">
                        <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-earn-500/12 text-earn-600 dark:text-earn-300">
                            <svg class="h-4.5 w-4.5" fill="none" viewBox="0 0 24 24" stroke-width="1.9" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18 9 11.25l4.306 4.306a11.95 11.95 0 0 1 5.814-5.518l2.74-1.22m0 0-5.94-2.281m5.94 2.28-2.28 5.941"/>
                            </svg>
                        </span>
                        <h3 class="text-base font-semibold text-[rgb(var(--text-strong))]">{{ __('Recent Task Earnings') }}</h3>
                    </div>
                    <span class="badge-earn">{{ __('Live') }}</span>
                </div>

                <div class="divide-y divide-[rgb(var(--line)/0.06)]">
                    @forelse($earnings as $txn)
                        <div class="flex items-center gap-4 px-6 py-4 transition-colors hover:bg-[rgb(var(--line)/0.025)]">
                            <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-brand-500/10 font-display text-sm font-bold text-brand-600 dark:text-brand-300">
                                {{ $initial($txn->user->full_name ?? 'U') }}
                            </span>
                            <div class="min-w-0 flex-1">
                                <p class="truncate text-sm font-semibold text-[rgb(var(--text-strong))]">
                                    {{ $txn->user->full_name ?? __('Member') }}
                                </p>
                                <p class="text-xs text-[rgb(var(--text-muted))]">{{ $txn->created_at }}</p>
                            </div>
                            <span class="shrink-0 font-display text-sm font-bold text-earn-600 dark:text-earn-400">
                                +{{ $currencySymbol }}{{ $txn->amount }}
                            </span>
                        </div>
                    @empty
                        <p class="px-6 py-10 text-center text-sm text-[rgb(var(--text-muted))]">{{ __('No earnings yet.') }}</p>
                    @endforelse
                </div>
            </div>

            {{-- completed tasks feed --------------------------------------- --}}
            <div class="overflow-hidden rounded-3xl border border-[rgb(var(--line)/0.09)] bg-[rgb(var(--surface-raised))] shadow-soft"
                 data-reveal data-reveal-delay="100">
                <div class="flex items-center justify-between border-b border-[rgb(var(--line)/0.07)] px-6 py-5">
                    <div class="flex items-center gap-3">
                        <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-brand-500/12 text-brand-600 dark:text-brand-300">
                            <svg class="h-4.5 w-4.5" fill="none" viewBox="0 0 24 24" stroke-width="1.9" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                            </svg>
                        </span>
                        <h3 class="text-base font-semibold text-[rgb(var(--text-strong))]">{{ __('Tasks Completed') }}</h3>
                    </div>
                    <span class="badge-brand">{{ __('Accepted') }}</span>
                </div>

                <div class="divide-y divide-[rgb(var(--line)/0.06)]">
                    @forelse($completed as $submission)
                        <div class="flex items-center gap-4 px-6 py-4 transition-colors hover:bg-[rgb(var(--line)/0.025)]">
                            <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-[rgb(var(--line)/0.07)] font-display text-sm font-bold text-[rgb(var(--text-muted))]">
                                {{ $initial($submission->user->full_name ?? 'U') }}
                            </span>
                            <div class="min-w-0 flex-1">
                                <p class="truncate text-sm font-semibold text-[rgb(var(--text-strong))]">
                                    {{ $submission->task->title ?? __('Task') }}
                                </p>
                                <p class="text-xs text-[rgb(var(--text-muted))]">
                                    {{ $submission->user->full_name ?? __('Member') }} · {{ $submission->created_at }}
                                </p>
                            </div>
                            <span class="shrink-0 font-display text-sm font-bold text-[rgb(var(--text-strong))]">
                                {{ $currencySymbol }}{{ $submission->pay_amount }}
                            </span>
                        </div>
                    @empty
                        <p class="px-6 py-10 text-center text-sm text-[rgb(var(--text-muted))]">{{ __('No completed tasks yet.') }}</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</section>
