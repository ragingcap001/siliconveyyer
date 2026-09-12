<section class="section-tight relative overflow-hidden">
    <div class="shell">
        <div class="relative overflow-hidden rounded-[2rem] border border-white/10 bg-brand-950 px-6 py-16 sm:px-12 lg:px-16">

            {{-- backdrop art --}}
            <div class="pointer-events-none absolute inset-0 opacity-[0.14]"
                 style="background-image: linear-gradient(to right, #fff 1px, transparent 1px), linear-gradient(to bottom, #fff 1px, transparent 1px); background-size: 48px 48px;
                        -webkit-mask-image: radial-gradient(ellipse 70% 80% at 20% 50%, #000 30%, transparent 100%);
                        mask-image: radial-gradient(ellipse 70% 80% at 20% 50%, #000 30%, transparent 100%);"></div>
            <div class="pointer-events-none absolute -right-20 -top-28 h-80 w-80 rounded-full blur-3xl"
                 style="background: radial-gradient(circle, rgba(20,184,166,.45) 0%, transparent 70%)"></div>
            <div class="pointer-events-none absolute -bottom-32 left-1/4 h-72 w-72 rounded-full blur-3xl"
                 style="background: radial-gradient(circle, rgba(91,97,248,.5) 0%, transparent 70%)"></div>

            @if(!empty($data['cta_bg_img']))
                <img src="{{ asset($data['cta_bg_img']) }}" alt=""
                     class="pointer-events-none absolute inset-0 h-full w-full object-cover opacity-[0.08]"/>
            @endif

            <div class="relative grid items-center gap-10 lg:grid-cols-12">
                <div class="lg:col-span-7" data-reveal>
                    <h2 class="text-3xl font-bold leading-tight tracking-tight text-white sm:text-4xl">
                        {{ $data['cta_title'] ?? '' }}
                    </h2>
                </div>

                <div class="flex flex-wrap gap-3 lg:col-span-5 lg:justify-end" data-reveal data-reveal-delay="100">
                    <a href="{{ $data['cta_button1_url'] ?? route('register') }}"
                       target="{{ $data['cta_button1_target'] ?? '_self' }}"
                       class="btn btn-lg bg-white text-brand-700 shadow-lift hover:-translate-y-0.5">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.9" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.59 14.37a6 6 0 0 1-5.84 7.38v-4.8m5.84-2.58a14.98 14.98 0 0 0 6.16-12.12A14.98 14.98 0 0 0 9.631 8.41m5.96 5.96a14.926 14.926 0 0 1-5.841 2.58m-.119-8.54a6 6 0 0 0-7.381 5.84h4.8m2.581-5.84a14.927 14.927 0 0 0-2.58 5.84m2.699 2.7c-.103.021-.207.041-.311.06a15.09 15.09 0 0 1-2.448-2.448 14.9 14.9 0 0 1 .06-.312m-2.24 2.39a4.499 4.499 0 0 0-1.757 4.306 4.499 4.499 0 0 0 4.306-1.758M16.5 9a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0Z"/>
                        </svg>
                        {{ $data['cta_button1_level'] ?? __('Join Us') }}
                    </a>
                    <a href="{{ $data['cta_button2_url'] ?? route('page', 'contact') }}"
                       target="{{ $data['cta_button2_target'] ?? '_self' }}"
                       class="btn btn-lg border border-white/20 text-white hover:bg-white/10">
                        {{ $data['cta_button2_lavel'] ?? __('Contact Us') }}
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>
