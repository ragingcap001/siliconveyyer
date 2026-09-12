@php
    // Accepts a plain Vimeo / YouTube URL and converts it to an embed URL.
    $rawVideo = $data['intro_video'] ?? '';
    $embedUrl = null;

    if ($rawVideo) {
        if (preg_match('/vimeo\.com\/(\d+)/', $rawVideo, $m)) {
            $embedUrl = 'https://player.vimeo.com/video/'.$m[1];
        } elseif (preg_match('/(?:youtube\.com\/watch\?v=|youtu\.be\/|youtube\.com\/embed\/)([\w-]+)/', $rawVideo, $m)) {
            $embedUrl = 'https://www.youtube.com/embed/'.$m[1];
        }
    }
@endphp

<section class="section relative overflow-hidden bg-[rgb(var(--surface-muted))]">
    <div class="pointer-events-none absolute inset-0 grid-flat opacity-40"></div>

    <div class="shell relative">
        <div class="grid items-center gap-14 lg:grid-cols-2 lg:gap-20">

            {{-- media ----------------------------------------------------- --}}
            <div data-reveal>
                <div class="relative overflow-hidden rounded-[2rem] border border-[rgb(var(--line)/0.09)] bg-[rgb(var(--surface-raised))] p-3 shadow-lift">
                    @if($embedUrl)
                        <div class="relative overflow-hidden rounded-3xl bg-brand-950">
                            <iframe src="{{ $embedUrl }}" title="{{ $data['calculation_title_small'] ?? __('Intro') }}"
                                    class="aspect-video w-full"
                                    frameborder="0" loading="lazy"
                                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                    allowfullscreen></iframe>
                        </div>
                    @elseif(!empty($data['calculation_left_img']))
                        <img src="{{ asset($data['calculation_left_img']) }}" alt=""
                             class="h-auto w-full rounded-3xl object-cover"/>
                    @else
                        {{-- fallback vector --}}
                        <svg viewBox="0 0 480 320" class="h-auto w-full rounded-3xl" role="img" aria-label="{{ __('How the platform works') }}">
                            <defs>
                                <linearGradient id="introg" x1="0" y1="0" x2="1" y2="1">
                                    <stop offset="0%" stop-color="rgb(var(--brand))" stop-opacity=".18"/>
                                    <stop offset="100%" stop-color="#14b8a6" stop-opacity=".14"/>
                                </linearGradient>
                            </defs>
                            <rect width="480" height="320" fill="url(#introg)"/>
                            <g stroke="rgb(var(--brand))" stroke-opacity=".2" stroke-width="1">
                                <path d="M0 64h480M0 128h480M0 192h480M0 256h480M96 0v320M192 0v320M288 0v320M384 0v320"/>
                            </g>
                            <circle cx="240" cy="160" r="54" fill="rgb(var(--surface-raised))" stroke="rgb(var(--brand))" stroke-opacity=".4" stroke-width="2"/>
                            <path d="M226 140l34 20-34 20v-40Z" fill="rgb(var(--brand))"/>
                        </svg>
                    @endif
                </div>
            </div>

            {{-- copy ------------------------------------------------------ --}}
            <div data-reveal data-reveal-delay="100">
                @if(!empty($data['calculation_title_small']))
                    <span class="eyebrow">{{ $data['calculation_title_small'] }}</span>
                @endif
                <h2 class="section-title">{{ $data['calculation_title_big'] ?? '' }}</h2>

                <p class="section-lede">
                    {{ __('Claim a task, do the work exactly as the instructions describe, then send your proof. An admin reviews it and the reward lands in your wallet the moment it is approved.') }}
                </p>

                <ul class="mt-8 space-y-4">
                    @foreach([
                        __('Browse the board and claim a task that fits you'),
                        __('Follow the instructions and collect your proof'),
                        __('Submit, get approved, and withdraw your earnings'),
                    ] as $i => $point)
                        <li class="flex items-start gap-3.5" data-reveal data-reveal-delay="{{ 120 + $i * 80 }}">
                            <span class="mt-0.5 flex h-7 w-7 shrink-0 items-center justify-center rounded-full bg-earn-500/12 text-earn-600 dark:text-earn-300">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2.4" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5"/>
                                </svg>
                            </span>
                            <span class="text-[0.95rem] leading-relaxed text-[rgb(var(--text-body))]">{{ $point }}</span>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
</section>
