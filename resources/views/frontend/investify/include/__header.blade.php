@php
    $currentUrl = Request::url();
    $languages = \App\Models\Language::where('status', true)->get();
@endphp

<header x-data="{ open: false, scrolled: false, langOpen: false }"
        x-init="scrolled = window.scrollY > 12"
        @scroll.window="scrolled = window.scrollY > 12"
        class="fixed inset-x-0 top-0 z-50 transition-all duration-500 ease-spring"
        :class="scrolled ? 'py-2' : 'py-4'">

    <div class="shell">
        <nav class="relative flex items-center justify-between gap-6 rounded-2xl px-4 py-3 transition-all duration-500 ease-spring sm:px-5"
             :class="scrolled
                ? 'glass border border-[rgb(var(--line)/0.1)] shadow-card'
                : 'border border-transparent'">

            {{-- Brand --}}
            <a href="{{ route('home') }}" class="flex shrink-0 items-center gap-2.5">
                <img src="{{ asset(setting('site_logo','global')) }}" alt="{{ setting('site_title','global') }}"
                     class="h-9 w-auto max-w-[150px] object-contain"/>
            </a>

            {{-- Desktop nav --}}
            <ul class="hidden items-center gap-1 lg:flex">
                @foreach($navigations as $navigation)
                    @if($navigation->page->status || $navigation->page_id == null)
                        <li>
                            <a href="{{ url($navigation->url) }}"
                               class="relative rounded-lg px-3.5 py-2 text-sm font-medium transition-colors duration-300
                                      {{ url($navigation->url) == $currentUrl
                                          ? 'text-brand-600 dark:text-brand-300'
                                          : 'text-[rgb(var(--text-body))] hover:text-[rgb(var(--text-strong))]' }}">
                                {{ $navigation->tname }}
                                @if(url($navigation->url) == $currentUrl)
                                    <span class="absolute inset-x-3 -bottom-0.5 h-0.5 rounded-full bg-brand-500"></span>
                                @endif
                            </a>
                        </li>
                    @endif
                @endforeach
            </ul>

            {{-- Actions --}}
            <div class="flex items-center gap-2">

                {{-- language --}}
                @if($languages->count() > 1)
                    <div class="relative hidden sm:block" @click.outside="langOpen = false">
                        <button @click="langOpen = !langOpen" type="button"
                                class="flex items-center gap-1.5 rounded-lg px-3 py-2 text-sm font-medium text-[rgb(var(--text-body))] transition-colors hover:text-[rgb(var(--text-strong))]">
                            {{ localeName() }}
                            <svg class="h-4 w-4 transition-transform duration-200" :class="langOpen && 'rotate-180'" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5"/>
                            </svg>
                        </button>
                        <div x-show="langOpen" x-cloak x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0"
                             x-transition:leave="transition ease-in duration-150"
                             x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 -translate-y-2"
                             class="absolute right-0 top-full z-50 mt-2 w-40 overflow-hidden rounded-xl border border-[rgb(var(--line)/0.1)] bg-[rgb(var(--surface-raised))] shadow-lift">
                            @foreach($languages as $lang)
                                <a href="{{ route('language-update',['name' => $lang->locale]) }}"
                                   class="flex items-center gap-2 px-4 py-2.5 text-sm transition-colors hover:bg-[rgb(var(--line)/0.05)] {{ App::currentLocale() == $lang->locale ? 'text-brand-600 dark:text-brand-300 font-semibold' : 'text-[rgb(var(--text-body))]' }}">
                                    {{ $lang->name }}
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif

                {{-- auth buttons --}}
                <div class="hidden items-center gap-2 md:flex">
                    @auth('web')
                        <a class="btn-primary btn-sm" href="{{ route('user.dashboard') }}">{{ __('Dashboard') }}</a>
                    @else
                        <a class="btn-outline btn-sm" href="{{ route('login') }}">{{ __('Login') }}</a>
                        <a class="btn-primary btn-sm" href="{{ route('register') }}">{{ __('Register') }}</a>
                    @endauth
                </div>

                {{-- mobile hamburger --}}
                <button @click="open = !open" type="button" class="flex lg:hidden">
                    <svg x-show="!open" class="h-6 w-6 text-[rgb(var(--text-strong))]" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 9h16.5m-16.5 6.75h16.5"/>
                    </svg>
                    <svg x-show="open" x-cloak class="h-6 w-6 text-[rgb(var(--text-strong))]" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </nav>

        {{-- Mobile menu --}}
        <div x-show="open" x-cloak x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 -translate-y-4" x-transition:enter-end="opacity-100 translate-y-0"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 -translate-y-4"
             class="mt-2 overflow-hidden rounded-2xl border border-[rgb(var(--line)/0.1)] bg-[rgb(var(--surface-raised))] p-4 shadow-lift lg:hidden">
            <ul class="space-y-1">
                @foreach($navigations as $navigation)
                    @if($navigation->page->status || $navigation->page_id == null)
                        <li>
                            <a href="{{ url($navigation->url) }}"
                               class="block rounded-xl px-4 py-3 text-sm font-medium transition-colors
                                      {{ url($navigation->url) == $currentUrl
                                          ? 'bg-brand-500/10 text-brand-600 dark:text-brand-300'
                                          : 'text-[rgb(var(--text-body))] hover:bg-[rgb(var(--line)/0.05)] hover:text-[rgb(var(--text-strong))]'}}">
                                {{ $navigation->tname }}
                            </a>
                        </li>
                    @endif
                @endforeach
            </ul>
            <div class="mt-4 flex flex-col gap-2 border-t border-[rgb(var(--line)/0.08)] pt-4">
                @auth('web')
                    <a class="btn-primary btn-sm btn-block" href="{{ route('user.dashboard') }}">{{ __('Dashboard') }}</a>
                @else
                    <a class="btn-outline btn-sm btn-block" href="{{ route('login') }}">{{ __('Login') }}</a>
                    <a class="btn-primary btn-sm btn-block" href="{{ route('register') }}">{{ __('Register') }}</a>
                @endauth
            </div>
        </div>
    </div>
</header>
