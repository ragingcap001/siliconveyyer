<!DOCTYPE html>
<html lang="{{ str_replace('_','-',app()->getLocale()) }}" class="scroll-pt-28">
@include('frontend::include.__head')

<body class="min-h-screen bg-[rgb(var(--surface-muted))] font-sans text-[rgb(var(--text-body))] antialiased">

<x:notify-messages/>

@include('frontend::include.__header')

<div x-data="{ navOpen: false }" class="shell pt-28 lg:pt-32">

    {{-- page heading --}}
    <div class="mb-8">
        <h1 class="text-2xl font-bold tracking-tight text-[rgb(var(--text-strong))] sm:text-3xl">
            @yield('title')
        </h1>
        @hasSection('subtitle')
            <p class="mt-2 text-sm text-[rgb(var(--text-muted))]">@yield('subtitle')</p>
        @endif
    </div>

    <div class="flex gap-6">

        {{-- desktop rail --}}
        @include('frontend::include.__user_side_nav')

        {{-- mobile drawer --}}
        <div x-show="navOpen" x-cloak class="fixed inset-0 z-40 lg:hidden">
            <div x-show="navOpen" x-transition.opacity class="absolute inset-0 bg-ink-950/50 backdrop-blur-sm"
                 @click="navOpen = false"></div>
            <div x-show="navOpen"
                 x-transition:enter="transition ease-spring duration-400"
                 x-transition:enter-start="-translate-x-full"
                 x-transition:enter-end="translate-x-0"
                 x-transition:leave="transition ease-in duration-250"
                 x-transition:leave-start="translate-x-0"
                 x-transition:leave-end="-translate-x-full"
                 class="absolute inset-y-0 left-0 w-[84%] max-w-[300px] overflow-y-auto bg-[rgb(var(--surface))] p-4 shadow-lift">
                <div class="mb-4 flex justify-end">
                    <button @click="navOpen = false" class="rounded-lg p-2 text-[rgb(var(--text-muted))] hover:bg-[rgb(var(--line)/0.06)]">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
                @include('frontend::include.__user_side_nav')
            </div>
        </div>

        {{-- main --}}
        <main class="min-w-0 flex-1 pb-20">
            {{-- mobile nav trigger --}}
            <button @click="navOpen = true" type="button"
                    class="mb-5 flex items-center gap-2 rounded-xl border border-[rgb(var(--line)/0.1)] bg-[rgb(var(--surface-raised))] px-4 py-2.5 text-sm font-semibold text-[rgb(var(--text-strong))] shadow-soft lg:hidden">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5"/>
                </svg>
                {{ __('Menu') }}
            </button>

            @if(setting('kyc_verification','permission'))
                @include('frontend::user.include.__kyc_info')
            @endif

            @yield('content')
        </main>
    </div>
</div>

@include('frontend::include.__footer')
@include('frontend::cookie.gdpr_cookie')
@include('frontend::include.__script')

</body>
</html>
