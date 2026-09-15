@extends('frontend::layouts.user')

@section('title'){{ __('Dashboard') }}@endsection

@section('content')
    <div class="space-y-6">

        {{-- greeting --}}
        <div class="flex flex-wrap items-end justify-between gap-4">
            <div>
                <p class="text-[0.7rem] font-semibold uppercase tracking-[0.16em] text-slate-400">{{ __('Client Portal') }}</p>
                <h1 class="mt-1.5 font-display text-3xl font-bold tracking-tight text-[#142235] sm:text-4xl">
                    {{ __('Good :time, :name.', ['time' => now()->hour < 12 ? __('morning') : (now()->hour < 18 ? __('afternoon') : __('evening')), 'name' => $user->first_name]) }}
                </h1>
                <p class="mt-1.5 text-sm text-slate-500">{{ __('Here is the latest status across your tasks and earnings.') }}</p>
            </div>
            <a href="{{ route('user.task.index') }}"
               class="inline-flex items-center gap-2 rounded-xl bg-[#142235] px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition-colors hover:bg-[#1c2e45]">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
                </svg>
                {{ __('Browse Tasks') }}
            </a>
        </div>

        {{-- stat cards --}}
        @include('frontend::user.include.__user_card')

        {{-- two-column: recent tasks + right panels --}}
        <div class="grid gap-6 md:grid-cols-[1fr_320px]">

            {{-- left column --}}
            <div class="space-y-6">

                {{-- recent tasks --}}
                <div class="overflow-hidden rounded-2xl border border-slate-200/80 bg-white shadow-sm">
                    <div class="flex items-center justify-between border-b border-slate-100 px-6 py-4">
                        <h3 class="text-[15px] font-semibold text-[#142235]">{{ __('Recent Tasks') }}</h3>
                        <a href="{{ route('user.task.history') }}"
                           class="text-xs font-semibold text-[#142235] hover:underline">{{ __('View all') }} &rarr;</a>
                    </div>

                    @if($recentSubmissions->isEmpty())
                        <p class="px-6 py-14 text-center text-sm text-slate-400">{{ __('No tasks yet. Browse available tasks to get started.') }}</p>
                    @else
                        <div class="divide-y divide-slate-100">
                            @foreach($recentSubmissions as $submission)
                                <div class="px-6 py-4 transition-colors hover:bg-slate-50/60">
                                    <div class="flex flex-wrap items-start justify-between gap-3">
                                        <div class="min-w-0">
                                            <div class="flex items-center gap-2">
                                                <span class="font-mono text-[0.7rem] font-semibold uppercase tracking-wider text-[#142235]/60">
                                                    {{ __('TASK') }}-{{ str_pad($submission->task_id, 6, '0', STR_PAD_LEFT) }}
                                                </span>
                                                @if($submission->isPending())
                                                    <span class="inline-flex items-center gap-1 rounded-full bg-amber-50 px-2 py-0.5 text-[0.65rem] font-semibold text-amber-600">
                                                        <span class="w-1 h-1 rounded-full bg-amber-500"></span>
                                                        {{ __('Under Review') }}
                                                    </span>
                                                @elseif($submission->isApproved())
                                                    <span class="inline-flex items-center gap-1 rounded-full bg-emerald-50 px-2 py-0.5 text-[0.65rem] font-semibold text-emerald-600">
                                                        <span class="w-1 h-1 rounded-full bg-emerald-500"></span>
                                                        {{ __('Approved') }}
                                                    </span>
                                                @else
                                                    <span class="inline-flex items-center gap-1 rounded-full bg-rose-50 px-2 py-0.5 text-[0.65rem] font-semibold text-rose-600">
                                                        <span class="w-1 h-1 rounded-full bg-rose-500"></span>
                                                        {{ __('Rejected') }}
                                                    </span>
                                                @endif
                                            </div>
                                            <p class="mt-1.5 text-sm font-semibold text-[#142235]">
                                                {{ $submission->task->title }}
                                            </p>
                                            <div class="mt-1 flex items-center gap-2 text-xs text-slate-500">
                                                <span>{{ __('Payout:') }}</span>
                                                <span class="font-semibold text-[#142235]">
                                                    {{ $currencySymbol }}{{ number_format((float) $submission->pay_amount, 2) }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mt-3 flex flex-wrap items-center gap-4 border-t border-slate-100 pt-3 text-xs text-slate-400">
                                        <span>{{ __('Submitted :date', ['date' => $submission->created_at->diffForHumans()]) }}</span>
                                        @if($submission->status->value === \App\Enums\TaskSubmissionStatus::Approved->value && $submission->paid_at)
                                            <span>{{ __('Paid :date', ['date' => $submission->paid_at->diffForHumans()]) }}</span>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>

                {{-- quick actions --}}
                <div class="grid gap-4 sm:grid-cols-3">
                    <a href="{{ route('user.task.index') }}"
                       class="group rounded-2xl border border-slate-200/80 bg-white p-5 shadow-sm transition-all duration-200 hover:-translate-y-0.5 hover:border-[#142235]/15 hover:shadow-md">
                        <span class="flex items-center justify-center w-10 h-10 rounded-xl bg-[#142235]/8 text-[#142235]">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.7" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
                            </svg>
                        </span>
                        <p class="mt-3 text-sm font-bold text-[#142235]">{{ __('Take a Task') }}</p>
                        <p class="mt-1 text-xs leading-relaxed text-slate-500">{{ __('Browse available tasks and start earning.') }}</p>
                    </a>

                    <a href="{{ route('user.task.history') }}"
                       class="group rounded-2xl border border-slate-200/80 bg-white p-5 shadow-sm transition-all duration-200 hover:-translate-y-0.5 hover:border-[#142235]/15 hover:shadow-md">
                        <span class="flex items-center justify-center w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.7" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.666 3.888A2.25 2.25 0 0 0 13.5 2.25h-3c-1.03 0-1.9.693-2.166 1.638m7.332 0c.055.194.084.4.084.612v0a.75.75 0 0 1-.75.75H9a.75.75 0 0 1-.75-.75v0c0-.212.03-.418.084-.612m7.332 0c.646.049 1.288.11 1.927.184 1.1.128 1.907 1.077 1.907 2.185V19.5a2.25 2.25 0 0 1-2.25 2.25H6.75A2.25 2.25 0 0 1 4.5 19.5V6.257c0-1.108.806-2.057 1.907-2.185a48.208 48.208 0 0 1 1.927-.184"/>
                            </svg>
                        </span>
                        <p class="mt-3 text-sm font-bold text-[#142235]">{{ __('Submit Proof') }}</p>
                        <p class="mt-1 text-xs leading-relaxed text-slate-500">{{ __('View your tasks and submit proof of completion.') }}</p>
                    </a>

                    <a href="{{ route('user.withdraw.view') }}"
                       class="group rounded-2xl border border-slate-200/80 bg-white p-5 shadow-sm transition-all duration-200 hover:-translate-y-0.5 hover:border-[#142235]/15 hover:shadow-md">
                        <span class="flex items-center justify-center w-10 h-10 rounded-xl bg-amber-50 text-amber-600">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.7" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0 1 15.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 0 1 3 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 0 0-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 0 1-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 0 0 3 15h-.75M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm3 0h.008v.008H18V10.5Zm-12 0h.008v.008H6V10.5Z"/>
                            </svg>
                        </span>
                        <p class="mt-3 text-sm font-bold text-[#142235]">{{ __('Withdraw Earnings') }}</p>
                        <p class="mt-1 text-xs leading-relaxed text-slate-500">{{ __('Cash out your earnings securely.') }}</p>
                    </a>
                </div>
            </div>

            {{-- right column --}}
            <div class="space-y-6">

                {{-- earnings summary --}}
                <div class="rounded-2xl border border-slate-200/80 bg-white p-5 shadow-sm">
                    <div class="flex items-center justify-between">
                        <h3 class="text-[13px] font-semibold uppercase tracking-wide text-slate-500">{{ __('Earnings Summary') }}</h3>
                        <a href="{{ route('user.transactions') }}" class="text-xs font-semibold text-[#142235] hover:underline">{{ __('View') }} &rarr;</a>
                    </div>

                    <div class="mt-5 space-y-4">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2.5">
                                <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-slate-100 text-slate-500">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.7" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5"/>
                                    </svg>
                                </span>
                                <span class="text-sm text-slate-600">{{ __('This Week') }}</span>
                            </div>
                            <span class="text-sm font-semibold text-[#142235]">
                                {{ $currencySymbol }}{{ number_format((float) $dataCount['profit_last_7_days'], 2) }}
                            </span>
                        </div>

                        <div class="h-px bg-slate-100"></div>

                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2.5">
                                <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-emerald-50 text-emerald-600">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.7" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818.879.659 1.171-1.09a3 3 0 1 1 5.119 0l1.171 1.09.879-.659M12 6a2.25 2.25 0 1 0 0 4.5 2.25 2.25 0 0 0 0-4.5Z"/>
                                    </svg>
                                </span>
                                <span class="text-sm text-slate-600">{{ __('Total Earned') }}</span>
                            </div>
                            <span class="text-sm font-semibold text-emerald-600">
                                {{ $currencySymbol }}{{ number_format((float) $dataCount['total_profit'], 2) }}
                            </span>
                        </div>

                        <div class="h-px bg-slate-100"></div>

                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2.5">
                                <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-slate-100 text-slate-500">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.7" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0 1 15.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 0 1 3 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 0 0-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 0 1-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 0 0 3 15h-.75M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm3 0h.008v.008H18V10.5Zm-12 0h.008v.008H6V10.5Z"/>
                                    </svg>
                                </span>
                                <span class="text-sm text-slate-600">{{ __('Withdrawn') }}</span>
                            </div>
                            <span class="text-sm font-semibold text-[#142235]">
                                {{ $currencySymbol }}{{ number_format((float) $dataCount['total_withdraw'], 2) }}
                            </span>
                        </div>

                        <div class="h-px bg-slate-100"></div>

                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2.5">
                                <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-[#142235]/8 text-[#142235]">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.7" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 0 0 3.741-.479 3 3 0 0 0-4.682-2.72m.94 3.198.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0 1 12 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 0 1 6 18.719m12 0a5.971 5.971 0 0 0-.941-3.197m0 0A5.995 5.995 0 0 0 12 12.75a5.995 5.995 0 0 0-5.058 2.772m0 0a3 3 0 0 0-4.681 2.72 8.986 8.986 0 0 0 3.74.477m.94-3.197a5.971 5.971 0 0 0-.94 3.197M15 6.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm6 3a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Zm-13.5 0a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Z"/>
                                    </svg>
                                </span>
                                <span class="text-sm text-slate-600">{{ __('Referral Earnings') }}</span>
                            </div>
                            <span class="text-sm font-semibold text-[#142235]">
                                {{ $currencySymbol }}{{ number_format((float) $dataCount['total_referral_profit'], 2) }}
                            </span>
                        </div>
                    </div>
                </div>

                {{-- recent activity --}}
                <div class="rounded-2xl border border-slate-200/80 bg-white p-5 shadow-sm">
                    <h3 class="text-[13px] font-semibold uppercase tracking-wide text-slate-500">{{ __('Activity & Notices') }}</h3>
                    <div class="mt-4 space-y-3">
                        @if($dataCount['pending_task'] > 0)
                            <div class="flex items-start gap-3 rounded-xl bg-amber-50/60 p-3">
                                <span class="mt-0.5 flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-amber-100 text-amber-600">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.7" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                                    </svg>
                                </span>
                                <div>
                                    <p class="text-sm font-semibold text-[#142235]">{{ __(':count task(s) under review', ['count' => $dataCount['pending_task']]) }}</p>
                                    <p class="text-xs text-slate-500">{{ __('Awaiting admin approval') }}</p>
                                </div>
                            </div>
                        @endif

                        @if($dataCount['completed_task'] > 0)
                            <div class="flex items-start gap-3 rounded-xl bg-emerald-50/60 p-3">
                                <span class="mt-0.5 flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-emerald-100 text-emerald-600">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.7" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                                    </svg>
                                </span>
                                <div>
                                    <p class="text-sm font-semibold text-[#142235]">{{ __('Task rewards earned') }}</p>
                                    <p class="text-xs text-slate-500">{{ __('Total: :count completed', ['count' => $dataCount['completed_task']]) }}</p>
                                </div>
                            </div>
                        @endif

                        <div class="flex items-start gap-3 rounded-xl bg-[#142235]/[0.03] p-3">
                            <span class="mt-0.5 flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-[#142235]/8 text-[#142235]">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.7" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z"/>
                                </svg>
                            </span>
                            <div>
                                <p class="text-sm font-semibold text-[#142235]">{{ __('Account secured') }}</p>
                                <p class="text-xs text-slate-500">{{ __('Bank-grade protection active') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
