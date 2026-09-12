@extends('frontend::pages.index')

@section('title'){{ $data['title'] ?? __('About Us') }}@endsection
@section('meta_keywords'){{ $data['meta_keywords'] ?? '' }}@endsection
@section('meta_description'){{ $data['meta_description'] ?? '' }}@endsection

@section('page-content')
    <section class="section relative overflow-hidden">
        <div class="glow-blob -right-32 top-10 h-80 w-80 opacity-50"></div>
        <div class="dot-field -z-10 absolute -left-6 bottom-24 h-36 w-36 rounded-3xl opacity-50"></div>

        <div class="shell relative">
            <div class="grid items-center gap-12 lg:grid-cols-2 lg:gap-16">

                {{-- image --}}
                <div class="relative" data-reveal>
                    <div class="overflow-hidden rounded-[2rem] border border-[rgb(var(--line)/0.09)] bg-[rgb(var(--surface-muted))] p-3 shadow-lift">
                        @if(!empty($data['aboutusLeftImg']))
                            <img src="{{ asset($data['aboutusLeftImg']) }}" alt=""
                                 class="h-auto w-full rounded-3xl object-cover"/>
                        @endif
                    </div>

                    @if(!empty($data['left_img_badge']))
                        <div class="absolute -bottom-6 -right-3 hidden animate-float rounded-2xl border border-[rgb(var(--line)/0.09)] bg-[rgb(var(--surface-raised))] px-5 py-4 shadow-lift sm:block">
                            <p class="text-sm font-semibold text-[rgb(var(--text-strong))]">{{ $data['left_img_badge'] }}</p>
                        </div>
                    @endif
                </div>

                {{-- intro copy --}}
                <div data-reveal data-reveal-delay="100">
                    @if(!empty($data['title_small']))
                        <span class="eyebrow">{{ $data['title_small'] }}</span>
                    @endif
                    <h2 class="section-title">{{ $data['title_big'] ?? '' }}</h2>

                    @if(!empty($data['side_content']))
                        <p class="mt-5 text-[0.95rem] leading-relaxed text-[rgb(var(--text-muted))]">
                            {{ $data['side_content'] }}
                        </p>
                    @endif
                </div>
            </div>

            {{-- long form content --}}
            @if(!empty($data['content']))
                <div class="frontend-editor-data mx-auto mt-16 max-w-3xl" data-reveal>
                    {!! $data['content'] !!}
                </div>
            @endif
        </div>
    </section>
@endsection
