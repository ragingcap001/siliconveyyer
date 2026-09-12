<section class="section-tight relative overflow-hidden">
    <div class="shell">
        <div class="relative overflow-hidden rounded-[2rem] border border-[rgb(var(--line)/0.09)] bg-[rgb(var(--surface-muted))] px-6 py-14 sm:px-12">

            <div class="dot-field pointer-events-none absolute -right-6 -top-6 h-44 w-44 rounded-3xl opacity-60"></div>
            <div class="glow-blob -left-24 -bottom-32 h-72 w-72 opacity-60"></div>

            <div class="relative mx-auto max-w-2xl text-center" data-reveal>
                <span class="eyebrow">
                    <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0L3.32 8.91a2.25 2.25 0 0 1-1.07-1.916V6.75"/>
                    </svg>
                    {{ __('Newsletter') }}
                </span>

                <h2 class="section-title">{{ __('Never miss a well-paid task') }}</h2>
                <p class="section-lede mx-auto">
                    {{ __('One short email when new tasks go live. No spam, unsubscribe any time.') }}
                </p>

                <form action="{{ route('subscriber') }}" method="post"
                      class="mx-auto mt-8 flex max-w-lg flex-col gap-2 sm:flex-row">
                    @csrf
                    <input type="email" name="email" required placeholder="{{ __('you@example.com') }}" class="field flex-1"/>
                    <button type="submit" class="btn-primary shrink-0">
                        {{ __('Subscribe') }}
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3"/>
                        </svg>
                    </button>
                </form>
            </div>
        </div>
    </div>
</section>
