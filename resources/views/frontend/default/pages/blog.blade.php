@extends('frontend::pages.index')

@section('title'){{ $data->title }}@endsection
@section('meta_keywords'){{ $data['meta_keywords'] }}@endsection
@section('meta_description'){{ $data['meta_description'] }}@endsection

@section('page-content')
    @php
        $blogs = \App\Models\Blog::where('locale', app()->getLocale())->latest()->paginate(6);
    @endphp

    <section class="section relative overflow-hidden">
        <div class="pointer-events-none absolute inset-0 grid-flat opacity-40"></div>

        <div class="shell relative">
            <div class="section-head-center" data-reveal>
                @if(!empty($data->title_small))
                    <span class="eyebrow">{{ $data->title_small }}</span>
                @endif
                <h2 class="section-title">{{ $data->title_big ?? '' }}</h2>
            </div>

            <div class="mt-12 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                @forelse($blogs as $blog)
                    <article data-reveal data-reveal-delay="{{ min($loop->index * 90, 360) }}"
                             class="group flex flex-col overflow-hidden rounded-3xl border border-[rgb(var(--line)/0.09)] bg-[rgb(var(--surface-raised))] shadow-soft transition-all duration-500 ease-spring hover:-translate-y-1.5 hover:shadow-lift">

                        <div class="relative aspect-[16/10] overflow-hidden bg-[rgb(var(--surface-muted))]">
                            <img src="{{ asset($blog->cover) }}" alt="{{ $blog->title }}"
                                 class="h-full w-full object-cover transition-transform duration-700 ease-spring group-hover:scale-105"/>
                            <span class="absolute left-4 top-4 rounded-lg bg-[rgb(var(--surface-raised)/0.9)] px-2.5 py-1.5 text-[0.68rem] font-semibold text-[rgb(var(--text-muted))] backdrop-blur">
                                {{ $blog->created_at }}
                            </span>
                        </div>

                        <div class="flex flex-1 flex-col p-6">
                            <h3 class="line-clamp-2 text-base font-semibold leading-snug text-[rgb(var(--text-strong))]">
                                <a href="{{ route('blog-details', $blog->id) }}" class="transition-colors hover:text-brand-600 dark:hover:text-brand-300">
                                    {{ $blog->title }}
                                </a>
                            </h3>
                            <p class="mt-3 flex-1 text-sm leading-relaxed text-[rgb(var(--text-muted))]">
                                {{ Str::limit(strip_tags($blog->details), 110) }}
                            </p>
                            <a href="{{ route('blog-details', $blog->id) }}" class="link-arrow mt-5">
                                {{ __('Continue Reading') }}
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3"/>
                                </svg>
                            </a>
                        </div>
                    </article>
                @empty
                    <p class="col-span-full py-16 text-center text-sm text-[rgb(var(--text-muted))]">{{ __('No posts yet.') }}</p>
                @endforelse
            </div>

            @if($blogs->hasPages())
                <div class="mt-10">{{ $blogs->links() }}</div>
            @endif
        </div>
    </section>
@endsection
