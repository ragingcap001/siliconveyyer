<!DOCTYPE html>
<html lang="{{ str_replace('_','-',app()->getLocale()) }}">

@include('frontend::include.__head')

<body class="min-h-screen bg-[#f7f6f3] font-sans text-slate-700 antialiased">

    <x:notify-messages/>

    @include('frontend::include.__header')

    <div x-data="{ navOpen: false }"
         class="min-h-screen pt-[72px]">

        <div class="flex">

            {{-- Sidebar --}}
            @include('frontend::include.__user_side_nav')

            {{-- Mobile drawer --}}
            <div x-show="navOpen"
                 x-cloak
                 class="fixed inset-0 z-40 md:hidden">

                <div x-show="navOpen"
                     x-transition.opacity
                     class="absolute inset-0 bg-slate-950/50"
                     @click="navOpen = false">
                </div>

                <div x-show="navOpen"
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="-translate-x-full"
                     x-transition:enter-end="translate-x-0"
                     x-transition:leave="transition ease-in duration-200"
                     x-transition:leave-start="translate-x-0"
                     x-transition:leave-end="-translate-x-full"
                     class="absolute inset-y-0 left-0 w-[280px] overflow-y-auto bg-[#071a2b] p-4 shadow-xl">

                    <div class="flex justify-end mb-4">
                        <button @click="navOpen = false"
                                class="p-2 rounded-lg text-white/60 hover:bg-white/10 hover:text-white">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>

                    @include('frontend::include.__user_side_nav')
                </div>
            </div>

            {{-- Main --}}
            <main class="flex-1 min-w-0">

                {{-- Mobile menu button --}}
                <div class="px-4 pt-5 md:hidden">
                    <button @click="navOpen = true"
                            type="button"
                            class="inline-flex items-center gap-2 border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5"/>
                        </svg>
                        {{ __('Menu') }}
                    </button>
                </div>

                @if(setting('kyc_verification','permission'))
                    @include('frontend::user.include.__kyc_info')
                @endif

                <div class="px-4 py-6 sm:px-6 lg:px-8 xl:px-10">
                    @yield('content')
                </div>

            </main>

        </div>
    </div>

    @include('frontend::include.__footer')
    @include('frontend::cookie.gdpr_cookie')
    @include('frontend::include.__script')

</body>
</html>
