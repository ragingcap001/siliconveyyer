@php
$landingContent = \App\Models\LandingContent::where('type','howitworks')->where('locale',app()->getLocale())->get();

$stepCols = [
    1 => 'grid-cols-1',
    2 => 'sm:grid-cols-2',
    3 => 'sm:grid-cols-2 lg:grid-cols-3',
    4 => 'sm:grid-cols-2 lg:grid-cols-4',
][min(max($landingContent->count(), 1), 4)];
@endphp

<section class="section relative overflow-hidden bg-[rgb(var(--surface-muted))]">
    <div class="pointer-events-none absolute inset-0 grid-flat opacity-50"></div>
    <div class="glow-blob -right-32 top-0 h-80 w-80 opacity-60"></div>

    <div class="shell relative">
        <div class="section-head-center" data-reveal>
            @if(!empty($data['title_small']))
                <span class="eyebrow">{{ $data['title_small'] }}</span>
            @endif
            <h2 class="section-title">{{ $data['title_big'] ?? '' }}</h2>
        </div>

        <div class="relative mt-14">
            @if($landingContent->count() > 1)
                <div class="absolute left-0 right-0 top-8 hidden h-px lg:block"
                     style="background: repeating-linear-gradient(to right, rgb(var(--line)/0.22) 0 8px, transparent 8px 16px);"></div>
            @endif

            <div class="grid gap-8 {{ $stepCols }}">
                @foreach($landingContent as $content)
                    <div class="relative" data-reveal data-reveal-delay="{{ $loop->index * 110 }}">
                        <div class="relative z-10 flex h-16 w-16 items-center justify-center rounded-2xl border border-[rgb(var(--line)/0.1)] bg-[rgb(var(--surface-raised))] shadow-soft transition-transform duration-500 ease-spring hover:-translate-y-1">
                            @if(content_exists($content->icon))
                                <img src="{{ asset($content->icon) }}" alt="" class="h-8 w-8 object-contain"/>
                            @else
                                <span class="font-display text-xl font-bold text-gradient">
                                    {{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}
                                </span>
                            @endif

                            <span class="absolute -right-1.5 -top-1.5 flex h-6 w-6 items-center justify-center rounded-full bg-earn-500 text-white shadow-soft">
                                <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke-width="3" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5"/>
                                </svg>
                            </span>
                        </div>

                        <h3 class="mt-6 text-lg font-semibold text-[rgb(var(--text-strong))]">{{ $content->title }}</h3>
                        <p class="mt-2.5 text-sm leading-relaxed text-[rgb(var(--text-muted))]">{{ $content->description }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</section>
