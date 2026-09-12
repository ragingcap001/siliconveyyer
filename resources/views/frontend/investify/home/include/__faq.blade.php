@php
$landingContent = \App\Models\LandingContent::where('type','faq')->where('locale',app()->getLocale())->get();
@endphp

<section class="section relative overflow-hidden">
    <div class="glow-blob -right-40 top-20 h-96 w-96 opacity-40"></div>

    <div class="shell relative">
        <div class="grid gap-12 lg:grid-cols-12 lg:gap-16">

            {{-- Heading column --}}
            <div class="lg:col-span-5" data-reveal>
                @if(!empty($data['title_small']))
                    <span class="eyebrow">{{ $data['title_small'] }}</span>
                @endif
                <h2 class="section-title">{{ $data['title_big'] ?? '' }}</h2>
                <p class="section-lede">
                    {{ __('Everything about investing, withdrawals and account security. Still stuck? Reach the team and we will answer directly.') }}
                </p>

                <a href="{{ route('page', 'contact') }}" class="btn-outline mt-8">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0L3.32 8.91a2.25 2.25 0 0 1-1.07-1.916V6.75"/>
                    </svg>
                    {{ __('Contact support') }}
                </a>
            </div>

            {{-- Accordion --}}
            <div class="lg:col-span-7" x-data="{ active: 0 }" data-reveal data-reveal-delay="100">
                <div class="space-y-3">
                    @foreach($landingContent as $content)
                        <div class="overflow-hidden rounded-2xl border border-[rgb(var(--line)/0.09)] bg-[rgb(var(--surface-raised))] transition-all duration-300"
                             :class="active === {{ $loop->index }} ? 'border-brand-500/30 shadow-card' : ''">

                            <button type="button"
                                    @click="active = active === {{ $loop->index }} ? null : {{ $loop->index }}"
                                    class="flex w-full items-center justify-between gap-4 px-5 py-5 text-left transition-colors hover:bg-[rgb(var(--line)/0.03)]">
                                <span class="flex items-start gap-3.5">
                                    <span class="mt-0.5 flex h-6 w-6 shrink-0 items-center justify-center rounded-lg text-xs font-bold transition-colors duration-300"
                                          :class="active === {{ $loop->index }} ? 'bg-brand-500 text-white' : 'bg-[rgb(var(--line)/0.07)] text-[rgb(var(--text-muted))]'">
                                        {{ $loop->iteration }}
                                    </span>
                                    <span class="text-[0.95rem] font-semibold text-[rgb(var(--text-strong))]">{{ $content->title }}</span>
                                </span>

                                <svg class="h-5 w-5 shrink-0 text-[rgb(var(--text-muted))] transition-transform duration-300"
                                     :class="active === {{ $loop->index }} && 'rotate-45'"
                                     fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
                                </svg>
                            </button>

                            <div x-show="active === {{ $loop->index }}"
                                 x-collapse x-cloak
                                 class="border-t border-[rgb(var(--line)/0.07)]">
                                <div class="px-5 py-5 pl-14 text-sm leading-relaxed text-[rgb(var(--text-muted))]">
                                    {!! nl2br(e($content->description)) !!}
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>
