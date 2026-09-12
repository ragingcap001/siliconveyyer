@extends('frontend::layouts.auth')

@section('title'){{ __('Reset password') }}@endsection

@section('content')
    <h1 class="text-2xl font-bold tracking-tight text-[rgb(var(--text-strong))] sm:text-3xl">
        {{ __('Choose a new password') }}
    </h1>
    <p class="mt-2 text-sm leading-relaxed text-[rgb(var(--text-muted))]">
        {{ __('Almost there. Pick something you have not used before.') }}
    </p>

    @if($errors->any())
        <div class="mt-6 rounded-2xl border border-rose-500/25 bg-rose-500/[0.07] p-4">
            @foreach($errors->all() as $error)
                <p class="text-sm text-rose-700 dark:text-rose-300">{{ $error }}</p>
            @endforeach
        </div>
    @endif

    <form method="POST" action="{{ route('password.update') }}" class="mt-8 space-y-5">
        @csrf
        <input type="hidden" name="token" value="{{ $request->route('token') }}"/>

        <div>
            <label class="field-label" for="email">{{ __('Email Address') }}</label>
            <input id="email" type="email" name="email" required readonly class="field"
                   value="{{ $request->email ?? old('email') }}"/>
        </div>

        <div>
            <label class="field-label" for="password">{{ __('New Password') }}</label>
            <input id="password" type="password" name="password" required autofocus class="field"
                   placeholder="{{ __('Enter your new password') }}"/>
        </div>

        <div>
            <label class="field-label" for="password_confirmation">{{ __('Confirm Password') }}</label>
            <input id="password_confirmation" type="password" name="password_confirmation" required class="field"
                   placeholder="{{ __('Repeat your new password') }}"/>
        </div>

        <button type="submit" class="btn-primary btn-block btn-lg">{{ __('Reset Password') }}</button>
    </form>

    <p class="mt-8 text-center text-sm text-[rgb(var(--text-muted))]">
        <a href="{{ route('login') }}" class="font-semibold text-brand-600 hover:underline dark:text-brand-300">
            {{ __('Back to login') }}
        </a>
    </p>
@endsection
