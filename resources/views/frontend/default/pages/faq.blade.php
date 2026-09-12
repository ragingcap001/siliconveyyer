@extends('frontend::pages.index')

@section('title'){{ $data['title'] ?? __('FAQ') }}@endsection
@section('meta_keywords'){{ $data['meta_keywords'] ?? '' }}@endsection
@section('meta_description'){{ $data['meta_description'] ?? '' }}@endsection

@section('page-content')
    @include('frontend::home.include.__faq', ['data' => $data])

    {{-- contact prompt under the questions --}}
    @if(!empty($data['button_level']) && !empty($data['button_url']))
        <section class="section-tight">
            <div class="shell text-center" data-reveal>
                <a href="{{ $data['button_url'] }}" target="{{ $data['button_target'] ?? '_self' }}" class="btn-primary btn-lg">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0L3.32 8.91a2.25 2.25 0 0 1-1.07-1.916V6.75"/>
                    </svg>
                    {{ $data['button_level'] }}
                </a>
            </div>
        </section>
    @endif

    {{-- an optional landing section attached to this page --}}
    @if(!empty($data->section_id))
        @php $section = \App\Models\LandingPage::find($data->section_id); @endphp
        @if($section)
            @includeIf('frontend::home.include.__'.$section->code, ['data' => json_decode($section->data, true)])
        @endif
    @endif
@endsection
