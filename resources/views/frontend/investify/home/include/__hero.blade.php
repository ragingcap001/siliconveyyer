<section class="relative overflow-hidden pb-24 pt-10 lg:pb-32 lg:pt-16">

    <div class="pointer-events-none absolute inset-0 grid-canvas"></div>
    <div class="glow-blob -top-48 left-1/2 h-[34rem] w-[34rem] -translate-x-1/2 opacity-90"></div>
    <div class="glow-blob right-[-8rem] top-40 h-80 w-80 opacity-60"
         style="background: radial-gradient(circle, rgb(20 184 166 / .26) 0%, transparent 68%)"></div>

    <div class="shell relative">
        <div class="grid items-center gap-16 lg:grid-cols-12 lg:gap-10">

            {{-- Copy --}}
            <div class="lg:col-span-6" data-reveal>
                <h1 class="mt-6 text-[2.6rem] font-bold leading-[1.06] tracking-[-0.03em] sm:text-5xl lg:text-[3.65rem]">
                    <span class="text-gradient">{{ $data['hero_title'] }}</span>
                </h1>

                <p class="mt-6 max-w-xl text-lg leading-relaxed text-[rgb(var(--text-muted))]">
                    {{ $data['hero_content'] }}
                </p>

                <div class="mt-9 flex flex-wrap items-center gap-3">
                    <a class="btn-primary btn-lg group" href="{{ $data['hero_button1_url'] }}">
                        <svg class="h-4 w-4 opacity-80" fill="none" viewBox="0 0 24 24" stroke-width="1.9" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.59 14.37a6 6 0 0 1-5.84 7.38v-4.8m5.84-2.58a14.98 14.98 0 0 0 6.16-12.12A14.98 14.98 0 0 0 9.631 8.41m5.96 5.96a14.926 14.926 0 0 1-5.841 2.58m-.119-8.54a6 6 0 0 0-7.381 5.84h4.8m2.581-5.84a14.927 14.927 0 0 0-2.58 5.84m2.699 2.7c-.103.021-.207.041-.311.06a15.09 15.09 0 0 1-2.448-2.448 14.9 14.9 0 0 1 .06-.312m-2.24 2.39a4.499 4.499 0 0 0-1.757 4.306 4.499 4.499 0 0 0 4.306-1.758M16.5 9a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0Z"/>
                        </svg>
                        {{ $data['hero_button1_level'] }}
                        <svg class="h-4 w-4 transition-transform duration-300 group-hover:translate-x-1" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3"/>
                        </svg>
                    </a>

                    <a class="btn-outline btn-lg" href="{{ $data['hero_button2_url'] }}">
                        {{ $data['hero_button2_lavel'] }}
                    </a>
                </div>

                {{-- Trust strip --}}
                <dl class="mt-12 grid max-w-lg grid-cols-3 gap-6 border-t border-[rgb(var(--line)/0.08)] pt-8">
                    <div>
                        <dt class="stat-label">{{ __('Earn Rate') }}</dt>
                        <dd class="stat-value text-2xl sm:text-3xl">High</dd>
                    </div>
                    <div>
                        <dt class="stat-label">{{ __('Payout') }}</dt>
                        <dd class="stat-value text-2xl sm:text-3xl">{{ __('Fast') }}</dd>
                    </div>
                    <div>
                        <dt class="stat-label">{{ __('Support') }}</dt>
                        <dd class="stat-value text-2xl sm:text-3xl">24/7</dd>
                    </div>
                </dl>
            </div>

            {{-- Art --}}
            <div class="lg:col-span-6" data-reveal data-reveal-delay="120">
                <div class="relative mx-auto max-w-xl">
                    <div class="relative rounded-[2rem] border border-[rgb(var(--line)/0.09)] bg-[rgb(var(--surface-raised))] p-3 shadow-lift">
                        <div class="overflow-hidden rounded-3xl bg-[rgb(var(--surface-muted))] p-5">
                            <div class="mb-5 flex items-center justify-between">
                                <div class="flex gap-1.5">
                                    <span class="h-2.5 w-2.5 rounded-full bg-rose-400/70"></span>
                                    <span class="h-2.5 w-2.5 rounded-full bg-amber-400/70"></span>
                                    <span class="h-2.5 w-2.5 rounded-full bg-emerald-400/70"></span>
                                </div>
                                <span class="text-[0.65rem] font-semibold uppercase tracking-[0.18em] text-[rgb(var(--text-muted))]">
                                    {{ __('Investment Plans') }}
                                </span>
                            </div>

                            <div class="space-y-3">
                                @foreach([
                                    ['Starter Plan', '5%', 'shield', __('Secure investment'), 'brand'],
                                    ['Growth Plan', '12%', 'trending', __('Daily returns'), 'earn'],
                                    ['Premium Plan', '25%', 'star', __('VIP rewards'), 'violet'],
                                ] as $i => $demo)
                                    <div class="flex items-center gap-3.5 rounded-2xl border border-[rgb(var(--line)/0.08)] bg-[rgb(var(--surface-raised))] p-3.5 transition-transform duration-500 ease-spring hover:-translate-y-0.5"
                                         style="animation: float 7s ease-in-out infinite; animation-delay: {{ $i * 0.9 }}s">
                                        <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl
                                            {{ $demo[4] === 'earn' ? 'bg-earn-500/12 text-earn-600 dark:text-earn-300' : 'bg-brand-500/12 text-brand-600 dark:text-brand-300' }}">
                                            @if($demo[2] === 'shield')
                                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75m-3-7.036A11.959 11.959 0 0 1 3.598 6 11.99 11.99 0 0 0 3 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285Z"/>
                                                </svg>
                                            @elseif($demo[2] === 'trending')
                                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18 9 11.25l4.306 4.306a11.95 11.95 0 0 1 5.814-5.518l2.74-1.22m0 0-5.94-2.281m5.94 2.28-2.28 5.941"/>
                                                </svg>
                                            @else
                                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M11.48 3.499a.562.562 0 0 1 1.04 0l2.125 5.111a.563.563 0 0 0 .475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 0 0-.182.557l1.285 5.385a.562.562 0 0 1-.84.61l-4.725-2.885a.562.562 0 0 0-.586 0L6.982 20.54a.562.562 0 0 1-.84-.61l1.285-5.386a.562.562 0 0 0-.182-.557l-4.204-3.602a.562.562 0 0 1 .321-.988l5.518-.442a.563.563 0 0 0 .475-.345L11.48 3.5Z"/>
                                                </svg>
                                            @endif
                                        </span>

                                        <div class="min-w-0 flex-1">
                                            <p class="truncate text-sm font-semibold text-[rgb(var(--text-strong))]">{{ $demo[0] }}</p>
                                            <p class="mt-0.5 text-xs text-[rgb(var(--text-muted))]">{{ $demo[3] }}</p>
                                        </div>

                                        <span class="shrink-0 rounded-lg bg-earn-500/12 px-2.5 py-1.5 text-sm font-bold text-earn-700 dark:text-earn-300">
                                            {{ $demo[1] }}
                                        </span>
                                    </div>
                                @endforeach
                            </div>

                            <div class="mt-5 flex items-center justify-between rounded-2xl border border-dashed border-[rgb(var(--line)/0.14)] p-4">
                                <div class="flex items-center gap-2.5">
                                    <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-brand-500 text-white">
                                        <svg class="h-4.5 w-4.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5"/>
                                        </svg>
                                    </span>
                                    <div>
                                        <p class="text-xs font-semibold text-[rgb(var(--text-strong))]">{{ __('Verified & Secure') }}</p>
                                        <p class="text-[0.7rem] text-[rgb(var(--text-muted))]">{{ __('Protected investments') }}</p>
                                    </div>
                                </div>
                                <span class="badge-earn">{{ __('Active') }}</span>
                            </div>
                        </div>
                    </div>

                    {{-- floating chips --}}
                    <div class="absolute -left-6 top-1/4 hidden animate-float rounded-2xl border border-[rgb(var(--line)/0.09)] bg-[rgb(var(--surface-raised))] px-4 py-3 shadow-lift sm:block">
                        <p class="text-[0.65rem] font-semibold uppercase tracking-wider text-[rgb(var(--text-muted))]">{{ __('Status') }}</p>
                        <p class="font-display text-lg font-bold text-[rgb(var(--text-strong))]">{{ __('Trusted') }}</p>
                    </div>

                    <div class="absolute -right-5 bottom-16 hidden animate-float rounded-2xl border border-[rgb(var(--line)/0.09)] bg-[rgb(var(--surface-raised))] px-4 py-3 shadow-lift sm:block"
                         style="animation-delay: 2.5s">
                        <p class="text-[0.65rem] font-semibold uppercase tracking-wider text-[rgb(var(--text-muted))]">{{ __('Returns') }}</p>
                        <p class="font-display text-lg font-bold text-earn-600 dark:text-earn-400">{{ __('Guaranteed') }}</p>
                    </div>

                    <div class="dot-field -z-10 absolute -bottom-8 -left-10 h-32 w-32 rounded-3xl opacity-60"></div>
                </div>
            </div>
        </div>
    </div>
</section>
