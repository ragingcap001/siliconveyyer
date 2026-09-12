@extends('frontend::layouts.auth')

@section('title'){{ __('2FA Security') }}@endsection

@section('content')
    <span class="flex h-14 w-14 items-center justify-center rounded-2xl bg-brand-500/10 text-brand-600 dark:text-brand-300">
        <svg class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke-width="1.6" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 1.5H8.25A2.25 2.25 0 0 0 6 3.75v16.5a2.25 2.25 0 0 0 2.25 2.25h7.5A2.25 2.25 0 0 0 18 20.25V3.75a2.25 2.25 0 0 0-2.25-2.25H13.5m-3 0V3h3V1.5m-3 0h3m-3 18.75h3"/>
        </svg>
    </span>

    <h1 class="mt-6 text-2xl font-bold tracking-tight text-[rgb(var(--text-strong))] sm:text-3xl">
        {{ __('Welcome Back!') }}
    </h1>
    <p class="mt-2 text-sm leading-relaxed text-[rgb(var(--text-muted))]">
        {{ __('Sign in to continue with') }} {{ setting('site_title','global') }} {{ __('User Panel') }}
    </p>

    @if($errors->any())
        <div class="mt-6 rounded-2xl border border-rose-500/25 bg-rose-500/[0.07] p-4">
            @foreach($errors->all() as $error)
                <p class="text-sm text-rose-700 dark:text-rose-300">{{ __('You Entered') }} {{ $error }}</p>
            @endforeach
        </div>
    @endif

    <form method="POST" action="{{ route('user.setting.2fa.verify') }}" class="mt-8 space-y-5">
        @csrf

        <div class="rounded-2xl border border-[rgb(var(--line)/0.09)] bg-[rgb(var(--surface-muted))] p-4">
            <p class="text-sm leading-relaxed text-[rgb(var(--text-muted))]">
                {{ __('Please enter the') }} <strong class="text-[rgb(var(--text-strong))]">{{ __('OTP') }}</strong>
                {{ __('generated on your Authenticator App.') }}
                <br/>
                {{ __('Ensure you submit the current one because it refreshes every 30 seconds.') }}
            </p>
        </div>

        <div>
            <label class="field-label" for="one_time_password">{{ __('One Time Password') }}</label>
            <input id="one_time_password" type="password" name="one_time_password" required autofocus
                   class="field tracking-[0.4em]" placeholder="••••••" inputmode="numeric" autocomplete="one-time-code"/>
        </div>

        <button type="submit" class="btn-primary btn-block btn-lg">{{ __('Authenticate Now') }}</button>
    </form>
@endsection
