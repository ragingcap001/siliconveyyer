@extends('backend.layouts.app')
@section('title')
    {{ __('Featured Tasks Section') }}
@endsection
@section('content')
    <div class="main-content">
        <div class="page-title">
            <div class="container-fluid">
                <div class="row justify-content-center">
                    <div class="col-xl-12">
                        <div class="title-content">
                            <h2 class="title">{{ __('Featured Tasks Section') }}</h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="site-tab-bars">
            <ul class="nav nav-pills" id="pills-tab" role="tablist">
                @foreach($languages as $language)
                    <li class="nav-item" role="presentation">
                        <a
                            href=""
                            class="nav-link  {{ $loop->index == 0 ?'active' : '' }}"
                            id="pills-informations-tab"
                            data-bs-toggle="pill"
                            data-bs-target="#{{$language->locale}}"
                            type="button"
                            role="tab"
                            aria-controls="pills-informations"
                            aria-selected="true"
                        ><i icon-name="languages"></i>{{$language->name}}</a
                        >
                    </li>
                @endforeach
            </ul>
        </div>

        <div class="tab-content" id="pills-tabContent">

            @foreach($groupData as $key => $value)

                @php
                    $data = new Illuminate\Support\Fluent($value);
                @endphp

                <div
                    class="tab-pane fade {{ $loop->index == 0 ?'show active' : '' }}"
                    id="{{$key}}"
                    role="tabpanel"
                    aria-labelledby="pills-informations-tab"
                >

                    <div class="row">
                        <div class="col-xl-12">
                            <div class="site-card">
                                <div class="site-card-header">
                                    <h3 class="title">{{ __('Contents') }}</h3>
                                </div>
                                <div class="site-card-body">

                                    <form action="{{ route('admin.page.section.section.update') }}" method="post"
                                          enctype="multipart/form-data">
                                        @csrf
                                        <input type="hidden" name="section_code" value="schema">
                                        <input type="hidden" name="section_locale" value="{{ $key }}">

                                        @if($key == 'en')
                                            <div class="site-input-groups row">
                                                <label for=""
                                                       class="col-sm-3 col-label pt-0">{{ __('Section Activity') }}<i
                                                        icon-name="info" data-bs-toggle="tooltip" title=""
                                                        data-bs-original-title="Manage Section Visibility"></i></label>
                                                <div class="col-sm-3">
                                                    <div class="site-input-groups">
                                                        <div class="switch-field">
                                                            <input type="radio" id="active" name="status"
                                                                   @if($status) checked
                                                                   @endif value="1"/>
                                                            <label for="active">{{ __('Show') }}</label>
                                                            <input type="radio" id="deactivate" name="status"
                                                                   @if(!$status) checked
                                                                   @endif value="0"/>
                                                            <label for="deactivate">{{ __('Hide') }}</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif

                                        <div class="site-input-groups row">
                                            <label for=""
                                                   class="col-sm-3 col-label">{{ __('Title Small') }}</label>
                                            <div class="col-sm-9">
                                                <input type="text" name="title_small" class="box-input"
                                                       value="{{ $data['title_small'] }}">
                                            </div>
                                        </div>
                                        <div class="site-input-groups row">
                                            <label for=""
                                                   class="col-sm-3 col-label">{{ __('Title Big') }}</label>
                                            <div class="col-sm-9">
                                                <input type="text" name="title_big" class="box-input"
                                                       value="{{ $data['title_big'] }}">
                                            </div>
                                        </div>

                                        @if($key == 'en')
                                            <div class="site-input-groups row">
                                                <div class="col-xl-3 col-lg-3 col-md-3 col-sm-12 col-label">
                                                    {{ __('Background Image') }}
                                                </div>
                                                <div class="col-xl-9 col-lg-9 col-md-9 col-sm-12">
                                                    <div class="wrap-custom-file">
                                                        <input type="file" name="left_top_img" id="leftTopImg"
                                                               accept=".gif, .jpg, .png"/>
                                                        <label for="leftTopImg" id="left_top_img"
                                                               @if($data->left_top_img)  class="file-ok"
                                                               style="background-image: url({{ asset($data['left_top_img']) }})" @endif>
                                                            <img class="upload-icon"
                                                                 src="{{ asset('global/materials/upload.svg') }}"
                                                                 alt=""/>
                                                            <span>{{ __('Update Image') }}</span>
                                                        </label>
                                                        @removeimg($data->left_top_img,left_top_img)
                                                    </div>
                                                </div>
                                            </div>
                                        @endif

                                        <div class="row">
                                            <div class="offset-sm-3 col-sm-9">
                                                <button type="submit"
                                                        class="site-btn-sm primary-btn w-100">{{ __('Save Changes') }}</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            @endforeach

            <div class="row">
                <div class="col-xl-12">
                    <div class="site-card">
                        <div class="site-card-header">
                            <h3 class="title">{{ __('Note') }}</h3>
                        </div>
                        <div class="site-card-body">
                            <p class="paragraph">
                                {{ __('The task cards below the headings are filled in automatically from the newest open tasks. Only the headings and the background image are edited here.') }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

@endsection
@section('script')
    @include('backend.page.section.include.__section_image_remove')
@endsection
