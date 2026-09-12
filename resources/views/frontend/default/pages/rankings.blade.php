@extends('frontend::pages.index')

@section('title'){{ $data['title'] ?? __('Rankings') }}@endsection
@section('meta_keywords'){{ $data['meta_keywords'] ?? '' }}@endsection
@section('meta_description'){{ $data['meta_description'] ?? '' }}@endsection

@section('page-content')
    @php
        $rankings = \App\Models\Ranking::where('status', true)->get();
    @endphp

    <section class="section relative overflow-hidden">
        <div class="pointer-events-none absolute inset-0 grid-flat opacity-40"></div>

        <div class="shell relative">
            <div class="section-head-center" data-reveal>
                @if(!empty($data['title_small']))
                    <span class="eyebrow">{{ $data['title_small'] }}</span>
                @endif
                <h2 class="section-title">{{ $data['title_big'] ?? '' }}</h2>
            </div>

            @if(!empty($data['content']))
                <div class="frontend-editor-data mx-auto mt-8 max-w-3xl text-center" data-reveal>
                    {!! $data['content'] !!}
                </div>
            @endif

            <div class="mt-14 grid gap-5 sm:grid-cols-2 lg:grid-cols-4">
                @foreach($rankings as $ranking)
                    <div data-reveal data-reveal-delay="{{ min($loop->index * 90, 360) }}"
                         class="group relative overflow-hidden rounded-3xl border border-[rgb(var(--line)/0.09)] bg-[rgb(var(--surface-raised))] p-7 text-center shadow-soft transition-all duration-500 ease-spring hover:-translate-y-1.5 hover:border-brand-500/25 hover:shadow-lift">

                        <span class="absolute inset-x-0 top-0 h-0.5 bg-brand-gradient opacity-0 transition-opacity duration-500 group-hover:opacity-100"></span>

                        @if($ranking->icon)
                            <span class="mx-auto flex h-20 w-20 items-center justify-center rounded-2xl bg-[rgb(var(--surface-muted))] p-4">
                                <img src="{{ asset($ranking->icon) }}" alt="" class="h-full w-full object-contain"/>
                            </span>
                        @endif

                        <h3 class="mt-5 text-lg font-semibold text-[rgb(var(--text-strong))]">{{ $ranking->ranking_name }}</h3>
                        <p class="mt-1 text-xs font-semibold uppercase tracking-wider text-brand-600 dark:text-brand-300">
                            {{ $ranking->ranking }}
                        </p>
                        <p class="mt-3 text-sm leading-relaxed text-[rgb(var(--text-muted))]">{{ $ranking->description }}</p>

                        <div class="mt-5 flex items-center justify-center gap-4 border-t border-[rgb(var(--line)/0.07)] pt-4 text-xs">
                            <span class="text-[rgb(var(--text-muted))]">
                                {{ __('Tasks') }} <b class="text-[rgb(var(--text-strong))]">{{ $ranking->minimum_tasks ?? 0 }}</b>
                            </span>
                            <span class="h-3 w-px bg-[rgb(var(--line)/0.15)]"></span>
                            <span class="text-[rgb(var(--text-muted))]">
                                {{ __('Earned') }} <b class="text-[rgb(var(--text-strong))]">{{ $currencySymbol }}{{ $ranking->minimum_task_earning ?? 0 }}</b>
                            </span>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
@endsection
