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

            {{-- Brand ---------------------------------------------------- --}}
            <a href="{{ route('home') }}" class="flex shrink-0 items-center gap-2.5">
                <img src="{{ asset(setting('site_logo','global')) }}" alt="{{ setting('site_title','global') }}"
                     class="h-9 w-auto max-w-[150px] object-contain"/>
            </a>

            {{-- Desktop nav ---------------------------------------------- --}}
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

            {{-- Actions --------------------------------------------------- --}}
            <div class="flex items-center gap-2">

                {{-- language --}}
                @if($languages->count() > 1)
                    <div class="relative hidden sm:block" @click.outside="langOpen = false">
                        <button @click="langOpen = !langOpen" type="button"
                                class="flex items-center gap-1.5 rounded-lg px-2.5 py-2 text-sm font-medium text-[rgb(var(--text-body))] transition-colors hover:bg-[rgb(var(--line)/0.06)]">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.7" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 21a9 9 0 1 0 0-18 9 9 0 0 0 0 18Zm0 0c2.5-2.6 3.5-5.6 3.5-9s-1-6.4-3.5-9m0 0c-2.5 2.6-3.5 5.6-3.5 9s1 6.4 3.5 9M3.5 9h17M3.5 15h17"/>
                            </svg>
                            <span class="uppercase">{{ app()->getLocale() }}</span>
                            <svg class="h-3.5 w-3.5 transition-transform duration-300" :class="langOpen && 'rotate-180'" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m6 9 6 6 6-6"/>
                            </svg>
                        </button>

                        <div x-show="langOpen" x-transition.opacity.duration.200ms
                             x-cloak
                             class="absolute right-0 mt-2 w-40 overflow-hidden rounded-xl border border-[rgb(var(--line)/0.1)] bg-[rgb(var(--surface-raised))] p-1.5 shadow-lift">
                            @foreach($languages as $lang)
                                <a href="{{ route('language-update',['name'=> $lang->locale]) }}"
                                   class="flex items-center justify-between rounded-lg px-3 py-2 text-sm transition-colors
                                          hover:bg-[rgb(var(--line)/0.06)]
                                          {{ app()->getLocale() == $lang->locale ? 'font-semibold text-brand-600 dark:text-brand-300' : 'text-[rgb(var(--text-body))]' }}">
                                    {{ $lang->name }}
                                    @if(app()->getLocale() == $lang->locale)
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2.2" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5"/>
                                        </svg>
                                    @endif
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif

                {{-- colour mode --}}
                <button type="button"
                        @click="var d = document.documentElement.classList.toggle('dark'); localStorage.setItem('site-color-mode', d ? 'dark' : 'light')"
                        class="relative rounded-lg p-2.5 text-[rgb(var(--text-body))] transition-colors hover:bg-[rgb(var(--line)/0.06)]"
                        aria-label="{{ __('Toggle colour mode') }}">
                    <svg class="h-[18px] w-[18px] dark:hidden" fill="none" viewBox="0 0 24 24" stroke-width="1.7" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v2.25m6.364.386-1.591 1.591M21 12h-2.25m-.386 6.364-1.591-1.591M12 18.75V21m-4.773-4.227-1.591 1.591M5.25 12H3m4.227-4.773L5.636 5.636M15.75 12a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0Z"/>
                    </svg>
                    <svg class="hidden h-[18px] w-[18px] dark:block" fill="none" viewBox="0 0 24 24" stroke-width="1.7" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21.752 15.002A9.72 9.72 0 0 1 18 15.75c-5.385 0-9.75-4.365-9.75-9.75 0-1.33.266-2.597.748-3.752A9.753 9.753 0 0 0 3 11.25C3 16.635 7.365 21 12.75 21a9.753 9.753 0 0 0 9.002-5.998Z"/>
                    </svg>
                </button>

                {{-- auth --}}
                <div class="hidden items-center gap-2 lg:flex">
                    @auth('web')
                        <a href="{{ route('user.dashboard') }}" class="btn-outline btn-sm">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 0 1 6 3.75h2.25A2.25 2.25 0 0 1 10.5 6v2.25a2.25 2.25 0 0 1-2.25 2.25H6a2.25 2.25 0 0 1-2.25-2.25V6ZM3.75 15.75A2.25 2.25 0 0 1 6 13.5h2.25a2.25 2.25 0 0 1 2.25 2.25V18a2.25 2.25 0 0 1-2.25 2.25H6A2.25 2.25 0 0 1 3.75 18v-2.25ZM13.5 6a2.25 2.25 0 0 1 2.25-2.25H18A2.25 2.25 0 0 1 20.25 6v2.25A2.25 2.25 0 0 1 18 10.5h-2.25A2.25 2.25 0 0 1 13.5 8.25V6ZM13.5 15.75a2.25 2.25 0 0 1 2.25-2.25H18a2.25 2.25 0 0 1 2.25 2.25V18A2.25 2.25 0 0 1 18 20.25h-2.25A2.25 2.25 0 0 1 13.5 18v-2.25Z"/>
                            </svg>
                            {{ __('Dashboard') }}
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="btn-ghost btn-sm">{{ __('Login') }}</a>
                        <a href="{{ route('register') }}" class="btn-primary btn-sm">
                            {{ __('Start Earning') }}
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3"/>
                            </svg>
                        </a>
                    @endauth
                </div>

                {{-- mobile trigger --}}
                <button @click="open = !open" type="button"
                        class="rounded-lg p-2.5 text-[rgb(var(--text-body))] transition-colors hover:bg-[rgb(var(--line)/0.06)] lg:hidden"
                        aria-label="{{ __('Menu') }}">
                    <svg x-show="!open" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5"/>
                    </svg>
                    <svg x-show="open" x-cloak class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </nav>

        {{-- Mobile sheet --------------------------------------------------- --}}
        <div x-show="open" x-cloak
             x-transition:enter="transition ease-spring duration-300"
             x-transition:enter-start="opacity-0 -translate-y-2"
             x-transition:enter-end="opacity-100 translate-y-0"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 translate-y-0"
             x-transition:leave-end="opacity-0 -translate-y-2"
             class="mt-2 overflow-hidden rounded-2xl border border-[rgb(var(--line)/0.1)] bg-[rgb(var(--surface-raised))] p-3 shadow-lift lg:hidden">

            <ul class="flex flex-col gap-0.5">
                @foreach($navigations as $navigation)
                    @if($navigation->page->status || $navigation->page_id == null)
                        <li>
                            <a href="{{ url($navigation->url) }}"
                               class="block rounded-lg px-3.5 py-2.5 text-sm font-medium transition-colors hover:bg-[rgb(var(--line)/0.06)]
                                      {{ url($navigation->url) == $currentUrl ? 'text-brand-600 dark:text-brand-300' : 'text-[rgb(var(--text-body))]' }}">
                                {{ $navigation->tname }}
                            </a>
                        </li>
                    @endif
                @endforeach
            </ul>

            <div class="mt-3 grid gap-2 border-t border-[rgb(var(--line)/0.08)] pt-3 sm:grid-cols-2">
                @auth('web')
                    <a href="{{ route('user.dashboard') }}" class="btn-outline btn-sm btn-block">{{ __('Dashboard') }}</a>
                @else
                    <a href="{{ route('login') }}" class="btn-outline btn-sm btn-block">{{ __('Login') }}</a>
                    <a href="{{ route('register') }}" class="btn-primary btn-sm btn-block">{{ __('Start Earning') }}</a>
                @endauth
            </div>

            @if($languages->count() > 1)
                <div class="mt-3 flex flex-wrap gap-1.5 border-t border-[rgb(var(--line)/0.08)] pt-3">
                    @foreach($languages as $lang)
                        <a href="{{ route('language-update',['name'=> $lang->locale]) }}"
                           class="rounded-lg px-2.5 py-1.5 text-xs font-medium transition-colors hover:bg-[rgb(var(--line)/0.06)]
                                  {{ app()->getLocale() == $lang->locale ? 'bg-brand-500/10 text-brand-600 dark:text-brand-300' : 'text-[rgb(var(--text-muted))]' }}">
                            {{ $lang->name }}
                        </a>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</header>
