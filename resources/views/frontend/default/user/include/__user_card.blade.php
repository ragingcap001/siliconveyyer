@php
    $stats = [
        [
            'label'  => __('Wallet Balance'),
            'value'  => $currencySymbol . number_format((float) $user->balance, 2),
            'sub'    => __('Available to withdraw'),
            'accent' => 'gold',
            'badge'  => __('Available'),
            'icon'   => 'M2.25 18.75a60.07 60.07 0 0 1 15.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 0 1 3 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 0 0-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 0 1-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 0 0 3 15h-.75M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm3 0h.008v.008H18V10.5Zm-12 0h.008v.008H6V10.5Z',
        ],
        [
            'label'  => __('Under Review'),
            'value'  => (string) $dataCount['pending_task'],
            'sub'    => __('Awaiting approval'),
            'accent' => 'amber',
            'badge'  => __('Review'),
            'icon'   => 'M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z',
        ],
        [
            'label'  => __('Completed'),
            'value'  => (string) $dataCount['completed_task'],
            'sub'    => __('Tasks finished'),
            'accent' => 'green',
            'badge'  => __('Done'),
            'icon'   => 'M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z',
        ],
    ];

    $accent = [
        'gold' => [
            'icon'    => 'border-[#c29448]/20 bg-[#c29448]/8 text-[#b08430]',
            'badge'   => 'border-[#c29448]/20 bg-[#c29448]/8 text-[#9b6d2d]',
            'border'  => 'border-t-[#c29448]',
        ],
        'amber' => [
            'icon'    => 'border-amber-200 bg-amber-50 text-amber-600',
            'badge'   => 'border-amber-200 bg-amber-50 text-amber-600',
            'border'  => 'border-t-amber-500',
        ],
        'green' => [
            'icon'    => 'border-emerald-200 bg-emerald-50 text-emerald-600',
            'badge'   => 'border-emerald-200 bg-emerald-50 text-emerald-600',
            'border'  => 'border-t-emerald-500',
        ],
    ];
@endphp

<div class="grid gap-4 sm:grid-cols-3">

    @foreach($stats as $stat)

        <div class="rounded-2xl border border-slate-200/80 border-t-[3px] {{ $accent[$stat['accent']]['border'] }} bg-white p-5 shadow-sm transition-shadow duration-200 hover:shadow-md">

            <div class="flex items-start justify-between gap-4">

                <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl border {{ $accent[$stat['accent']]['icon'] }}">
                    <svg class="h-[18px] w-[18px]" fill="none" viewBox="0 0 24 24" stroke-width="1.6" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="{{ $stat['icon'] }}"/>
                    </svg>
                </span>

                <span class="rounded-full border px-2.5 py-0.5 text-[10px] font-semibold uppercase tracking-wide {{ $accent[$stat['accent']]['badge'] }}">
                    {{ $stat['badge'] }}
                </span>

            </div>

            <p class="mt-4 font-display text-[26px] font-bold leading-none tracking-tight text-[#142235]">
                {{ $stat['value'] }}
            </p>

            <p class="mt-2 text-sm font-medium text-slate-600">
                {{ $stat['label'] }}
            </p>

            <p class="mt-0.5 text-[11px] text-slate-400">
                {{ $stat['sub'] }}
            </p>

        </div>

    @endforeach

</div>
