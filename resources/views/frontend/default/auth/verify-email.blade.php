@extends('frontend::layouts.auth')

@section('title'){{ __('Verify Email') }}@endsection

@section('content')
    <span class="flex h-14 w-14 items-center justify-center rounded-2xl bg-brand-500/10 text-brand-600 dark:text-brand-300">
        <svg class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke-width="1.6" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0L3.32 8.91a2.25 2.25 0 0 1-1.07-1.916V6.75"/>
        </svg>
    </span>

    <h1 class="mt-6 text-2xl font-bold tracking-tight text-[rgb(var(--text-strong))] sm:text-3xl">
        {{ __('Welcome Back!') }}
    </h1>
    <p class="mt-2 text-sm leading-relaxed text-[rgb(var(--text-muted))]">
        {{ __('verify your email address by clicking on the link we just emailed to you') }}
    </p>

    @if(session('status') == 'verification-link-sent')
        <div class="mt-6 flex items-start gap-3 rounded-2xl border border-earn-500/25 bg-earn-500/[0.07] p-4">
            <svg class="mt-0.5 h-5 w-5 shrink-0 text-earn-600 dark:text-earn-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5"/>
            </svg>
            <p class="text-sm leading-relaxed text-earn-700 dark:text-earn-300">
                {{ __('A new verification link has been sent to the email address you provided during registration.') }}
            </p>
        </div>
    @endif

    <form method="POST" action="{{ route('verification.send') }}" class="mt-8">
        @csrf
        <button type="submit" class="btn-primary btn-block btn-lg">{{ __('Resend Verification Email') }}</button>
    </form>

    <form method="POST" action="{{ route('logout') }}" class="mt-3">
        @csrf
        <button type="submit" class="btn-ghost btn-block">{{ __('Log Out') }}</button>
    </form>
@endsection
