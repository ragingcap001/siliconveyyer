@php
    $gateways = \App\Models\Gateway::where('status', true)->pluck('logo', 'name');
@endphp

<section class="section-tight relative overflow-hidden">
    <div class="shell relative">
        <div class="section-head-center" data-reveal>
            @if(!empty($data['title_small']))
                <span class="eyebrow">{{ $data['title_small'] }}</span>
            @endif
            <h2 class="section-title">{{ $data['title_big'] ?? '' }}</h2>
            <p class="section-lede mx-auto">
                {{ __('Get paid through any payment method the platform supports.') }}
            </p>
        </div>

        <div class="mt-12 grid grid-cols-2 gap-4 sm:grid-cols-3 lg:grid-cols-6">
            @foreach($gateways as $name => $logo)
                <div data-reveal data-reveal-delay="{{ min($loop->index * 60, 400) }}"
                     class="group flex items-center justify-center rounded-2xl border border-[rgb(var(--line)/0.09)] bg-[rgb(var(--surface-raised))] p-6 shadow-soft transition-all duration-500 ease-spring hover:-translate-y-1 hover:border-brand-500/25 hover:shadow-card"
                     title="{{ $name }}">
                    <img src="{{ asset($logo) }}" alt="{{ $name }}"
                         class="h-9 w-auto max-w-full object-contain opacity-70 transition-opacity duration-300 group-hover:opacity-100"/>
                </div>
            @endforeach
        </div>
    </div>
</section>
