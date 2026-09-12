@php
    $routeName = Route::currentRouteName();

    // isActive() supports trailing wildcards like user.deposit*
    $active = function (string $pattern) use ($routeName) {
        if (str_ends_with($pattern, '*')) {
            return str_starts_with($routeName, rtrim($pattern, '*')) ? 'is-active' : '';
        }
        return $routeName === $pattern ? 'is-active' : '';
    };

    $menus = [
        [
            'label' => __('Earn'),
            'items' => [
                ['route' => 'user.task.index',   'label' => __('Available Tasks'), 'icon' => 'check'],
                ['route' => 'user.task.history', 'label' => __('My Tasks'),        'icon' => 'copy'],
                ['route' => 'user.referral',     'label' => __('Referral'),        'icon' => 'users'],
            ],
        ],
        [
            'label' => __('Money'),
            'items' => [
                ['route' => 'user.deposit.amount', 'label' => __('Add Money'),     'icon' => 'plus'],
                ['route' => 'user.deposit.log',    'label' => __('Add Money Log'), 'icon' => 'folder'],
                ['route' => 'user.withdraw.view',  'label' => __('Withdraw'),      'icon' => 'bank'],
                ['route' => 'user.withdraw.log',   'label' => __('Withdraw Log'),  'icon' => 'clock'],
                ['route' => 'user.transactions',   'label' => __('Transactions'),  'icon' => 'list'],
            ],
        ],
        [
            'label' => __('Account'),
            'items' => [
                ['route' => 'user.dashboard',      'label' => __('Overview'),        'icon' => 'grid'],
                ['route' => 'user.setting.show',  'label' => __('Profile'),         'icon' => 'user'],
                ['route' => 'user.kyc',            'label' => __('KYC Verification'),'icon' => 'shield'],
                ['route' => 'user.change.password', 'label' => __('Password'),       'icon' => 'lock'],
                ['route' => 'user.ticket.index',   'label' => __('Support Tickets'), 'icon' => 'help'],
            ],
        ],
    ];

    $icons = [
        'check'  => 'M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z',
        'copy'   => 'M15.666 3.888A2.25 2.25 0 0 0 13.5 2.25h-3c-1.03 0-1.9.693-2.166 1.638m7.332 0c.055.194.084.4.084.612v0a.75.75 0 0 1-.75.75H9a.75.75 0 0 1-.75-.75v0c0-.212.03-.418.084-.612m7.332 0c.646.049 1.288.11 1.927.184 1.1.128 1.907 1.077 1.907 2.185V19.5a2.25 2.25 0 0 1-2.25 2.25H6.75A2.25 2.25 0 0 1 4.5 19.5V6.257c0-1.108.806-2.057 1.907-2.185a48.208 48.208 0 0 1 1.927-.184',
        'users'  => 'M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z',
        'plus'   => 'M12 4.5v15m7.5-7.5h-15',
        'folder' => 'M2.25 12.75V12A2.25 2.25 0 0 1 4.5 9.75h15A2.25 2.25 0 0 1 21.75 12v.75m-8.69-6.44-2.12-2.12a1.5 1.5 0 0 0-1.061-.44H4.5A2.25 2.25 0 0 0 2.25 6v12a2.25 2.25 0 0 0 2.25 2.25h15A2.25 2.25 0 0 0 21.75 18V9a2.25 2.25 0 0 0-2.25-2.25h-5.379a1.5 1.5 0 0 1-1.06-.44Z',
        'bank'   => 'M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21M6.75 6.75h.75m-.75 3h.75m-.75 3h.75m3-6h.75m-.75 3h.75m-.75 3h.75M6.75 21v-3.375c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21M3 3h12m-.75 4.5H21m-3.75 3.75h.008v.008h-.008v-.008Zm0 3h.008v.008h-.008v-.008Zm0 3h.008v.008h-.008v-.008Z',
        'clock'  => 'M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z',
        'list'   => 'M8.25 6.75h12M8.25 12h12m-12 5.25h12M3.75 6.75h.007v.008H3.75V6.75Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0ZM3.75 12h.007v.008H3.75V12Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm-.375 5.25h.007v.008H3.75v-.008Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z',
        'grid'   => 'M3.75 6A2.25 2.25 0 0 1 6 3.75h2.25A2.25 2.25 0 0 1 10.5 6v2.25a2.25 2.25 0 0 1-2.25 2.25H6a2.25 2.25 0 0 1-2.25-2.25V6ZM3.75 15.75A2.25 2.25 0 0 1 6 13.5h2.25a2.25 2.25 0 0 1 2.25 2.25V18a2.25 2.25 0 0 1-2.25 2.25H6A2.25 2.25 0 0 1 3.75 18v-2.25ZM13.5 6a2.25 2.25 0 0 1 2.25-2.25H18A2.25 2.25 0 0 1 20.25 6v2.25A2.25 2.25 0 0 1 18 10.5h-2.25a2.25 2.25 0 0 1-2.25-2.25V6ZM13.5 15.75a2.25 2.25 0 0 1 2.25-2.25H18a2.25 2.25 0 0 1 2.25 2.25V18A2.25 2.25 0 0 1 18 20.25h-2.25A2.25 2.25 0 0 1 13.5 18v-2.25Z',
        'user'   => 'M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z',
        'shield' => 'M9 12.75 11.25 15 15 9.75m-3-7.036A11.959 11.959 0 0 1 3.598 6 11.99 11.99 0 0 0 3 9.749c0 5.592 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285Z',
        'lock'   => 'M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z',
        'help'   => 'M9.879 7.519c1.171-1.025 3.071-1.025 4.242 0 1.172 1.025 1.172 2.687 0 3.712-.203.179-.43.326-.67.442-.745.361-1.45.999-1.45 1.827v.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 5.25h.008v.008H12v-.008Z',
    ];
@endphp

{{-- ============ desktop sidebar ============ --}}
<aside class="hidden w-[280px] shrink-0 lg:block">
    <div class="sticky top-28 space-y-4">

        {{-- wallet card --}}
        <div class="relative overflow-hidden rounded-3xl border border-white/10 bg-brand-950 p-5">
            <div class="pointer-events-none absolute -right-10 -top-10 h-36 w-36 rounded-full blur-2xl"
                 style="background: radial-gradient(circle, rgba(20,184,166,.45) 0%, transparent 70%)"></div>

            <p class="text-[0.65rem] font-semibold uppercase tracking-[0.14em] text-white/50">{{ __('Main wallet') }}</p>
            <p class="mt-1.5 font-display text-3xl font-bold text-white">
                {{ setting('currency_symbol','global') }}{{ number_format((float) $user->balance, 2) }}
            </p>

            <div class="mt-4 border-t border-white/10 pt-4">
                <div class="flex items-center justify-between">
                    <span class="text-xs text-white/60">{{ __('Profit wallet') }}</span>
                    <span class="text-sm font-semibold text-white/90">
                        {{ setting('currency_symbol','global') }}{{ number_format((float) $user->profit_balance, 2) }}
                    </span>
                </div>
            </div>

            <div class="mt-4 grid grid-cols-2 gap-2">
                <a href="{{ route('user.deposit.amount') }}"
                   class="btn btn-sm bg-white/10 text-white hover:bg-white/20">{{ __('Deposit') }}</a>
                <a href="{{ route('user.withdraw.view') }}"
                   class="btn btn-sm bg-white text-brand-700 hover:bg-white/90">{{ __('Withdraw') }}</a>
            </div>
        </div>

        {{-- navigation --}}
        <nav class="rounded-3xl border border-[rgb(var(--line)/0.09)] bg-[rgb(var(--surface-raised))] p-3 shadow-soft">
            @foreach($menus as $group)
                <div class="{{ $loop->first ? '' : 'mt-4 border-t border-[rgb(var(--line)/0.07)] pt-4' }}">
                    <p class="px-3 pb-2 text-[0.65rem] font-semibold uppercase tracking-[0.14em] text-[rgb(var(--text-muted))]">
                        {{ $group['label'] }}
                    </p>
                    <ul class="space-y-0.5">
                        @foreach($group['items'] as $item)
                            <li>
                                <a href="{{ route($item['route']) }}"
                                   class="dash-link {{ $active($item['route']) }}">
                                    <svg class="h-[18px] w-[18px] shrink-0" fill="none" viewBox="0 0 24 24"
                                         stroke-width="1.7" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="{{ $icons[$item['icon']] ?? $icons['grid'] }}"/>
                                    </svg>
                                    <span class="truncate">{{ $item['label'] }}</span>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endforeach

            <div class="mt-4 border-t border-[rgb(var(--line)/0.07)] pt-3">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="dash-link w-full text-rose-600 hover:bg-rose-500/10 dark:text-rose-400">
                        <svg class="h-[18px] w-[18px] shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.7" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6a2.25 2.25 0 0 0-2.25 2.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15M12 9l-3 3m0 0 3 3m-3-3h12"/>
                        </svg>
                        <span>{{ __('Logout') }}</span>
                    </button>
                </form>
            </div>
        </nav>
    </div>
</aside>
