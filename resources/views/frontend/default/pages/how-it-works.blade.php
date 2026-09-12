@extends('frontend::pages.index')

@section('title'){{ $data['title'] ?? __('How It Works') }}@endsection
@section('meta_keywords'){{ $data['meta_keywords'] ?? '' }}@endsection
@section('meta_description'){{ $data['meta_description'] ?? '' }}@endsection

@section('page-content')
    @include('frontend::home.include.__howitworks', ['data' => $data])

    @if(!empty($data['section_id']))
        @php $section = \App\Models\LandingPage::find($data['section_id']); @endphp
        @if($section)
            @includeIf('frontend::home.include.__'.$section->code, ['data' => json_decode($section->data, true)])
        @endif
    @endif
@endsection
