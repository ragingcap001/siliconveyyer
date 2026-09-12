@php
$landingContent = \App\Models\LandingContent::where('type','whychooseus')->where('locale',app()->getLocale())->get();
@endphp

<section class="section relative overflow-hidden">
    <div class="glow-blob -left-40 bottom-0 h-96 w-96 opacity-50"
         style="background: radial-gradient(circle, rgb(20 184 166 / .22) 0%, transparent 68%)"></div>
    <div class="dot-field -z-10 absolute right-10 top-24 h-40 w-40 rounded-3xl opacity-50"></div>

    <div class="shell relative">
        <div class="grid items-center gap-14 lg:grid-cols-2 lg:gap-20">

            {{-- Art --}}
            <div class="relative order-2 lg:order-1" data-reveal>
                <div class="relative overflow-hidden rounded-[2rem] border border-[rgb(var(--line)/0.09)] bg-[rgb(var(--surface-muted))] p-3 shadow-lift">
                    @if(!empty($data['left_img']))
                        <img src="{{ asset($data['left_img']) }}" alt=""
                             class="h-auto w-full rounded-3xl object-cover"/>
                    @else
                        <svg viewBox="0 0 400 320" class="h-auto w-full rounded-3xl" role="img" aria-label="{{ __('Why choose us') }}">
                            <defs>
                                <linearGradient id="wcug" x1="0" y1="0" x2="1" y2="1">
                                    <stop offset="0%" stop-color="rgb(var(--brand))" stop-opacity=".16"/>
                                    <stop offset="100%" stop-color="#14b8a6" stop-opacity=".14"/>
                                </linearGradient>
                            </defs>
                            <rect width="400" height="320" fill="url(#wcug)"/>
                            <g stroke="rgb(var(--brand))" stroke-opacity=".22" stroke-width="1">
                                <path d="M0 80h400M0 160h400M0 240h400M80 0v320M160 0v320M240 0v320M320 0v320"/>
                            </g>
                            <g fill="rgb(var(--surface-raised))" stroke="rgb(var(--brand))" stroke-opacity=".35">
                                <rect x="60" y="70" width="150" height="54" rx="14"/>
                                <rect x="190" y="140" width="150" height="54" rx="14"/>
                                <rect x="60" y="210" width="150" height="54" rx="14"/>
                            </g>
                            <g fill="rgb(var(--brand))">
                                <circle cx="82" cy="97" r="9" fill-opacity=".85"/>
                                <circle cx="212" cy="167" r="9" fill-opacity=".85"/>
                                <circle cx="82" cy="237" r="9" fill-opacity=".85"/>
                            </g>
                            <g stroke="rgb(var(--text-muted))" stroke-linecap="round" stroke-width="7" stroke-opacity=".28">
                                <path d="M104 97h84M104 110h56"/>
                                <path d="M234 167h84M234 180h56"/>
                                <path d="M104 237h84M104 250h56"/>
                            </g>
                        </svg>
                    @endif
                </div>

                <div class="absolute -bottom-7 -right-3 hidden animate-float rounded-2xl border border-[rgb(var(--line)/0.09)] bg-[rgb(var(--surface-raised))] px-5 py-4 shadow-lift sm:block">
                    <p class="font-display text-2xl font-bold text-gradient">100%</p>
                    <p class="text-xs font-medium text-[rgb(var(--text-muted))]">{{ __('Secure & Trusted') }}</p>
                </div>
            </div>

            {{-- Copy --}}
            <div class="order-1 lg:order-2" data-reveal data-reveal-delay="100">
                @if(!empty($data['title_small']))
                    <span class="eyebrow">{{ $data['title_small'] }}</span>
                @endif
                <h2 class="section-title">{{ $data['title_big'] ?? '' }}</h2>

                <div class="mt-9 space-y-5">
                    @foreach($landingContent as $content)
                        <div class="group flex gap-4 rounded-2xl border border-transparent p-4 transition-all duration-500 ease-spring hover:border-[rgb(var(--line)/0.09)] hover:bg-[rgb(var(--surface-muted))]"
                             data-reveal data-reveal-delay="{{ $loop->index * 80 }}">
                            <span class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-brand-500/10 text-brand-600 transition-transform duration-500 group-hover:scale-110 dark:text-brand-300">
                                @if(content_exists($content->icon))
                                    <img src="{{ asset($content->icon) }}" alt="" class="h-6 w-6 object-contain"/>
                                @else
                                    <i class="anticon {{ $content->icon }} text-xl"></i>
                                @endif
                            </span>
                            <div>
                                <h4 class="text-base font-semibold text-[rgb(var(--text-strong))]">{{ $content->title }}</h4>
                                <p class="mt-1.5 text-sm leading-relaxed text-[rgb(var(--text-muted))]">{{ $content->description }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>
