@php
    $stats = [
        [
            'key'   => 'wallet_balance',
            'label' => __('Wallet Balance'),
            'value' => number_format((float) $user->balance, 2),
            'money' => true,
            'accent'=> 'brand',
            'icon'  => 'M21 11.25v8.25a1.5 1.5 0 0 1-1.5 1.5H5.25a1.5 1.5 0 0 1-1.5-1.5v-8.25M12 4.875A2.625 2.625 0 1 0 9.375 7.5H12m0-2.625V7.5m0-2.625A2.625 2.625 0 1 1 14.625 7.5H12m0 0V21m-8.625-9.75h18c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125h-18c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125Z',
        ],
        [
            'key'   => 'total_task_earning',
            'label' => __('Total Earnings'),
            'value' => number_format((float) $dataCount['total_task_earning'], 2),
            'money' => true,
            'accent'=> 'earn',
            'icon'  => 'M2.25 18 9 11.25l4.306 4.306a11.95 11.95 0 0 1 5.814-5.518l2.74-1.22m0 0-5.94-2.281m5.94 2.28-2.28 5.941',
        ],
        [
            'key'   => 'completed_task',
            'label' => __('Tasks Completed'),
            'value' => number_format((float) $dataCount['completed_task']),
            'money' => false,
            'accent'=> 'earn',
            'icon'  => 'M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z',
        ],
        [
            'key'   => 'pending_task',
            'label' => __('In Review'),
            'value' => number_format((float) $dataCount['pending_task']),
            'money' => false,
            'accent'=> 'warn',
            'icon'  => 'M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z',
        ],
    ];

    $accentClass = [
        'earn'  => 'bg-earn-500/12 text-earn-600 dark:text-earn-400',
        'brand' => 'bg-brand-500/12 text-brand-600 dark:text-brand-300',
        'warn'  => 'bg-amber-500/12 text-amber-600 dark:text-amber-400',
    ];
@endphp

<div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
    @foreach($stats as $stat)
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
                            <span class="text-base font-semibold text-[rgb(var(--text-muted))]">{{ $currencySymbol }}</span>{{ $stat['value'] }}
                        @else
                            {{ $stat['value'] }}
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
