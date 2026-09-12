<!DOCTYPE html>
<html lang="{{ str_replace('_','-',app()->getLocale()) }}">
@include('frontend::include.__head')

<body class="min-h-screen bg-[rgb(var(--surface))] font-sans text-[rgb(var(--text-body))] antialiased">

<x:notify-messages/>

<div class="flex min-h-screen">

    {{-- ============ form side ============ --}}
    <div class="flex w-full flex-col justify-center px-5 py-12 sm:px-8 lg:w-1/2 lg:px-16 xl:px-24">

        <div class="mx-auto w-full max-w-md">
            <a href="{{ route('home') }}" class="inline-block">
                <img src="{{ asset(setting('site_logo','global')) }}" alt="{{ setting('site_title','global') }}"
                     class="h-9 w-auto max-w-[170px] object-contain"/>
            </a>

            <div class="mt-10">
                @yield('content')
            </div>
        </div>
    </div>

    {{-- ============ art side ============ --}}
    <div class="relative hidden overflow-hidden lg:block lg:w-1/2">
        <div class="absolute inset-0 bg-brand-950">
            <div class="pointer-events-none absolute inset-0 opacity-[0.15]"
                 style="background-image: linear-gradient(to right, #fff 1px, transparent 1px), linear-gradient(to bottom, #fff 1px, transparent 1px); background-size: 48px 48px;"></div>
            <div class="pointer-events-none absolute -right-20 top-1/4 h-96 w-96 rounded-full blur-3xl"
                 style="background: radial-gradient(circle, rgba(20,184,166,.4) 0%, transparent 70%)"></div>
            <div class="pointer-events-none absolute -bottom-24 -left-20 h-96 w-96 rounded-full blur-3xl"
                 style="background: radial-gradient(circle, rgba(91,97,248,.5) 0%, transparent 70%)"></div>

            <div class="relative flex h-full flex-col justify-center px-14 xl:px-20">

                <h2 class="text-3xl font-bold leading-tight tracking-tight text-white xl:text-4xl">
                    {{ __('Get paid for small jobs, done well.') }}
                </h2>
                <p class="mt-4 max-w-md text-[0.95rem] leading-relaxed text-white/65">
                    {{ __('Browse the task board, claim what suits you, send your proof and get paid once an admin approves it.') }}
                </p>

                <ul class="mt-10 space-y-4">
                    @foreach([
                        __('Clear instructions on every task'),
                        __('Proof reviewed by a real person'),
                        __('Paid out the moment you are approved'),
                    ] as $point)
                        <li class="flex items-center gap-3.5">
                            <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-xl bg-white/10 text-white">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2.4" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5"/>
                                </svg>
                            </span>
                            <span class="text-sm font-medium text-white/85">{{ $point }}</span>
                        </li>
                    @endforeach
                </ul>

                {{-- decorative task card --}}
                <div class="mt-12 max-w-sm rounded-2xl border border-white/10 bg-white/[0.06] p-5 backdrop-blur">
                    <div class="flex items-center justify-between">
                        <span class="text-[0.65rem] font-semibold uppercase tracking-[0.14em] text-white/50">{{ __('Sample task') }}</span>
                        <span class="rounded-lg bg-earn-500/20 px-2.5 py-1 text-sm font-bold text-earn-300">$3.00</span>
                    </div>
                    <p class="mt-3 text-sm font-semibold text-white">{{ __('Write a 300 word review of the platform') }}</p>
                    <div class="mt-3 flex items-center gap-2 text-xs text-white/55">
                        <span class="rounded-md bg-white/10 px-2 py-0.5">{{ __('Level 2') }}</span>
                        <span class="rounded-md bg-white/10 px-2 py-0.5">{{ __('Text proof') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@include('frontend::include.__script')

</body>
</html>
