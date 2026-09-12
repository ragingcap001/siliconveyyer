@extends('frontend::layouts.user')

@section('title'){{ __('Dashboard') }}@endsection
@section('subtitle'){{ __('Your earnings, tasks and activity at a glance.') }}@endsection

@section('content')
    <div class="space-y-6">

        {{-- stat cards --}}
        @include('frontend::user.include.__user_card')

        {{-- available tasks --}}
        @if($availableTasks->isNotEmpty())
            <div class="overflow-hidden rounded-3xl border border-[rgb(var(--line)/0.09)] bg-[rgb(var(--surface-raised))] shadow-soft">
                <div class="flex items-center justify-between border-b border-[rgb(var(--line)/0.07)] px-6 py-5">
                    <h3 class="text-base font-semibold text-[rgb(var(--text-strong))]">{{ __('Available Tasks') }}</h3>
                    <a href="{{ route('user.task.index') }}" class="link-arrow">{{ __('View all') }}</a>
                </div>

                <div class="divide-y divide-[rgb(var(--line)/0.06)]">
                    @foreach($availableTasks as $task)
                        <div class="flex flex-col gap-3 p-5 sm:flex-row sm:items-center sm:justify-between">
                            <div class="min-w-0 flex-1">
                                <p class="truncate text-sm font-semibold text-[rgb(var(--text-strong))]">{{ $task->title }}</p>
                                <div class="mt-1.5 flex flex-wrap items-center gap-2 text-xs text-[rgb(var(--text-muted))]">
                                    <span class="inline-flex items-center gap-1 rounded-full bg-earn-500/10 px-2 py-0.5 font-semibold text-earn-600 dark:text-earn-400">
                                        {{ $currencySymbol }}{{ number_format((float) $task->pay_amount, 2) }}
                                    </span>
                                    @if($task->total_slots)
                                        <span>{{ $task->slotsRemaining() === -1 ? __('Unlimited') : $task->slotsRemaining().' '.__('slots left') }}</span>
                                    @endif
                                    @if($task->expires_at)
                                        <span>{{ __('Expires :date', ['date' => $task->expires_at->diffForHumans()]) }}</span>
                                    @endif
                                </div>
                            </div>
                            <a href="{{ route('user.task.show', $task->id) }}"
                               class="btn btn-sm bg-brand-500/10 text-brand-700 hover:bg-brand-500/20 dark:text-brand-300">
                                {{ __('View') }}
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        {{-- recent earnings --}}
        @include('frontend::user.include.__recent_transaction')
    </div>
@endsection
