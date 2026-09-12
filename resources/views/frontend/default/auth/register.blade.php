@extends('frontend::layouts.auth')

@section('title'){{ __('Register') }}@endsection

@section('content')
    <h1 class="text-2xl font-bold tracking-tight text-[rgb(var(--text-strong))] sm:text-3xl">
        {{ $data['title'] ?? __('Create your account') }}
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
                        {{ __('You Entered') }} {{ $error }}
                    </li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('register') }}" class="mt-8 grid gap-5 sm:grid-cols-2">
        @csrf

        <div>
            <label class="field-label" for="first_name">{{ __('First Name') }} <span class="text-rose-500">*</span></label>
            <input id="first_name" type="text" name="first_name" required class="field"
                   placeholder="{{ __('Your First Name') }}" value="{{ old('first_name') }}"/>
        </div>

        <div>
            <label class="field-label" for="last_name">{{ __('Last Name') }} <span class="text-rose-500">*</span></label>
            <input id="last_name" type="text" name="last_name" required class="field"
                   placeholder="{{ __('Your Last Name') }}" value="{{ old('last_name') }}"/>
        </div>

        <div>
            <label class="field-label" for="email">{{ __('Email Address') }} <span class="text-rose-500">*</span></label>
            <input id="email" type="email" name="email" required class="field"
                   placeholder="{{ __('Enter Your Email Address') }}" value="{{ old('email') }}"/>
        </div>

        @if(getPageSetting('username_show'))
            <div>
                <label class="field-label" for="username">{{ __('User Name') }} <span class="text-rose-500">*</span></label>
                <input id="username" type="text" name="username" required class="field"
                       placeholder="{{ __('Enter Your User Name') }}" value="{{ old('username') }}"/>
            </div>
        @endif

        @if(getPageSetting('country_show'))
            <div>
                <label class="field-label" for="country">{{ __('Select Country') }} <span class="text-rose-500">*</span></label>
                <select name="country" id="countrySelect" class="field" required>
                    @foreach(getCountries() as $country)
                        <option value="{{ $country['name'].':'.$country['dial_code'] }}"
                            @selected(($location->country_code ?? null) === $country['code'])>
                            {{ $country['name'] }}
                        </option>
                    @endforeach
                </select>
            </div>
        @endif

        @if(getPageSetting('phone_show'))
            <div>
                <label class="field-label" for="phone">{{ __('Phone Number') }}</label>
                <div class="flex">
                    <span class="inline-flex shrink-0 items-center rounded-l-xl border border-r-0 border-[rgb(var(--line)/0.12)] bg-[rgb(var(--surface-muted))] px-3.5 text-sm font-medium text-[rgb(var(--text-muted))]">
                        {{ getLocation()->dial_code }}
                    </span>
                    <input id="phone" type="text" name="phone" class="field rounded-l-none"
                           placeholder="{{ __('Phone Number') }}" value="{{ old('phone') }}"/>
                </div>
            </div>
        @endif

        @if(getPageSetting('referral_code_show'))
            <div>
                <label class="field-label" for="invite">{{ __('Referral Code') }}</label>
                <input id="invite" type="text" name="invite" class="field"
                       placeholder="{{ __('Enter Your Referral Code') }}"
                       value="{{ request('invite') ?? old('invite') }}"/>
            </div>
        @endif

        <div>
            <label class="field-label" for="password">{{ __('Password') }} <span class="text-rose-500">*</span></label>
            <input id="password" type="password" name="password" required class="field"
                   placeholder="{{ __('Enter your password') }}"/>
        </div>

        <div>
            <label class="field-label" for="password_confirmation">{{ __('Confirm Password') }} <span class="text-rose-500">*</span></label>
            <input id="password_confirmation" type="password" name="password_confirmation" required class="field"
                   placeholder="{{ __('Enter your password') }}"/>
        </div>

        @if($googleReCaptcha)
            <div class="sm:col-span-2">
                <div class="g-recaptcha" id="feedback-recaptcha"
                     data-sitekey="{{ json_decode($googleReCaptcha->data, true)['google_recaptcha_key'] }}"></div>
            </div>
        @endif

        <div class="sm:col-span-2">
            <label class="flex items-start gap-2.5 text-sm text-[rgb(var(--text-body))]">
                <input type="checkbox" name="i_agree" value="yes" required
                       class="mt-0.5 h-4 w-4 rounded border-[rgb(var(--line)/0.2)] text-brand-600 focus:ring-brand-500/30"/>
                <span>
                    {{ __('I agree with') }}
                    <a href="{{ url('/privacy-policy') }}" class="font-semibold text-brand-600 hover:underline dark:text-brand-300">{{ __('Privacy & Policy') }}</a>
                    {{ __('and') }}
                    <a href="{{ url('/terms-and-conditions') }}" class="font-semibold text-brand-600 hover:underline dark:text-brand-300">{{ __('Terms & Condition') }}</a>
                </span>
            </label>
        </div>

        <div class="sm:col-span-2">
            <button type="submit" class="btn-primary btn-block btn-lg">
                {{ __('Create Account') }}
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3"/>
                </svg>
            </button>
        </div>
    </form>

    <p class="mt-8 text-center text-sm text-[rgb(var(--text-muted))]">
        {{ __('Already have an account?') }}
        <a href="{{ route('login') }}" class="font-semibold text-brand-600 hover:underline dark:text-brand-300">
            {{ __('Login') }}
        </a>
    </p>
@endsection
