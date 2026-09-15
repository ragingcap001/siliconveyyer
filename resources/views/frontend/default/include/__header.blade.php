@php
    $currentUrl = Request::url();
    $languages = \App\Models\Language::where('status', true)->get();

    $isUserPortal = auth('web')->check()
        && (request()->routeIs('user.*') || request()->is('user/*'));

    $portalUser = $isUserPortal ? auth('web')->user() : null;
@endphp

@if($isUserPortal)

    {{-- ================================================================
         CLIENT PORTAL HEADER
         ================================================================ --}}
    <header class="fixed inset-x-0 top-0 z-50 bg-white border-b border-slate-200">

        <div class="h-[72px]">
            <div class="flex items-center h-full">

                {{-- Logo --}}
                <div class="flex h-full w-[256px] shrink-0 items-center border-r border-slate-200 px-6">
                    <a href="{{ route('user.dashboard') }}"
                       class="flex items-center">
                        <img
                            src="{{ asset(setting('site_logo','global')) }}"
                            alt="{{ setting('site_title','global') }}"
                            class="h-10 w-auto max-w-[175px] object-contain"
                        />
                    </a>
                </div>

                {{-- Main header --}}
                <div class="flex items-center justify-between flex-1 min-w-0 px-6 lg:px-8">

                    {{-- Date --}}
                    <div class="hidden md:block">
                        <p class="text-sm font-medium text-slate-500">
                            {{ now()->format('l, F j, Y') }}
                        </p>
                    </div>

                    {{-- Right controls --}}
                    <div class="flex items-center gap-4 ml-auto">

                        {{-- KYC --}}
                        @if(setting('kyc_verification','permission')
                            && $portalUser->kyc !== \App\Enums\KYCStatus::Verified->value)

                            <a href="{{ route('user.kyc') }}"
                               class="inline-flex items-center gap-2 border border-rose-200 bg-rose-50 px-3 py-1.5 text-xs font-semibold text-rose-600 transition-colors hover:bg-rose-100">
                                <span class="h-1.5 w-1.5 rounded-full bg-rose-500"></span>
                                {{ __('KYC Required') }}
                            </a>

                        @endif

                        {{-- Notification --}}
                        <a href="{{ route('user.notification.all') }}"
                           class="relative flex items-center justify-center transition-colors h-9 w-9 text-slate-500 hover:text-slate-900"
                           aria-label="{{ __('Notifications') }}">

                            <svg class="h-[19px] w-[19px]"
                                 fill="none"
                                 viewBox="0 0 24 24"
                                 stroke-width="1.6"
                                 stroke="currentColor">
                                <path stroke-linecap="round"
                                      stroke-linejoin="round"
                                      d="M14.857 17.082a23.848 23.848 0 0 0 5.454-1.31A8.967 8.967 0 0 1 18 9.75V9A6 6 0 0 0 6 9v.75a8.967 8.967 0 0 1-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 0 1-5.714 0m5.714 0a3 3 0 1 1-5.714 0"/>
                            </svg>

                            {{-- Optional notification indicator --}}
                            <span class="absolute right-1.5 top-1.5 h-1.5 w-1.5 rounded-full bg-brand-500"></span>
                        </a>

                        {{-- Account --}}
                        <div class="flex items-center gap-3 pl-4 border-l border-slate-200">

                            <div class="hidden text-right sm:block">
                                <p class="text-sm font-semibold leading-tight text-slate-800">
                                    {{ $portalUser->first_name }}
                                    {{ $portalUser->last_name }}
                                </p>

                                <p class="mt-0.5 text-xs text-slate-400">
                                    {{ __('Client Account') }}
                                </p>
                            </div>

                            <div class="flex items-center justify-center text-xs font-semibold text-white h-9 w-9 bg-slate-900">
                                {{ strtoupper(
                                    substr($portalUser->first_name ?? '', 0, 1) .
                                    substr($portalUser->last_name ?? '', 0, 1)
                                ) }}
                            </div>

                        </div>

                    </div>
                </div>
            </div>
        </div>
    </header>

@else

    {{-- ================================================================
         PUBLIC WEBSITE HEADER
         ================================================================ --}}
    <header x-data="{ open: false, scrolled: false, langOpen: false }"
            x-init="scrolled = window.scrollY > 12"
            @scroll.window="scrolled = window.scrollY > 12"
            class="fixed inset-x-0 top-0 z-50 transition-all duration-500 ease-spring"
            :class="scrolled ? 'py-2' : 'py-4'">

        <div class="shell">
            <nav class="relative flex items-center justify-between gap-6 px-4 py-3 transition-all duration-500 rounded-2xl ease-spring sm:px-5"
                 :class="scrolled
                    ? 'glass border border-[rgb(var(--line)/0.1)] shadow-card'
                    : 'border border-transparent'">

                {{-- Brand --}}
                <a href="{{ route('home') }}" class="flex shrink-0 items-center gap-2.5">
                    <img
                        src="{{ asset(setting('site_logo','global')) }}"
                        alt="{{ setting('site_title','global') }}"
                        class="h-9 w-auto max-w-[150px] object-contain"
                    />
                </a>

                {{-- Desktop nav --}}
                <ul class="items-center hidden gap-1 lg:flex">
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

                    {{-- Language --}}
                    @if($languages->count() > 1)
                        <div class="relative hidden sm:block"
                             @click.outside="langOpen = false">

                            <button @click="langOpen = !langOpen"
                                    type="button"
                                    class="flex items-center gap-1.5 rounded-lg px-2.5 py-2 text-sm font-medium text-[rgb(var(--text-body))] transition-colors hover:bg-[rgb(var(--line)/0.06)]">

                                <svg class="w-4 h-4"
                                     fill="none"
                                     viewBox="0 0 24 24"
                                     stroke-width="1.7"
                                     stroke="currentColor">
                                    <path stroke-linecap="round"
                                          stroke-linejoin="round"
                                          d="M12 21a9 9 0 1 0 0-18 9 9 0 0 0 0 18Zm0 0c2.5-2.6 3.5-5.6 3.5-9s-1-6.4-3.5-9m0 0c-2.5 2.6-3.5 5.6-3.5 9s1 6.4 3.5 9M3.5 9h17M3.5 15h17"/>
                                </svg>

                                <span class="uppercase">
                                    {{ app()->getLocale() }}
                                </span>

                                <svg class="h-3.5 w-3.5 transition-transform duration-300"
                                     :class="langOpen && 'rotate-180'"
                                     fill="none"
                                     viewBox="0 0 24 24"
                                     stroke-width="2"
                                     stroke="currentColor">
                                    <path stroke-linecap="round"
                                          stroke-linejoin="round"
                                          d="m6 9 6 6 6-6"/>
                                </svg>
                            </button>

                            <div x-show="langOpen"
                                 x-transition.opacity.duration.200ms
                                 x-cloak
                                 class="absolute right-0 mt-2 w-40 overflow-hidden rounded-xl border border-[rgb(var(--line)/0.1)] bg-[rgb(var(--surface-raised))] p-1.5 shadow-lift">

                                @foreach($languages as $lang)
                                    <a href="{{ route('language-update',['name'=> $lang->locale]) }}"
                                       class="flex items-center justify-between rounded-lg px-3 py-2 text-sm transition-colors hover:bg-[rgb(var(--line)/0.06)]
                                       {{ app()->getLocale() == $lang->locale
                                            ? 'font-semibold text-brand-600 dark:text-brand-300'
                                            : 'text-[rgb(var(--text-body))]' }}">

                                        {{ $lang->name }}

                                        @if(app()->getLocale() == $lang->locale)
                                            <svg class="w-4 h-4"
                                                 fill="none"
                                                 viewBox="0 0 24 24"
                                                 stroke-width="2.2"
                                                 stroke="currentColor">
                                                <path stroke-linecap="round"
                                                      stroke-linejoin="round"
                                                      d="m4.5 12.75 6 6 9-13.5"/>
                                            </svg>
                                        @endif

                                    </a>
                                @endforeach

                            </div>
                        </div>
                    @endif

                    {{-- Colour mode --}}
                    <button type="button"
                            @click="var d = document.documentElement.classList.toggle('dark'); localStorage.setItem('site-color-mode', d ? 'dark' : 'light')"
                            class="relative rounded-lg p-2.5 text-[rgb(var(--text-body))] transition-colors hover:bg-[rgb(var(--line)/0.06)]"
                            aria-label="{{ __('Toggle colour mode') }}">

                        <svg class="h-[18px] w-[18px] dark:hidden"
                             fill="none"
                             viewBox="0 0 24 24"
                             stroke-width="1.7"
                             stroke="currentColor">
                            <path stroke-linecap="round"
                                  stroke-linejoin="round"
                                  d="M12 3v2.25m6.364.386-1.591 1.591M21 12h-2.25m-.386 6.364-1.591-1.591M12 18.75V21m-4.773-4.227-1.591 1.591M5.25 12H3m4.227-4.773L5.636 5.636M15.75 12a3.75 3.75 0 1 1-7.5 0Z"/>
                        </svg>

                        <svg class="hidden h-[18px] w-[18px] dark:block"
                             fill="none"
                             viewBox="0 0 24 24"
                             stroke-width="1.7"
                             stroke="currentColor">
                            <path stroke-linecap="round"
                                  stroke-linejoin="round"
                                  d="M21.752 15.002A9.72 9.72 0 0 1 18 15.75c-5.385 0-9.75-4.365-9.75-9.75 0-1.33.266-2.597.748-3.752A9.753 9.753 0 0 0 3 11.25C3 16.635 7.365 21 12.75 21a9.753 9.753 0 0 0 9.002-5.998Z"/>
                        </svg>

                    </button>

                    {{-- Auth --}}
                    <div class="items-center hidden gap-2 lg:flex">
                        @auth('web')

                            <a href="{{ route('user.dashboard') }}"
                               class="btn-outline btn-sm">
                                {{ __('Dashboard') }}
                            </a>

                        @else

                            <a href="{{ route('login') }}"
                               class="btn-ghost btn-sm">
                                {{ __('Login') }}
                            </a>

                            <a href="{{ route('register') }}"
                               class="btn-primary btn-sm">
                                {{ __('Start Earning') }}
                            </a>

                        @endauth
                    </div>

                    {{-- Mobile --}}
                    <button @click="open = !open"
                            type="button"
                            class="rounded-lg p-2.5 text-[rgb(var(--text-body))] lg:hidden"
                            aria-label="{{ __('Menu') }}">

                        <svg x-show="!open"
                             class="w-5 h-5"
                             fill="none"
                             viewBox="0 0 24 24"
                             stroke-width="1.8"
                             stroke="currentColor">
                            <path stroke-linecap="round"
                                  stroke-linejoin="round"
                                  d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5"/>
                        </svg>

                        <svg x-show="open"
                             x-cloak
                             class="w-5 h-5"
                             fill="none"
                             viewBox="0 0 24 24"
                             stroke-width="1.8"
                             stroke="currentColor">
                            <path stroke-linecap="round"
                                  stroke-linejoin="round"
                                  d="M6 18 18 6M6 6l12 12"/>
                        </svg>

                    </button>

                </div>
            </nav>

            {{-- Existing mobile sheet can remain here --}}
            <div x-show="open"
                 x-cloak
                 x-transition
                 class="mt-2 overflow-hidden rounded-2xl border border-[rgb(var(--line)/0.1)] bg-[rgb(var(--surface-raised))] p-3 shadow-lift lg:hidden">

                <ul class="flex flex-col gap-0.5">
                    @foreach($navigations as $navigation)
                        @if($navigation->page->status || $navigation->page_id == null)
                            <li>
                                <a href="{{ url($navigation->url) }}"
                                   class="block rounded-lg px-3.5 py-2.5 text-sm font-medium transition-colors hover:bg-[rgb(var(--line)/0.06)]">
                                    {{ $navigation->tname }}
                                </a>
                            </li>
                        @endif
                    @endforeach
                </ul>

            </div>
        </div>
    </header>

@endif
