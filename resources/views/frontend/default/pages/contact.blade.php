@extends('frontend::pages.index')

@section('title'){{ $data['title'] }}@endsection
@section('meta_keywords'){{ $data['meta_keywords'] }}@endsection
@section('meta_description'){{ $data['meta_description'] }}@endsection

@section('page-content')
    <section class="section relative overflow-hidden">
        <div class="glow-blob -left-40 top-20 h-96 w-96 opacity-40"></div>

        <div class="shell relative">
            <div class="section-head-center" data-reveal>
                @if(!empty($data['title_small']))
                    <span class="eyebrow">{{ $data['title_small'] }}</span>
                @endif
                <h2 class="section-title">{{ $data['title_big'] ?? '' }}</h2>
            </div>

            <div class="mx-auto mt-12 max-w-2xl">
                <form action="{{ route('mail-send') }}" method="post"
                      class="rounded-3xl border border-[rgb(var(--line)/0.09)] bg-[rgb(var(--surface-raised))] p-6 shadow-soft sm:p-8"
                      data-reveal data-reveal-delay="100">
                    @csrf

                    <div class="space-y-5">
                        <div>
                            <label class="field-label" for="name">{{ __('Name') }}</label>
                            <input type="text" id="name" name="name" required class="field" placeholder="{{ __('Your name') }}"/>
                        </div>

                        <div class="grid gap-5 sm:grid-cols-2">
                            <div>
                                <label class="field-label" for="email">{{ __('Email') }}</label>
                                <input type="email" id="email" name="email" required class="field" placeholder="{{ __('you@example.com') }}"/>
                            </div>
                            <div>
                                <label class="field-label" for="subject">{{ __('Subject') }}</label>
                                <input type="text" id="subject" name="subject" required class="field" placeholder="{{ __('How can we help?') }}"/>
                            </div>
                        </div>

                        <div>
                            <label class="field-label" for="msg">{{ __('Message') }}</label>
                            <textarea id="msg" name="msg" rows="5" class="field" placeholder="{{ __('Tell us what you need') }}"></textarea>
                        </div>

                        <button type="submit" class="btn-primary btn-block">
                            {{ __('Send message') }}
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.9" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 12 3.269 3.125A59.769 59.769 0 0 1 21.485 12 59.768 59.768 0 0 1 3.27 20.875L5.999 12Zm0 0h7.5"/>
                            </svg>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </section>
@endsection
