@php
    // Deliberately task-first: the two counts that matter most on a task
    // platform were in $dataCount but never rendered.
    $stats = [
        ['key' => 'completed_task',      'label' => __('Tasks Completed'), 'money' => false, 'accent' => 'earn',
         'icon' => 'M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z'],
        ['key' => 'pending_task',        'label' => __('Awaiting Review'), 'money' => false, 'accent' => 'warn',
         'icon' => 'M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z'],
        ['key' => 'total_task_earning',  'label' => __('Task Earnings'),   'money' => true,  'accent' => 'earn',
         'icon' => 'M2.25 18 9 11.25l4.306 4.306a11.95 11.95 0 0 1 5.814-5.518l2.74-1.22m0 0-5.94-2.281m5.94 2.28-2.28 5.941'],
        ['key' => 'total_withdraw',      'label' => __('Total Withdrawn'), 'money' => true,  'accent' => 'brand',
         'icon' => 'M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 0 0 2.25-2.25V6.75A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25v10.5A2.25 2.25 0 0 0 4.5 19.5Z'],
        ['key' => 'total_deposit',       'label' => __('Total Deposit'),   'money' => true,  'accent' => 'brand',
         'icon' => 'M12 4.5v15m7.5-7.5h-15'],
        ['key' => 'total_referral',      'label' => __('Referrals'),       'money' => false, 'accent' => 'brand',
         'icon' => 'M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z'],
        ['key' => 'total_referral_profit','label' => __('Referral Bonus'), 'money' => true,  'accent' => 'earn',
         'icon' => 'M21 11.25v8.25a1.5 1.5 0 0 1-1.5 1.5H5.25a1.5 1.5 0 0 1-1.5-1.5v-8.25M12 4.875A2.625 2.625 0 1 0 9.375 7.5H12m0-2.625V7.5m0-2.625A2.625 2.625 0 1 1 14.625 7.5H12m0 0V21m-8.625-9.75h18c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125h-18c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125Z'],
        ['key' => 'rank_achieved',       'label' => __('Rank Achieved'),   'money' => false, 'accent' => 'brand',
         'icon' => 'M11.48 3.499a.562.562 0 0 1 1.04 0l2.125 5.111a.563.563 0 0 0 .475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 0 0-.182.557l1.285 5.385a.562.562 0 0 1-.84.61l-4.725-2.885a.562.562 0 0 0-.586 0L6.982 20.54a.562.562 0 0 1-.84-.61l1.285-5.386a.562.562 0 0 0-.182-.557l-4.204-3.602a.562.562 0 0 1 .321-.988l5.518-.442a.563.563 0 0 0 .475-.345L11.48 3.5Z'],
    ];

    $accentClass = [
        'earn'  => 'bg-earn-500/12 text-earn-600 dark:text-earn-400',
        'brand' => 'bg-brand-500/12 text-brand-600 dark:text-brand-300',
        'warn'  => 'bg-amber-500/12 text-amber-600 dark:text-amber-400',
    ];
@endphp

<div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
    @foreach($stats as $stat)
        @php $value = $dataCount[$stat['key']] ?? 0; @endphp
        <div class="group relative overflow-hidden rounded-2xl border border-[rgb(var(--line)/0.09)] bg-[rgb(var(--surface-raised))] p-5 shadow-soft transition-all duration-500 ease-spring hover:-translate-y-1 hover:border-brand-500/25 hover:shadow-card">

            <span class="absolute -right-6 -top-6 h-20 w-20 rounded-full opacity-[0.06] transition-opacity duration-500 group-hover:opacity-[0.12]
                         {{ $stat['accent'] === 'earn' ? 'bg-earn-500' : ($stat['accent'] === 'warn' ? 'bg-amber-500' : 'bg-brand-500') }}"></span>

            <div class="relative flex items-start justify-between gap-3">
                <div class="min-w-0">
                    <p class="text-[0.68rem] font-semibold uppercase tracking-[0.12em] text-[rgb(var(--text-muted))]">
                        {{ $stat['label'] }}
                    </p>
                    <p class="mt-2 font-display text-2xl font-bold tracking-tight text-[rgb(var(--text-strong))]">
                        @if($stat['money'])
                            <span class="text-base font-semibold text-[rgb(var(--text-muted))]">{{ $currencySymbol }}</span>{{ number_format((float) $value, 2) }}
                        @else
                            {{ number_format((float) $value) }}
                        @endif
                    </p>
                </div>

                <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl {{ $accentClass[$stat['accent']] }}">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.7" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="{{ $stat['icon'] }}"/>
                    </svg>
                </span>
            </div>
        </div>
    @endforeach
</div>
