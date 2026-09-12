<div class="grid gap-4 lg:grid-cols-12">

    {{-- rank badge --}}
    <div class="lg:col-span-4 xl:col-span-3">
        <div class="relative overflow-hidden rounded-3xl border border-white/10 bg-brand-950 p-6 text-center">
            <div class="pointer-events-none absolute -right-12 -top-12 h-36 w-36 rounded-full blur-2xl"
                 style="background: radial-gradient(circle, rgba(20,184,166,.45) 0%, transparent 70%)"></div>
            <div class="pointer-events-none absolute inset-0 opacity-[0.12]"
                 style="background-image: linear-gradient(to right, #fff 1px, transparent 1px), linear-gradient(to bottom, #fff 1px, transparent 1px); background-size: 34px 34px;"></div>

            <div class="relative">
                @if($user->rank?->icon)
                    <img src="{{ asset($user->rank->icon) }}" alt="" class="mx-auto h-14 w-14 object-contain"
                         title="{{ $user->rank->description }}"/>
                @endif

                <p class="mt-4 font-display text-xl font-bold text-white">{{ $user->rank?->ranking }}</p>
                <p class="mt-1 text-sm text-white/70">{{ $user->rank?->ranking_name }}</p>

                @if($user->rank?->description)
                    <p class="mt-3 text-xs leading-relaxed text-white/50">{{ $user->rank->description }}</p>
                @endif
            </div>
        </div>
    </div>

    {{-- referral link --}}
    @if(setting('sign_up_referral','permission'))
        <div class="lg:col-span-8 xl:col-span-9">
            <div class="flex h-full flex-col justify-center rounded-3xl border border-[rgb(var(--line)/0.09)] bg-[rgb(var(--surface-raised))] p-6 shadow-soft">

                <div class="flex items-center gap-2.5">
                    <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-brand-500/10 text-brand-600 dark:text-brand-300">
                        <svg class="h-4.5 w-4.5" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.19 8.688a4.5 4.5 0 0 1 1.242 7.244l-4.5 4.5a4.5 4.5 0 0 1-6.364-6.364l1.757-1.757m13.35-.622 1.757-1.757a4.5 4.5 0 0 0-6.364-6.364l-4.5 4.5a4.5 4.5 0 0 0 1.242 7.244"/>
                        </svg>
                    </span>
                    <h3 class="text-base font-semibold text-[rgb(var(--text-strong))]">{{ __('Referral URL') }}</h3>
                </div>

                <div class="mt-4 flex flex-col gap-2 sm:flex-row">
                    <input type="text" id="refLink" value="{{ $referral?->link ?? '' }}" readonly class="field flex-1 font-mono text-xs"/>
                    <button type="button" onclick="copyRef()" class="btn-primary shrink-0">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.666 3.888A2.25 2.25 0 0 0 13.5 2.25h-3c-1.03 0-1.9.693-2.166 1.638m7.332 0c.055.194.084.4.084.612v0a.75.75 0 0 1-.75.75H9a.75.75 0 0 1-.75-.75v0c0-.212.03-.418.084-.612m7.332 0c.646.049 1.288.11 1.927.184 1.1.128 1.907 1.077 1.907 2.185V19.5a2.25 2.25 0 0 1-2.25 2.25H6.75A2.25 2.25 0 0 1 4.5 19.5V6.257c0-1.108.806-2.057 1.907-2.185a48.208 48.208 0 0 1 1.927-.184"/>
                        </svg>
                        <span id="copy">{{ __('Copy') }}</span>
                    </button>
                </div>

                <p class="mt-3 text-sm text-[rgb(var(--text-muted))]">
                    <span class="font-semibold text-[rgb(var(--text-strong))]">{{ $referral?->relationships()->count() ?? 0 }}</span>
                    {{ __('peoples are joined by using this URL') }}
                </p>
            </div>
        </div>
    @endif
</div>
