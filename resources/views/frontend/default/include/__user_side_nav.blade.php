@php
    $routeName = Route::currentRouteName();

    $active = function (string $pattern) use ($routeName) {
        if (str_ends_with($pattern, '*')) {
            return str_starts_with($routeName, rtrim($pattern, '*'))
                ? 'is-active'
                : '';
        }

        return $routeName === $pattern ? 'is-active' : '';
    };

    $menus = [
        [
            'label' => __('Client Portal'),
            'items' => [
                [
                    'route' => 'user.dashboard',
                    'label' => __('Overview'),
                    'icon'  => 'grid',
                ],
                [
                    'route' => 'user.task.index',
                    'label' => __('Tasks'),
                    'icon'  => 'check',
                ],
                [
                    'route' => 'user.task.history',
                    'label' => __('My Tasks'),
                    'icon'  => 'copy',
                ],
                [
                    'route' => 'user.withdraw.view',
                    'label' => __('Withdraw'),
                    'icon'  => 'bank',
                ],
                [
                    'route' => 'user.referral',
                    'label' => __('Referrals'),
                    'icon'  => 'users',
                ],
                [
                    'route' => 'user.transactions',
                    'label' => __('Transactions'),
                    'icon'  => 'list',
                ],
                [
                    'route' => 'user.ticket.index',
                    'label' => __('Support'),
                    'icon'  => 'help',
                ],
            ],
        ],
    ];

    $icons = [
        'grid' => 'M3.75 6A2.25 2.25 0 0 1 6 3.75h2.25A2.25 2.25 0 0 1 10.5 6v2.25a2.25 2.25 0 0 1-2.25 2.25H6a2.25 2.25 0 0 1-2.25-2.25V6ZM3.75 15.75A2.25 2.25 0 0 1 6 13.5h2.25a2.25 2.25 0 0 1 2.25 2.25V18a2.25 2.25 0 0 1-2.25 2.25H6A2.25 2.25 0 0 1 3.75 18v-2.25ZM13.5 6a2.25 2.25 0 0 1 2.25-2.25H18A2.25 2.25 0 0 1 20.25 6v2.25A2.25 2.25 0 0 1 18 10.5h-2.25a2.25 2.25 0 0 1-2.25-2.25V6ZM13.5 15.75a2.25 2.25 0 0 1 2.25-2.25H18a2.25 2.25 0 0 1 2.25 2.25V18A2.25 2.25 0 0 1 18 20.25h-2.25A2.25 2.25 0 0 1 13.5 18v-2.25Z',

        'check' => 'M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z',

        'copy' => 'M15.666 3.888A2.25 2.25 0 0 0 13.5 2.25h-3c-1.03 0-1.9.693-2.166 1.638m7.332 0c.055.194.084.4.084.612v0a.75.75 0 0 1-.75.75H9a.75.75 0 0 1-.75-.75v0c0-.212.03-.418.084-.612m7.332 0c.646.049 1.288.11 1.927.184 1.1.128 1.907 1.077 1.907 2.185V19.5a2.25 2.25 0 0 1-2.25 2.25H6.75A2.25 2.25 0 0 1 4.5 19.5V6.257c0-1.108.806-2.057 1.907-2.185a48.208 48.208 0 0 1 1.927-.184',

        'bank' => 'M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21M6.75 6.75h.75m-.75 3h.75m-.75 3h.75m3-6h.75m-.75 3h.75m-.75 3h.75M6.75 21v-3.375c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21M3 3h12m-.75 4.5H21m-3.75 3.75h.008v.008H18v-.008Zm0 3h.008v.008H18v-.008Zm0 3h.008v.008H18v-.008Z',

        'users' => 'M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z',

        'list' => 'M8.25 6.75h12M8.25 12h12m-12 5.25h12M3.75 6.75h.007v.008H3.75V6.75Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0ZM3.75 12h.007v.008H3.75V12Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm-.375 5.25h.007v.008H3.75v-.008Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z',

        'help' => 'M9.879 7.519c1.171-1.025 3.071-1.025 4.242 0 1.172 1.025 1.172 2.687 0 3.712-.203.179-.43.326-.67.442-.745.361-1.45.999-1.45 1.827v.75M21 12a9 9 0 1 1-18 0Zm-9 5.25h.008v.008H12v-.008Z',
    ];
@endphp

{{-- ================================================================
     DESKTOP CLIENT PORTAL SIDEBAR
     ================================================================ --}}
<aside class="hidden w-[256px] shrink-0 bg-[#071a2b] text-white md:block">

    <div class="sticky top-[72px] flex h-[calc(100vh-72px)] flex-col">

        {{-- Navigation --}}
        <div class="flex-1 px-4 overflow-y-auto py-7">

            @foreach($menus as $group)

                <div>
                    <p class="mb-3 px-3 text-[10px] font-semibold uppercase tracking-[0.18em] text-slate-400">
                        {{ $group['label'] }}
                    </p>

                    <ul class="space-y-1">

                        @foreach($group['items'] as $item)

                            @php
                                $isActive = $active($item['route']) !== '';
                            @endphp

                            <li>
                                <a href="{{ route($item['route']) }}"
                                   class="group flex items-center gap-3 border-l-2 px-3 py-3 text-sm transition-all duration-200
                                   {{ $isActive
                                        ? 'border-[#c29448] bg-white/10 font-semibold text-white'
                                        : 'border-transparent text-slate-400 hover:bg-white/5 hover:text-white' }}">

                                    <svg class="h-[17px] w-[17px] shrink-0
                                        {{ $isActive
                                            ? 'text-[#d0a45d]'
                                            : 'text-slate-500 group-hover:text-slate-300' }}"
                                         fill="none"
                                         viewBox="0 0 24 24"
                                         stroke-width="1.6"
                                         stroke="currentColor">

                                        <path stroke-linecap="round"
                                              stroke-linejoin="round"
                                              d="{{ $icons[$item['icon']] ?? $icons['grid'] }}"/>

                                    </svg>

                                    <span class="truncate">
                                        {{ $item['label'] }}
                                    </span>

                                </a>
                            </li>

                        @endforeach

                    </ul>
                </div>

            @endforeach

        </div>

        {{-- Bottom area --}}
        <div class="p-4 border-t border-white/10">

            {{-- Profile --}}
            <a href="{{ route('user.setting.show') }}"
               class="flex items-center gap-3 px-3 py-3 text-sm transition-colors border-l-2 border-transparent group text-slate-400 hover:bg-white/5 hover:text-white">

                <svg class="h-[17px] w-[17px] shrink-0 text-slate-500 group-hover:text-slate-300"
                     fill="none"
                     viewBox="0 0 24 24"
                     stroke-width="1.6"
                     stroke="currentColor">

                    <path stroke-linecap="round"
                          stroke-linejoin="round"
                          d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.325.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 0 1 1.37.49l1.296 2.247a1.125 1.125 0 0 1-.26 1.431l-1.003.827c-.293.241-.438.613-.43.992a7.723 7.723 0 0 1 0 .255c-.008.378.137.75.43.991l1.004.827c.424.35.534.955.26 1.43l-1.298 2.247a1.125 1.125 0 0 1-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.47 6.47 0 0 1-.22.128c-.331.183-.581.495-.644.869l-.213 1.281c-.09.543-.56.94-1.11.94h-2.594c-.55 0-1.019-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 0 1-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 0 1-1.369-.49l-1.297-2.247a1.125 1.125 0 0 1 .26-1.431l1.004-.827c.292-.24.437-.613.43-.991a6.932 6.932 0 0 1 0-.255c.007-.38-.138-.751-.43-.992l-1.004-.827a1.125 1.125 0 0 1-.26-1.43l1.297-2.247a1.125 1.125 0 0 1 1.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.086.22-.128.332-.183.582-.495.644-.869l.214-1.28Z"/>

                    <path stroke-linecap="round"
                          stroke-linejoin="round"
                          d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/>
                </svg>

                <span>{{ __('Profile & Settings') }}</span>

            </a>

            {{-- Logout --}}
            <form method="POST" action="{{ route('logout') }}" class="mt-1">
                @csrf

                <button type="submit"
                        class="flex items-center w-full gap-3 px-3 py-3 text-sm transition-colors border-l-2 border-transparent group text-slate-400 hover:bg-rose-500/10 hover:text-rose-300">

                    <svg class="h-[17px] w-[17px] shrink-0 text-slate-500 group-hover:text-rose-300"
                         fill="none"
                         viewBox="0 0 24 24"
                         stroke-width="1.6"
                         stroke="currentColor">

                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6a2.25 2.25 0 0 0-2.25 2.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15m-3-3H21m0 0-3-3m3 3-3 3"/>
                    </svg>

                    <span>{{ __('Logout') }}</span>

                </button>
            </form>

        </div>

    </div>
</aside>
