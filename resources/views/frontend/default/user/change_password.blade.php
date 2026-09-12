@extends('frontend::layouts.user')

@section('title'){{ __('Change Password') }}@endsection
@section('subtitle'){{ __('Use a strong password you have not used elsewhere.') }}@endsection

@section('content')
    <div class="mx-auto max-w-lg">
        <form action="{{ route('user.new.password') }}" method="post"
              class="rounded-3xl border border-[rgb(var(--line)/0.09)] bg-[rgb(var(--surface-raised))] p-6 shadow-soft sm:p-8">
            @csrf

            @foreach($errors->all() as $error)
                @php notify()->warning($error); @endphp
            @endforeach

            <div class="space-y-5">
                <div>
                    <label class="field-label" for="current_password">{{ __('Current Password') }}</label>
                    <input id="current_password" type="password" name="current_password" required class="field"
                           placeholder="{{ __('Your current password') }}"/>
                </div>

                <div>
                    <label class="field-label" for="new_password">{{ __('New Password') }}</label>
                    <input id="new_password" type="password" name="new_password" required class="field"
                           placeholder="{{ __('Your new password') }}"/>
                </div>

                <div>
                    <label class="field-label" for="new_confirm_password">{{ __('Confirm New Password') }}</label>
                    <input id="new_confirm_password" type="password" name="new_confirm_password" required class="field"
                           placeholder="{{ __('Repeat your new password') }}"/>
                </div>

                <button type="submit" class="btn-primary btn-block btn-lg">
                    {{ __('Update Password') }}
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3"/>
                    </svg>
                </button>
            </div>
        </form>
    </div>
@endsection
