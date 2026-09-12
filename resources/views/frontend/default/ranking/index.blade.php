@extends('frontend::layouts.user')

@section('title'){{ __('All The Badges') }}@endsection
@section('subtitle'){{ __('Complete tasks and grow your earnings to unlock the next level.') }}@endsection

@section('content')
    <div class="grid gap-5 sm:grid-cols-2 xl:grid-cols-4">
        @foreach($rankings as $ranking)
            @php $locked = !in_array($ranking->id, (array) $alreadyRank); @endphp

            <div data-reveal data-reveal-delay="{{ min($loop->index * 80, 320) }}"
                 class="group relative overflow-hidden rounded-3xl border p-7 text-center shadow-soft transition-all duration-500 ease-spring
                        {{ $locked
                            ? 'border-[rgb(var(--line)/0.07)] bg-[rgb(var(--surface-muted))] opacity-60'
                            : 'border-[rgb(var(--line)/0.09)] bg-[rgb(var(--surface-raised))] hover:-translate-y-1.5 hover:border-brand-500/25 hover:shadow-lift' }}">

                @unless($locked)
                    <span class="absolute inset-x-0 top-0 h-0.5 bg-brand-gradient opacity-0 transition-opacity duration-500 group-hover:opacity-100"></span>
                @endunless

                <span class="relative mx-auto flex h-20 w-20 items-center justify-center rounded-2xl bg-[rgb(var(--surface-muted))] p-4">
                    <img src="{{ asset($ranking->icon) }}" alt="" class="h-full w-full object-contain"/>
                    @if($locked)
                        <span class="absolute inset-0 flex items-center justify-center rounded-2xl bg-[rgb(var(--surface)/0.6)]">
                            <svg class="h-6 w-6 text-[rgb(var(--text-muted))]" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z"/>
                            </svg>
                        </span>
                    @endif
                </span>

                <h3 class="mt-5 text-lg font-semibold text-[rgb(var(--text-strong))]">{{ $ranking->ranking_name }}</h3>
                <p class="mt-1 text-xs font-semibold uppercase tracking-wider text-brand-600 dark:text-brand-300">{{ $ranking->ranking }}</p>
                <p class="mt-3 text-sm leading-relaxed text-[rgb(var(--text-muted))]">{{ $ranking->description }}</p>

                <div class="mt-5 flex items-center justify-center gap-4 border-t border-[rgb(var(--line)/0.07)] pt-4 text-xs">
                    <span class="text-[rgb(var(--text-muted))]">
                        {{ __('Tasks') }} <b class="text-[rgb(var(--text-strong))]">{{ $ranking->minimum_tasks ?? 0 }}</b>
                    </span>
                    <span class="h-3 w-px bg-[rgb(var(--line)/0.15)]"></span>
                    <span class="text-[rgb(var(--text-muted))]">
                        {{ __('Earned') }} <b class="text-[rgb(var(--text-strong))]">{{ $currencySymbol }}{{ $ranking->minimum_task_earning ?? 0 }}</b>
                    </span>
                </div>
            </div>
        @endforeach
    </div>
@endsection
