@extends('frontend::pages.index')

@section('title'){{ $data['title'] ?? __('Privacy Policy') }}@endsection
@section('meta_keywords'){{ $data['meta_keywords'] ?? '' }}@endsection
@section('meta_description'){{ $data['meta_description'] ?? '' }}@endsection

@section('page-content')
    <section class="section relative overflow-hidden">
        <div class="shell relative">
            <div class="frontend-editor-data mx-auto max-w-3xl" data-reveal>
                {!! $data['content'] !!}
            </div>
        </div>
    </section>
@endsection
