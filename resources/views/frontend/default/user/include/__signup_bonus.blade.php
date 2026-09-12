<div id="auto-popup" class="auto-popup-section" x-data="{ open: true }" x-show="open" x-cloak>
    <div class="fixed inset-0 z-[60] flex items-center justify-center bg-ink-950/60 p-4 backdrop-blur-sm"
         @click.self="open = false">

        <div class="relative w-full max-w-md overflow-hidden rounded-3xl border border-[rgb(var(--line)/0.09)] bg-[rgb(var(--surface-raised))] p-8 text-center shadow-lift"
             x-transition:enter="transition duration-300 ease-spring"
             x-transition:enter-start="opacity-0 translate-y-4 scale-95"
             x-transition:enter-end="opacity-100 translate-y-0 scale-100">

            <div class="dot-field pointer-events-none absolute inset-0"></div>

            <button type="button" class="auto-popup-close-now absolute right-4 top-4 flex h-8 w-8 items-center justify-center rounded-lg text-[rgb(var(--text-muted))] transition hover:bg-[rgb(var(--surface-muted))] hover:text-[rgb(var(--text-strong))]"
                    @click="open = false" aria-label="{{ __('Close') }}">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2.2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"/>
                </svg>
            </button>

            <div class="relative">
                <span class="mx-auto flex h-16 w-16 items-center justify-center rounded-2xl bg-earn-500/10 text-earn-600 dark:text-earn-400">
                    <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke-width="1.7" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 11.25v8.25a1.5 1.5 0 0 1-1.5 1.5H5.25a1.5 1.5 0 0 1-1.5-1.5v-8.25M12 15.75l9-5.25M12 15.75 3 10.5m18-3.75v3.75M3 6.75v3.75"/>
                    </svg>
                </span>

                <h2 class="mt-5 text-2xl font-bold tracking-tight text-[rgb(var(--text-strong))]">{{ __('Congratulation!') }}</h2>

                <p class="mt-2 text-sm text-[rgb(var(--text-muted))]">{{ __('You got a Signup Bonus') }}</p>
                <p class="mt-1 text-3xl font-bold text-earn-600 dark:text-earn-400">
                    {{ Session::get('signup_bonus') }} {{ $currency }}
                </p>

                <button type="button" class="btn-primary btn-block auto-popup-close-now mt-7" @click="open = false">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2.4" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5"/>
                    </svg>
                    {{ __('Got it') }}
                </button>
            </div>
        </div>
    </div>
</div>

@php Session::remove('signup_bonus'); @endphp
