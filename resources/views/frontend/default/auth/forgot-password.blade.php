@extends('frontend::layouts.auth')

@section('title'){{ __('Forgot Password') }}@endsection

@section('content')
    <h1 class="text-2xl font-bold tracking-tight text-[rgb(var(--text-strong))] sm:text-3xl">
        {{ $data['title'] ?? __('Forgot your password?') }}
    </h1>
    <p class="mt-2 text-sm leading-relaxed text-[rgb(var(--text-muted))]">
        {{ $data['bottom_text'] ?? __('No problem. Enter your email and we will send you a reset link.') }}
    </p>

    @if($errors->any())
        <div class="mt-6 rounded-2xl border border-rose-500/25 bg-rose-500/[0.07] p-4">
            @foreach($errors->all() as $error)
                <p class="text-sm text-rose-700 dark:text-rose-300">{{ $error }}</p>
            @endforeach
        </div>
    @endif

    @if(session('status'))
        <div class="mt-6 rounded-2xl border border-earn-500/25 bg-earn-500/[0.07] p-4">
            <p class="text-sm text-earn-700 dark:text-earn-300">{{ session('status') }}</p>
        </div>
    @endif

    <form method="POST" action="{{ route('password.email') }}" class="mt-8 space-y-5">
        @csrf
        <div>
            <label class="field-label" for="email">{{ __('Email Address') }}</label>
            <input id="email" type="email" name="email" required autofocus class="field"
                   placeholder="{{ __('you@example.com') }}" value="{{ old('email') }}"/>
        </div>

        <button type="submit" class="btn-primary btn-block btn-lg">{{ __('Send Reset Link') }}</button>
    </form>

    <p class="mt-8 text-center text-sm text-[rgb(var(--text-muted))]">
        <a href="{{ route('login') }}" class="font-semibold text-brand-600 hover:underline dark:text-brand-300">
            {{ __('Back to login') }}
        </a>
    </p>
@endsection
