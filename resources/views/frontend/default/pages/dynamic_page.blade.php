@extends('frontend::pages.index')

@section('title'){{ $data->title }}@endsection
@section('meta_keywords'){{ $data->meta_keywords ?? '' }}@endsection
@section('meta_description'){{ $data->meta_description ?? '' }}@endsection

@section('page-content')
    <section class="section relative overflow-hidden">
        <div class="shell relative">
            <div class="frontend-editor-data mx-auto max-w-3xl" data-reveal>
                {!! $data->content !!}
            </div>
        </div>
    </section>

    {{-- an optional landing section attached to this page --}}
    @if(!empty($data->section_id))
        @php
            $section = \App\Models\LandingPage::where('short', $data->section_id)
                        ->where('locale', app()->getLocale())->first();
        @endphp

        @if($section)
            @includeIf('frontend::home.include.__'.$section->code, ['data' => json_decode($section->data, true)])
        @endif
    @endif
@endsection
