@extends('frontend::layouts.auth')

@section('title'){{ __('Login') }}@endsection

@section('content')
    <h1 class="text-2xl font-bold tracking-tight text-[rgb(var(--text-strong))] sm:text-3xl">
        {{ $data['title'] ?? __('Welcome back') }}
    </h1>
    <p class="mt-2 text-sm text-[rgb(var(--text-muted))]">
        {{ $data['bottom_text'] ?? '' }}
    </p>

    @if($errors->any())
        <div class="mt-6 rounded-2xl border border-rose-500/25 bg-rose-500/[0.07] p-4">
            <ul class="space-y-1">
                @foreach($errors->all() as $error)
                    <li class="flex items-start gap-2 text-sm text-rose-700 dark:text-rose-300">
                        <svg class="mt-0.5 h-4 w-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z"/>
                        </svg>
                        {{ $error }}
                    </li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}" class="mt-8 space-y-5">
        @csrf

        <div>
            <label class="field-label" for="email">{{ __('Email Or Username') }}</label>
            <input id="email" type="text" name="email" required autofocus
                   class="field" placeholder="{{ __('Enter your email address or username') }}"
                   value="{{ old('email') }}"/>
        </div>

        <div>
            <label class="field-label" for="password">{{ __('Password') }}</label>
            <input id="password" type="password" name="password" required class="field"
                   placeholder="{{ __('Enter your password') }}"/>
        </div>

        @if($googleReCaptcha)
            <div class="g-recaptcha" id="feedback-recaptcha"
                 data-sitekey="{{ json_decode($googleReCaptcha->data, true)['google_recaptcha_key'] }}"></div>
        @endif

        <div class="flex flex-wrap items-center justify-between gap-3">
            <label class="flex items-center gap-2 text-sm text-[rgb(var(--text-body))]">
                <input type="checkbox" name="remember"
                       class="h-4 w-4 rounded border-[rgb(var(--line)/0.2)] text-brand-600 focus:ring-brand-500/30"/>
                {{ __('Remember me') }}
            </label>

            @if(Route::has('password.request'))
                <a href="{{ route('password.request') }}" class="text-sm font-semibold text-brand-600 hover:underline dark:text-brand-300">
                    {{ __('Forget Password') }}
                </a>
            @endif
        </div>

        <button type="submit" class="btn-primary btn-block btn-lg">
            {{ __('Account Login') }}
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3"/>
            </svg>
        </button>
    </form>

    <p class="mt-8 text-center text-sm text-[rgb(var(--text-muted))]">
        {{ __("Don't have an account?") }}
        <a href="{{ route('register') }}" class="font-semibold text-brand-600 hover:underline dark:text-brand-300">
            {{ __('Signup for free') }}
        </a>
    </p>
@endsection
