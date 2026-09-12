@php
$footerContent =
json_decode(\App\Models\LandingPage::where('locale',app()->getLocale())->where('status',true)->where('code','footer')->first()->data,true);
@endphp

<footer class="relative overflow-hidden border-t border-[rgb(var(--line)/0.08)] bg-[rgb(var(--surface-muted))]">
    <div class="pointer-events-none absolute inset-0 grid-flat opacity-30"></div>

    <div class="shell relative">
        {{-- Intro --}}
        <div class="flex flex-col items-start gap-8 border-b border-[rgb(var(--line)/0.08)] py-12 sm:flex-row sm:items-center sm:gap-12 lg:py-16">
            @if(!empty($footerContent['right_img']))
                <img src="{{ asset($footerContent['right_img']) }}" alt="" class="h-12 w-auto object-contain"/>
            @endif
            <div class="max-w-xl">
                <h4 class="text-lg font-semibold text-[rgb(var(--text-strong))]">{{ $footerContent['widget_left_title'] ?? '' }}</h4>
                <p class="mt-2 text-sm leading-relaxed text-[rgb(var(--text-muted))]">{{ $footerContent['widget_left_description'] ?? '' }}</p>
            </div>
        </div>

        {{-- Widgets --}}
        <div class="grid gap-10 py-12 sm:grid-cols-2 lg:grid-cols-4 lg:gap-8">
            @foreach($navigations as $navigation)
                <div>
                    <h5 class="mb-4 text-sm font-semibold uppercase tracking-wider text-[rgb(var(--text-strong))]">{{ $footerContent['widget_title_'.$loop->iteration] ?? '' }}</h5>
                    <ul class="space-y-2.5">
                        @foreach($navigation as $menu)
                            @if($menu->page->status || $menu->page_id == null)
                                <li>
                                    <a href="{{ url($menu->url) }}" class="text-sm text-[rgb(var(--text-muted))] transition-colors hover:text-brand-600 dark:hover:text-brand-300">{{ $menu->tname }}</a>
                                </li>
                            @endif
                        @endforeach
                    </ul>
                </div>
            @endforeach

            {{-- Social --}}
            <div>
                <h5 class="mb-4 text-sm font-semibold uppercase tracking-wider text-[rgb(var(--text-strong))]">{{ __('Follow Us') }}</h5>
                <div class="flex flex-wrap gap-2">
                    @foreach(\App\Models\Social::all() as $social)
                        <a href="{{ url($social->url) }}"
                           class="flex h-10 w-10 items-center justify-center rounded-xl border border-[rgb(var(--line)/0.1)] bg-[rgb(var(--surface-raised))] text-[rgb(var(--text-muted))] transition-all duration-300 hover:-translate-y-0.5 hover:border-brand-500/30 hover:text-brand-600 dark:hover:text-brand-300"
                           target="_blank" rel="noopener">
                            <i class="{{ $social->class_name }}"></i>
                        </a>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- Bottom --}}
        <div class="border-t border-[rgb(var(--line)/0.08)] py-6 text-center">
            <p class="text-xs text-[rgb(var(--text-muted))]">{{ $footerContent['copyright_text'] ?? '' }}</p>
        </div>
    </div>
</footer>
