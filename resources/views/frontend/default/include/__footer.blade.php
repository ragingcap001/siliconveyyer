@php
    $footer = \App\Models\LandingPage::where('locale', app()->getLocale())
                ->where('status', true)->where('code','footer')->first();
    $footerContent = $footer ? json_decode($footer->data, true) : [];
    $socials = \App\Models\Social::all();
@endphp

<footer class="relative overflow-hidden border-t border-[rgb(var(--line)/0.08)] bg-[rgb(var(--surface-muted))]">

    {{-- decorative grid + glow --}}
    <div class="pointer-events-none absolute inset-0 grid-flat opacity-60"></div>
    <div class="glow-blob -left-40 -bottom-56 h-96 w-96 opacity-70"></div>

    <div class="shell relative py-16 lg:py-20">

        {{-- top band --------------------------------------------------- --}}
        <div class="grid gap-10 lg:grid-cols-12">
            {{-- brand column --}}
            <div class="lg:col-span-4">
                <a href="{{ route('home') }}" class="inline-block">
                    <img src="{{ asset(setting('site_logo','global')) }}" alt="{{ setting('site_title','global') }}"
                         class="h-10 w-auto max-w-[170px] object-contain"/>
                </a>

                <p class="mt-5 max-w-sm text-sm leading-relaxed text-[rgb(var(--text-muted))]">
                    {{ $footerContent['widget_left_description'] ?? '' }}
                </p>

                @if($socials->count())
                    <div class="mt-6 flex flex-wrap gap-2">
                        @foreach($socials as $social)
                            <a href="{{ url($social->url) }}" target="_blank" rel="noopener"
                               class="flex h-9 w-9 items-center justify-center rounded-xl border border-[rgb(var(--line)/0.1)] bg-[rgb(var(--surface-raised))] text-[rgb(var(--text-muted))] transition-all duration-300 hover:-translate-y-0.5 hover:border-brand-500/40 hover:text-brand-600 dark:hover:text-brand-300">
                                <i class="{{ $social->class_name }} text-sm"></i>
                                <span class="sr-only">{{ $social->name ?? 'Social' }}</span>
                            </a>
                        @endforeach
                    </div>
                @endif
            </div>

            {{-- link columns --}}
            <div class="grid gap-8 sm:grid-cols-3 lg:col-span-8">
                @foreach($navigations as $navigation)
                    <div>
                        <h4 class="text-xs font-semibold uppercase tracking-[0.14em] text-[rgb(var(--text-strong))]">
                            {{ $footerContent['widget_title_'.$loop->iteration] ?? '' }}
                        </h4>
                        <ul class="mt-4 space-y-2.5">
                            @foreach($navigation as $menu)
                                @if($menu->page->status || $menu->page_id == null)
                                    <li>
                                        <a href="{{ url($menu->url) }}"
                                           class="group inline-flex items-center gap-1.5 text-sm text-[rgb(var(--text-muted))] transition-colors hover:text-brand-600 dark:hover:text-brand-300">
                                            <span class="h-1 w-1 rounded-full bg-[rgb(var(--line)/0.25)] transition-colors group-hover:bg-brand-500"></span>
                                            {{ $menu->tname }}
                                        </a>
                                    </li>
                                @endif
                            @endforeach
                        </ul>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- newsletter strip ------------------------------------------- --}}
        <div class="mt-14 flex flex-col gap-6 rounded-3xl border border-[rgb(var(--line)/0.09)] bg-[rgb(var(--surface-raised))] p-6 sm:p-8 lg:flex-row lg:items-center lg:justify-between">
            <div class="max-w-md">
                <h4 class="text-lg font-semibold text-[rgb(var(--text-strong))]">{{ __('Get new tasks in your inbox') }}</h4>
                <p class="mt-1.5 text-sm text-[rgb(var(--text-muted))]">
                    {{ __('We send a short digest when fresh, well-paid tasks go live.') }}
                </p>
            </div>

            <form action="{{ route('subscriber') }}" method="post" class="flex w-full max-w-md gap-2">
                @csrf
                <input type="email" name="email" required placeholder="{{ __('you@example.com') }}" class="field"/>
                <button type="submit" class="btn-primary shrink-0">{{ __('Subscribe') }}</button>
            </form>
        </div>

        {{-- bottom bar --------------------------------------------------- --}}
        <div class="mt-12 flex flex-col items-center justify-between gap-4 border-t border-[rgb(var(--line)/0.08)] pt-7 sm:flex-row">
            <p class="text-xs text-[rgb(var(--text-muted))]">
                &copy; {{ date('Y') }} {{ setting('site_title','global') }}. {{ __('All rights reserved.') }}
            </p>
            <p class="flex items-center gap-1.5 text-xs text-[rgb(var(--text-muted))]">
                {{ __('Built for people who get things done') }}
                <span class="inline-block h-1.5 w-1.5 rounded-full bg-earn-500"></span>
            </p>
        </div>
    </div>
</footer>
