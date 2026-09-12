@php
    // Headings come from the landing data the admin edits; the cards are the
    // newest tasks that are actually open right now.
    $featuredTasks = \App\Models\Task::open()->latest()->take(4)->get();
@endphp

<section class="section relative overflow-hidden">
    <div class="pointer-events-none absolute inset-0 grid-flat opacity-40"></div>

    <div class="shell relative">
        {{-- section head --}}
        <div class="flex flex-col gap-6 md:flex-row md:items-end md:justify-between" data-reveal>
            <div class="max-w-2xl">
                @if(!empty($data['title_small']))
                    <span class="eyebrow">{{ $data['title_small'] }}</span>
                @endif
                <h2 class="section-title">{{ $data['title_big'] ?? '' }}</h2>
            </div>

            <a href="{{ route('user.task.index') }}" class="link-arrow shrink-0">
                {{ __('Browse all tasks') }}
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3"/>
                </svg>
            </a>
        </div>

        {{-- task grid --}}
        <div class="mt-12 grid gap-5 sm:grid-cols-2 lg:grid-cols-4">
            @forelse($featuredTasks as $task)
                <article data-reveal data-reveal-delay="{{ $loop->index * 90 }}"
                         class="group relative flex flex-col overflow-hidden rounded-3xl border border-[rgb(var(--line)/0.09)] bg-[rgb(var(--surface-raised))] p-6 shadow-soft transition-all duration-500 ease-spring hover:-translate-y-1.5 hover:border-brand-500/30 hover:shadow-lift">

                    {{-- top accent line --}}
                    <span class="absolute inset-x-0 top-0 h-0.5 bg-brand-gradient opacity-0 transition-opacity duration-500 group-hover:opacity-100"></span>

                    <div class="flex items-start justify-between gap-3">
                        @if(!empty($task->category))
                            <span class="badge-brand">{{ $task->category }}</span>
                        @else
                            <span class="badge-neutral">{{ __('Task') }}</span>
                        @endif

                        <span class="badge {{ $task->require_kyc ? 'badge-warn' : 'badge-neutral' }}"
                              title="{{ $task->require_kyc ? __('KYC required') : __('Open to level :level and above', ['level' => $task->min_level]) }}">
                            {{ $task->require_kyc ? __('KYC') : __('L:level', ['level' => $task->min_level]) }}
                        </span>
                    </div>

                    <h3 class="mt-4 line-clamp-2 text-base font-semibold leading-snug text-[rgb(var(--text-strong))]">
                        {{ $task->title }}
                    </h3>

                    <p class="mt-2.5 line-clamp-3 text-sm leading-relaxed text-[rgb(var(--text-muted))]">
                        {{ Str::limit(strip_tags($task->description), 110) }}
                    </p>

                    <div class="mt-5 flex items-center gap-4 border-t border-[rgb(var(--line)/0.07)] pt-4">
                        <div>
                            <p class="text-[0.65rem] font-semibold uppercase tracking-wider text-[rgb(var(--text-muted))]">{{ __('Pay') }}</p>
                            <p class="font-display text-xl font-bold text-earn-600 dark:text-earn-400">
                                {{ $currencySymbol }}{{ $task->pay_amount }}
                            </p>
                        </div>
                        <div class="ml-auto text-right">
                            <p class="text-[0.65rem] font-semibold uppercase tracking-wider text-[rgb(var(--text-muted))]">{{ __('Slots') }}</p>
                            <p class="font-display text-xl font-bold text-[rgb(var(--text-strong))]">
                                {{ $task->hasUnlimitedSlots() ? '∞' : $task->slotsRemaining() }}
                            </p>
                        </div>
                    </div>

                    <a href="{{ route('user.task.show', $task->id) }}"
                       class="btn-secondary btn-sm btn-block mt-5">
                        {{ __('View task') }}
                        <svg class="h-4 w-4 transition-transform duration-300 group-hover:translate-x-0.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3"/>
                        </svg>
                    </a>
                </article>
            @empty
                <div class="col-span-full">
                    <div class="flex flex-col items-center rounded-3xl border border-dashed border-[rgb(var(--line)/0.16)] bg-[rgb(var(--surface-muted))] px-6 py-16 text-center">
                        <span class="flex h-14 w-14 items-center justify-center rounded-2xl bg-brand-500/10 text-brand-600 dark:text-brand-300">
                            <svg class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke-width="1.6" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 14.15v4.25c0 1.094-.787 2.036-1.872 2.18-2.087.277-4.216.42-6.378.42s-4.291-.143-6.378-.42c-1.085-.144-1.872-1.086-1.872-2.18v-4.25m16.5 0a2.18 2.18 0 0 0 .75-1.65V8.706c0-1.081-.768-2.015-1.837-2.175a48.114 48.114 0 0 0-3.413-.387m4.5 5.006c-.194.165-.42.295-.673.38A23.978 23.978 0 0 1 12 11.5c-2.38 0-4.68.27-6.9.774a2.16 2.16 0 0 1-.673-.38m13.453 0A48.108 48.108 0 0 0 12 11.5c-2.38 0-4.68.27-6.9.774M3.75 14.15c-.28.24-.593.435-.93.573"/>
                            </svg>
                        </span>
                        <h3 class="mt-5 text-lg font-semibold text-[rgb(var(--text-strong))]">{{ __('No tasks are open right now') }}</h3>
                        <p class="mt-2 max-w-sm text-sm text-[rgb(var(--text-muted))]">
                            {{ __('New tasks are published regularly. Check back shortly or subscribe below and we will tell you when they land.') }}
                        </p>
                    </div>
                </div>
            @endforelse
        </div>
    </div>
</section>
