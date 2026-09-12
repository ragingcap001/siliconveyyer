@extends('frontend::pages.index')

@section('title'){{ $blog->title }}@endsection
@section('meta_keywords'){{ $data['meta_keywords'] ?? '' }}@endsection
@section('meta_description'){{ $data['meta_description'] ?? '' }}@endsection

@section('page-content')
    <section class="section relative overflow-hidden">
        <div class="pointer-events-none absolute inset-0 grid-flat opacity-30"></div>

        <div class="shell relative">
            <div class="grid gap-10 lg:grid-cols-12">

                {{-- article --}}
                <article class="lg:col-span-8" data-reveal>
                    <img src="{{ asset($blog->cover) }}" alt="{{ $blog->title }}"
                         class="aspect-[16/9] w-full rounded-3xl border border-[rgb(var(--line)/0.08)] object-cover shadow-card"/>

                    <div class="mt-8 flex flex-wrap items-center gap-3 text-sm text-[rgb(var(--text-muted))]">
                        <span class="badge-brand">{{ __('Blog') }}</span>
                        <span>{{ $blog->created_at }}</span>
                    </div>

                    <h1 class="mt-4 text-3xl font-bold leading-tight tracking-tight text-[rgb(var(--text-strong))]">
                        {{ $blog->title }}
                    </h1>

                    <div class="frontend-editor-data mt-6">{!! $blog->details !!}</div>

                    {{-- share --}}
                    <div class="mt-10 flex flex-wrap items-center gap-3 border-t border-[rgb(var(--line)/0.08)] pt-6">
                        <span class="text-sm font-semibold text-[rgb(var(--text-strong))]">{{ __('Share') }}:</span>

                        @php $shareUrl = route('blog-details', $blog->id); @endphp

                        <a href="https://www.facebook.com/sharer/sharer.php?u={{ $shareUrl }}" target="_blank" rel="noopener"
                           class="flex h-9 w-9 items-center justify-center rounded-xl border border-[rgb(var(--line)/0.1)] bg-[rgb(var(--surface-raised))] text-[rgb(var(--text-muted))] transition-all hover:-translate-y-0.5 hover:text-[#1877F2]">
                            <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24"><path d="M22 12a10 10 0 1 0-11.56 9.88v-6.99H7.9V12h2.54V9.8c0-2.5 1.49-3.89 3.77-3.89 1.1 0 2.24.2 2.24.2v2.46h-1.26c-1.24 0-1.63.77-1.63 1.56V12h2.78l-.45 2.89h-2.33v6.99A10 10 0 0 0 22 12Z"/></svg>
                        </a>

                        <a href="https://twitter.com/intent/tweet?url={{ $shareUrl }}&text={{ urlencode($blog->title) }}" target="_blank" rel="noopener"
                           class="flex h-9 w-9 items-center justify-center rounded-xl border border-[rgb(var(--line)/0.1)] bg-[rgb(var(--surface-raised))] text-[rgb(var(--text-muted))] transition-all hover:-translate-y-0.5 hover:text-ink-950 dark:hover:text-white">
                            <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231 5.45-6.231Zm-1.161 17.52h1.833L7.084 4.126H5.117L17.083 19.77Z"/></svg>
                        </a>

                        <a href="http://www.linkedin.com/shareArticle?mini=true&url={{ $shareUrl }}" target="_blank" rel="noopener"
                           class="flex h-9 w-9 items-center justify-center rounded-xl border border-[rgb(var(--line)/0.1)] bg-[rgb(var(--surface-raised))] text-[rgb(var(--text-muted))] transition-all hover:-translate-y-0.5 hover:text-[#0A66C2]">
                            <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24"><path d="M20.45 20.45h-3.56v-5.57c0-1.33-.02-3.04-1.85-3.04-1.85 0-2.13 1.45-2.13 2.94v5.67H9.35V9h3.41v1.56h.05a3.74 3.74 0 0 1 3.37-1.85c3.6 0 4.27 2.37 4.27 5.46v6.28ZM5.34 7.43a2.07 2.07 0 1 1 0-4.13 2.07 2.07 0 0 1 0 4.13ZM7.12 20.45H3.56V9h3.56v11.45ZM22.22 0H1.77C.79 0 0 .77 0 1.73v20.54C0 23.23.79 24 1.77 24h20.45c.98 0 1.78-.77 1.78-1.73V1.73C24 .77 23.2 0 22.22 0Z"/></svg>
                        </a>

                        <a href="https://wa.me/?text={{ $shareUrl }}" target="_blank" rel="noopener"
                           class="flex h-9 w-9 items-center justify-center rounded-xl border border-[rgb(var(--line)/0.1)] bg-[rgb(var(--surface-raised))] text-[rgb(var(--text-muted))] transition-all hover:-translate-y-0.5 hover:text-[#25D366]">
                            <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24"><path d="M17.47 14.38c-.3-.15-1.75-.86-2.02-.96-.27-.1-.47-.15-.67.15-.2.3-.77.96-.94 1.16-.17.2-.35.22-.64.07-.3-.15-1.25-.46-2.38-1.47-.88-.78-1.47-1.75-1.64-2.05-.17-.3-.02-.46.13-.61.13-.13.3-.35.45-.52.15-.17.2-.3.3-.5.1-.2.05-.37-.02-.52-.08-.15-.67-1.6-.92-2.19-.24-.58-.49-.5-.67-.51h-.57c-.2 0-.52.07-.79.37-.27.3-1.04 1.01-1.04 2.47s1.06 2.86 1.21 3.06c.15.2 2.1 3.2 5.08 4.49.71.31 1.26.49 1.69.63.71.23 1.36.19 1.87.12.57-.09 1.75-.71 2-1.4.25-.69.25-1.29.17-1.41-.07-.12-.27-.2-.57-.35M12.04 2C6.58 2 2.13 6.45 2.13 11.91c0 1.75.46 3.45 1.32 4.95L2 22l5.25-1.38a9.86 9.86 0 0 0 4.79 1.22h.01c5.46 0 9.91-4.45 9.91-9.91 0-2.65-1.03-5.14-2.9-7.01A9.82 9.82 0 0 0 12.04 2m0 18.15h-.01a8.24 8.24 0 0 1-4.19-1.15l-.3-.18-3.11.82.83-3.04-.2-.31a8.2 8.2 0 0 1-1.26-4.38c0-4.54 3.7-8.23 8.24-8.23 2.2 0 4.27.86 5.83 2.41a8.19 8.19 0 0 1 2.41 5.83c0 4.54-3.7 8.23-8.24 8.23"/></svg>
                        </a>
                    </div>
                </article>

                {{-- sidebar --}}
                <aside class="lg:col-span-4" data-reveal data-reveal-delay="100">
                    <div class="sticky top-28 space-y-4">
                        <div class="rounded-3xl border border-[rgb(var(--line)/0.09)] bg-[rgb(var(--surface-raised))] p-6 shadow-soft">
                            <h3 class="text-sm font-semibold uppercase tracking-wider text-[rgb(var(--text-strong))]">
                                {{ $data['sidebar_widget_title'] ?? __('Recent Posts') }}
                            </h3>

                            <ul class="mt-4 space-y-1">
                                @foreach($blogs as $recent)
                                    <li>
                                        <a href="{{ route('blog-details', $recent->id) }}"
                                           class="group flex items-start gap-3 rounded-xl px-3 py-2.5 transition-colors hover:bg-[rgb(var(--line)/0.05)]">
                                            <span class="mt-1.5 h-1.5 w-1.5 shrink-0 rounded-full bg-[rgb(var(--line)/0.25)] transition-colors group-hover:bg-brand-500"></span>
                                            <span class="text-sm leading-snug text-[rgb(var(--text-body))]">
                                                {{ Str::limit($recent->title, 40) }}
                                            </span>
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>

                        <a href="{{ route('page', 'blog') }}" class="btn-outline btn-block">
                            {{ __('All posts') }}
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3"/>
                            </svg>
                        </a>
                    </div>
                </aside>
            </div>
        </div>
    </section>
@endsection
