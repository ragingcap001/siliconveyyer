@extends('frontend::layouts.user')

@section('title'){{ __('Available Tasks') }}@endsection
@section('subtitle'){{ __('Claim a task, submit your proof, and get paid once it is approved.') }}@endsection

@section('content')

    {{-- filters ------------------------------------------------------------ --}}
    <form action="{{ route('user.task.index') }}" method="get"
          class="mb-6 flex flex-col gap-3 rounded-2xl border border-[rgb(var(--line)/0.09)] bg-[rgb(var(--surface-raised))] p-4 shadow-soft sm:flex-row sm:items-center">

        <div class="relative flex-1">
            <svg class="pointer-events-none absolute left-3.5 top-1/2 h-4 w-4 -translate-y-1/2 text-[rgb(var(--text-muted))]"
                 fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z"/>
            </svg>
            <input type="text" name="query" value="{{ request('query') }}"
                   placeholder="{{ __('Search tasks') }}" class="field pl-10"/>
        </div>

        <select name="category" class="field sm:w-52">
            <option value="">{{ __('All Categories') }}</option>
            @foreach($categories as $category)
                <option value="{{ $category }}" @selected(request('category') === $category)>{{ $category }}</option>
            @endforeach
        </select>

        <button type="submit" class="btn-primary shrink-0">{{ __('Filter') }}</button>

        @if(request()->filled('query') || request()->filled('category'))
            <a href="{{ route('user.task.index') }}" class="btn-ghost btn-sm shrink-0">{{ __('Clear') }}</a>
        @endif
    </form>

    {{-- task grid ----------------------------------------------------------- --}}
    <div class="grid gap-5 sm:grid-cols-2 xl:grid-cols-3">
        @forelse($tasks as $task)
            <article class="group relative flex flex-col rounded-3xl border border-[rgb(var(--line)/0.09)] bg-[rgb(var(--surface-raised))] p-6 shadow-soft transition-all duration-500 ease-spring hover:-translate-y-1 hover:border-brand-500/25 hover:shadow-lift">

                <span class="absolute inset-x-0 top-0 h-0.5 bg-brand-gradient opacity-0 transition-opacity duration-500 group-hover:opacity-100"></span>

                {{-- header --}}
                <div class="flex items-start justify-between gap-3">
                    @if($task->category)
                        <span class="badge-brand">{{ $task->category }}</span>
                    @else
                        <span class="badge-neutral">{{ __('General') }}</span>
                    @endif
                    <span class="font-display text-lg font-bold text-earn-600 dark:text-earn-400">
                        {{ $currencySymbol }}{{ $task->pay_amount }}
                    </span>
                </div>

                <h3 class="mt-4 line-clamp-2 text-base font-semibold leading-snug text-[rgb(var(--text-strong))]">
                    {{ $task->title }}
                </h3>

                <p class="mt-2.5 line-clamp-3 flex-1 text-sm leading-relaxed text-[rgb(var(--text-muted))]">
                    {{ Str::limit(strip_tags($task->description), 140) }}
                </p>

                {{-- requirements --}}
                <ul class="mt-5 space-y-2 border-t border-[rgb(var(--line)/0.07)] pt-4 text-xs text-[rgb(var(--text-muted))]">
                    <li class="flex items-center gap-2">
                        <svg class="h-3.5 w-3.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 0 1 3 19.875v-6.75ZM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V8.625ZM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V4.125Z"/>
                        </svg>
                        {{ __('Level') }} {{ $task->min_level }}+
                    </li>

                    @if($task->require_kyc)
                        <li class="flex items-center gap-2 text-amber-600 dark:text-amber-400">
                            <svg class="h-3.5 w-3.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75m-3-7.036A11.959 11.959 0 0 1 3.598 6 11.99 11.99 0 0 0 3 9.749c0 5.592 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285Z"/>
                            </svg>
                            {{ __('KYC required') }}
                        </li>
                    @endif

                    @if($task->min_balance > 0)
                        <li class="flex items-center gap-2">
                            <svg class="h-3.5 w-3.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182C10.536 7.28 11.768 7 12 7s1.464.28 2.121.659"/>
                            </svg>
                            {{ __('Min balance') }} {{ $currencySymbol }}{{ $task->min_balance }}
                        </li>
                    @endif

                    <li class="flex items-center gap-2">
                        <svg class="h-3.5 w-3.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z"/>
                        </svg>
                        @if($task->hasUnlimitedSlots())
                            {{ __('Unlimited takers') }}
                        @else
                            {{ $task->slotsRemaining() }} {{ __('slots left') }}
                        @endif
                    </li>

                    @if($task->expires_at)
                        <li class="flex items-center gap-2">
                            <svg class="h-3.5 w-3.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                            </svg>
                            {{ __('Ends') }} {{ $task->expires_at->format('d M Y') }}
                        </li>
                    @endif
                </ul>

                {{-- status / action --}}
                @if($task->my_submission)
                    <div class="mt-4 rounded-xl border border-[rgb(var(--line)/0.08)] bg-[rgb(var(--surface-muted))] px-3.5 py-3 text-center text-xs font-semibold">
                        {{ __('You submitted this') }}:
                        <span class="{{ $task->my_submission->status->color() === 'success' ? 'text-earn-600 dark:text-earn-400'
                                      : ($task->my_submission->status->color() === 'danger' ? 'text-rose-600 dark:text-rose-400'
                                      : 'text-amber-600 dark:text-amber-400') }}">
                            {{ $task->my_submission->status->label() }}
                        </span>
                    </div>
                @endif

                <a href="{{ route('user.task.show', $task->id) }}"
                   class="mt-4 {{ $task->eligibility_error ? 'btn-outline btn-sm btn-block' : 'btn-primary btn-sm btn-block' }}">
                    {{ $task->eligibility_error ? __('View Details') : __('View & Take Task') }}
                </a>

                @if($task->eligibility_error)
                    <p class="mt-2.5 text-center text-[0.7rem] leading-snug text-amber-600 dark:text-amber-400">
                        {{ $task->eligibility_error }}
                    </p>
                @endif
            </article>
        @empty
            <div class="sm:col-span-2 xl:col-span-3">
                <div class="flex flex-col items-center rounded-3xl border border-dashed border-[rgb(var(--line)/0.16)] bg-[rgb(var(--surface-raised))] px-6 py-20 text-center">
                    <span class="flex h-14 w-14 items-center justify-center rounded-2xl bg-brand-500/10 text-brand-600 dark:text-brand-300">
                        <svg class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke-width="1.6" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z"/>
                        </svg>
                    </span>
                    <h3 class="mt-5 text-lg font-semibold text-[rgb(var(--text-strong))]">{{ __('No tasks match') }}</h3>
                    <p class="mt-2 max-w-sm text-sm text-[rgb(var(--text-muted))]">
                        {{ __('Try a different search, or check back soon as new tasks are published regularly.') }}
                    </p>
                </div>
            </div>
        @endforelse
    </div>

    @if($tasks->hasPages())
        <div class="mt-8">{{ $tasks->links() }}</div>
    @endif

@endsection
