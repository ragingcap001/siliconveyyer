@php
    $landingContent = \App\Models\LandingContent::where('type','counter')->where('locale',app()->getLocale())->get();
    $counterCols = [
        1 => 'grid-cols-1',
        2 => 'grid-cols-2',
        3 => 'grid-cols-2 md:grid-cols-3',
        4 => 'grid-cols-2 md:grid-cols-4',
    ][min(max($landingContent->count(), 1), 4)];
@endphp

<section class="section-tight relative overflow-hidden">
    <div class="shell">
        <div class="relative overflow-hidden rounded-[2rem] border border-white/10 bg-brand-950 px-6 py-14 sm:px-12 lg:py-20">

            <div class="pointer-events-none absolute inset-0 opacity-[0.16]"
                 style="background-image: linear-gradient(to right, #fff 1px, transparent 1px), linear-gradient(to bottom, #fff 1px, transparent 1px); background-size: 46px 46px;"></div>
            <div class="pointer-events-none absolute -right-24 -top-24 h-72 w-72 rounded-full blur-3xl"
                 style="background: radial-gradient(circle, rgba(20,184,166,.5) 0%, transparent 70%)"></div>
            <div class="pointer-events-none absolute -bottom-32 -left-20 h-80 w-80 rounded-full blur-3xl"
                 style="background: radial-gradient(circle, rgba(91,97,248,.55) 0%, transparent 70%)"></div>

            <div class="relative grid gap-10 {{ $counterCols }}">
                @foreach($landingContent as $content)
                    <div class="text-center sm:text-left" data-reveal data-reveal-delay="{{ $loop->index * 90 }}">
                        @if(!empty($content->icon))
                            <img src="{{ asset($content->icon) }}" alt="" class="mx-auto h-10 w-10 object-contain sm:mx-0"/>
                        @endif
                        <p class="mt-5 font-display text-4xl font-bold tracking-tight text-white sm:text-5xl">
                            {{ $content->description }}
                        </p>
                        <p class="mt-2 text-sm font-medium uppercase tracking-[0.12em] text-white/60">
                            {{ $content->title }}
                        </p>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</section>
