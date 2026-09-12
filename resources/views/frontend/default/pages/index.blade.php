@extends('frontend::layouts.app')

@section('content')

    {{-- page head --}}
    <section class="relative overflow-hidden border-b border-[rgb(var(--line)/0.08)] bg-[rgb(var(--surface-muted))] pb-16 pt-12 lg:pb-20 lg:pt-16">
        <div class="pointer-events-none absolute inset-0 grid-canvas"></div>
        <div class="glow-blob -top-32 left-1/2 h-72 w-72 -translate-x-1/2 opacity-70"></div>

        @php $breadcrumb = getPageSetting('breadcrumb'); @endphp
        @if($breadcrumb)
            <img src="{{ asset($breadcrumb) }}" alt=""
                 class="pointer-events-none absolute inset-0 h-full w-full object-cover opacity-[0.07]"/>
        @endif

        <div class="shell relative text-center">
            <h1 class="text-3xl font-bold tracking-tight text-[rgb(var(--text-strong))] sm:text-4xl lg:text-[2.75rem]">
                @yield('title')
            </h1>

            <nav class="mt-4 flex items-center justify-center gap-2 text-sm text-[rgb(var(--text-muted))]">
                <a href="{{ route('home') }}" class="transition-colors hover:text-brand-600 dark:hover:text-brand-300">{{ __('Home') }}</a>
                <span class="text-[rgb(var(--line)/0.35)]">/</span>
                <span class="text-[rgb(var(--text-strong))]">@yield('title')</span>
            </nav>
        </div>
    </section>

    @yield('page-content')

@endsection
