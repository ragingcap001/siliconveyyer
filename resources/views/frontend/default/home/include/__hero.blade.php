@php
    $taskCount = \App\Models\Task::open()->count();
@endphp

<section class="relative overflow-hidden pb-24 pt-10 lg:pb-32 lg:pt-16">

    {{-- backdrop: blueprint grid, glows, dot field --}}
    <div class="pointer-events-none absolute inset-0 grid-canvas"></div>
    <div class="glow-blob -top-48 left-1/2 h-[34rem] w-[34rem] -translate-x-1/2 opacity-90"></div>
    <div class="glow-blob right-[-8rem] top-40 h-80 w-80 opacity-60"
         style="background: radial-gradient(circle, rgb(20 184 166 / .26) 0%, transparent 68%)"></div>

    <div class="shell relative">
        <div class="grid items-center gap-16 lg:grid-cols-12 lg:gap-10">

            {{-- ============ copy ============ --}}
            <div class="lg:col-span-6" data-reveal>
                <span class="eyebrow">
                    <span class="relative flex h-1.5 w-1.5">
                        <span class="absolute inline-flex h-full w-full animate-pulse-ring rounded-full bg-earn-500"></span>
                        <span class="relative inline-flex h-1.5 w-1.5 rounded-full bg-earn-500"></span>
                    </span>
                    @if($taskCount)
                        {{ __(':count tasks open right now', ['count' => $taskCount]) }}
                    @else
                        {{ __('Task marketplace') }}
                    @endif
                </span>

                <h1 class="mt-6 text-[2.6rem] font-bold leading-[1.06] tracking-[-0.03em] sm:text-5xl lg:text-[3.65rem]">
                    <span class="text-gradient">{{ $data['hero_title'] ?? '' }}</span>
                </h1>

                <p class="mt-6 max-w-xl text-lg leading-relaxed text-[rgb(var(--text-muted))]">
                    {{ $data['hero_content'] ?? '' }}
                </p>

                <div class="mt-9 flex flex-wrap items-center gap-3">
                    <a href="{{ $data['hero_button1_url'] ?? route('register') }}"
                       target="{{ $data['hero_button1_target'] ?? '_self' }}"
                       class="btn-primary btn-lg group">
                        <svg class="h-4 w-4 opacity-80" fill="none" viewBox="0 0 24 24" stroke-width="1.9" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.59 14.37a6 6 0 0 1-5.84 7.38v-4.8m5.84-2.58a14.98 14.98 0 0 0 6.16-12.12A14.98 14.98 0 0 0 9.631 8.41m5.96 5.96a14.926 14.926 0 0 1-5.841 2.58m-.119-8.54a6 6 0 0 0-7.381 5.84h4.8m2.581-5.84a14.927 14.927 0 0 0-2.58 5.84m2.699 2.7c-.103.021-.207.041-.311.06a15.09 15.09 0 0 1-2.448-2.448 14.9 14.9 0 0 1 .06-.312m-2.24 2.39a4.499 4.499 0 0 0-1.757 4.306 4.499 4.499 0 0 0 4.306-1.758M16.5 9a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0Z"/>
                        </svg>
                        {{ $data['hero_button1_level'] ?? __('Start Earning') }}
                        <svg class="h-4 w-4 transition-transform duration-300 group-hover:translate-x-1" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3"/>
                        </svg>
                    </a>

                    <a href="{{ $data['hero_button2_url'] ?? route('login') }}"
                       target="{{ $data['hero_button2_target'] ?? '_self' }}"
                       class="btn-outline btn-lg">
                        {{ $data['hero_button2_lavel'] ?? __('Browse Tasks') }}
                    </a>
                </div>

                {{-- trust strip --}}
                <dl class="mt-12 grid max-w-lg grid-cols-3 gap-6 border-t border-[rgb(var(--line)/0.08)] pt-8">
                    <div>
                        <dt class="stat-label">{{ __('Open tasks') }}</dt>
                        <dd class="stat-value">{{ $taskCount }}</dd>
                    </div>
                    <div>
                        <dt class="stat-label">{{ __('Proof types') }}</dt>
                        <dd class="stat-value">{{ __('Link') }} · {{ __('Text') }} · {{ __('Photo') }}</dd>
                    </div>
                    <div>
                        <dt class="stat-label">{{ __('Reward') }}</dt>
                        <dd class="stat-value">{{ __('Per task') }}</dd>
                    </div>
                </dl>
            </div>

            {{-- ============ art ============ --}}
            <div class="lg:col-span-6" data-reveal data-reveal-delay="120">
                <div class="relative mx-auto max-w-xl">

                    {{-- soft card frame --}}
                    <div class="relative rounded-[2rem] border border-[rgb(var(--line)/0.09)] bg-[rgb(var(--surface-raised))] p-3 shadow-lift">
                        <div class="overflow-hidden rounded-3xl bg-[rgb(var(--surface-muted))] p-5">

                            {{-- fake window bar --}}
                            <div class="mb-5 flex items-center justify-between">
                                <div class="flex gap-1.5">
                                    <span class="h-2.5 w-2.5 rounded-full bg-rose-400/70"></span>
                                    <span class="h-2.5 w-2.5 rounded-full bg-amber-400/70"></span>
                                    <span class="h-2.5 w-2.5 rounded-full bg-emerald-400/70"></span>
                                </div>
                                <span class="text-[0.65rem] font-semibold uppercase tracking-[0.18em] text-[rgb(var(--text-muted))]">
                                    {{ __('Available tasks') }}
                                </span>
                            </div>

                            {{-- task rows --}}
                            <div class="space-y-3">
                                @foreach([
                                    ['Follow & repost on X', '0.50', 'link', __('Link proof'), 'brand'],
                                    ['Write a 300 word review', '3.00', 'text', __('Text proof'), 'earn'],
                                    ['Upload dashboard screenshot', '0.25', 'screenshot', __('Screenshot'), 'violet'],
                                ] as $i => $demo)
                                    <div class="flex items-center gap-3.5 rounded-2xl border border-[rgb(var(--line)/0.08)] bg-[rgb(var(--surface-raised))] p-3.5 transition-transform duration-500 ease-spring hover:-translate-y-0.5"
                                         style="animation: float 7s ease-in-out infinite; animation-delay: {{ $i * 0.9 }}s">
                                        <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl
                                            {{ $demo[4] === 'earn' ? 'bg-earn-500/12 text-earn-600 dark:text-earn-300' : 'bg-brand-500/12 text-brand-600 dark:text-brand-300' }}">
                                            @if($demo[2] === 'link')
                                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.19 8.688a4.5 4.5 0 0 1 1.242 7.244l-4.5 4.5a4.5 4.5 0 0 1-6.364-6.364l1.757-1.757m13.35-.622 1.757-1.757a4.5 4.5 0 0 0-6.364-6.364l-4.5 4.5a4.5 4.5 0 0 0 1.242 7.244"/>
                                                </svg>
                                            @elseif($demo[2] === 'text')
                                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Z"/>
                                                </svg>
                                            @else
                                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.827 6.175A2.31 2.31 0 0 1 5.186 7.23c-.38.054-.757.112-1.134.175C2.999 7.58 2.25 8.507 2.25 9.574V18a2.25 2.25 0 0 0 2.25 2.25h15A2.25 2.25 0 0 0 21.75 18V9.574c0-1.067-.75-1.994-1.802-2.169a47.865 47.865 0 0 0-1.134-.175 2.31 2.31 0 0 1-1.64-1.055l-.822-1.316a2.192 2.192 0 0 0-1.736-1.039 48.774 48.774 0 0 0-5.232 0 2.192 2.192 0 0 0-1.736 1.039l-.821 1.316Z"/>
                                                </svg>
                                            @endif
                                        </span>

                                        <div class="min-w-0 flex-1">
                                            <p class="truncate text-sm font-semibold text-[rgb(var(--text-strong))]">{{ $demo[0] }}</p>
                                            <p class="mt-0.5 text-xs text-[rgb(var(--text-muted))]">{{ $demo[3] }}</p>
                                        </div>

                                        <span class="shrink-0 rounded-lg bg-earn-500/12 px-2.5 py-1.5 text-sm font-bold text-earn-700 dark:text-earn-300">
                                            ${{ $demo[1] }}
                                        </span>
                                    </div>
                                @endforeach
                            </div>

                            {{-- payout footer of the card --}}
                            <div class="mt-5 flex items-center justify-between rounded-2xl border border-dashed border-[rgb(var(--line)/0.14)] p-4">
                                <div class="flex items-center gap-2.5">
                                    <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-brand-500 text-white">
                                        <svg class="h-4.5 w-4.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5"/>
                                        </svg>
                                    </span>
                                    <div>
                                        <p class="text-xs font-semibold text-[rgb(var(--text-strong))]">{{ __('Accepted') }}</p>
                                        <p class="text-[0.7rem] text-[rgb(var(--text-muted))]">{{ __('Reward added to your balance') }}</p>
                                    </div>
                                </div>
                                <span class="badge-earn">+ $3.75</span>
                            </div>
                        </div>
                    </div>

                    {{-- floating chips --}}
                    <div class="absolute -left-6 top-1/4 hidden animate-float rounded-2xl border border-[rgb(var(--line)/0.09)] bg-[rgb(var(--surface-raised))] px-4 py-3 shadow-lift sm:block">
                        <p class="text-[0.65rem] font-semibold uppercase tracking-wider text-[rgb(var(--text-muted))]">{{ __('Level') }}</p>
                        <p class="font-display text-lg font-bold text-[rgb(var(--text-strong))]">{{ __('Task Pro') }}</p>
                    </div>

                    <div class="absolute -right-5 bottom-16 hidden animate-float rounded-2xl border border-[rgb(var(--line)/0.09)] bg-[rgb(var(--surface-raised))] px-4 py-3 shadow-lift sm:block"
                         style="animation-delay: 2.5s">
                        <p class="text-[0.65rem] font-semibold uppercase tracking-wider text-[rgb(var(--text-muted))]">{{ __('Slots left') }}</p>
                        <p class="font-display text-lg font-bold text-earn-600 dark:text-earn-400">{{ __('Unlimited') }}</p>
                    </div>

                    {{-- dotted accent --}}
                    <div class="dot-field -z-10 absolute -bottom-8 -left-10 h-32 w-32 rounded-3xl opacity-60"></div>
                </div>
            </div>
        </div>
    </div>
</section>
