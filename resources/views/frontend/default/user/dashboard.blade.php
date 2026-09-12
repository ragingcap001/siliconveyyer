@extends('frontend::layouts.user')

@section('title'){{ __('Dashboard') }}@endsection
@section('subtitle'){{ __('Your earnings, tasks and activity at a glance.') }}@endsection

@section('content')
    <div class="space-y-6">

        {{-- stat cards --}}
        @include('frontend::user.include.__user_card')

        {{-- referral + ranking --}}
        @include('frontend::user.include.__referral_ranking')

        {{-- recent transactions --}}
        @include('frontend::user.include.__recent_transaction')
    </div>
@endsection

@section('script')
    <script>
        function copyRef() {
            var copyApi = document.getElementById('refLink');
            if (!copyApi) return;

            copyApi.select();
            copyApi.setSelectionRange(0, 999999999);

            if (navigator.clipboard && window.isSecureContext) {
                navigator.clipboard.writeText(copyApi.value);
            } else {
                document.execCommand('copy');
            }

            var btn = document.getElementById('copy');
            if (btn) {
                var previous = btn.textContent;
                btn.textContent = '{{ __('Copied') }}';
                setTimeout(function () { btn.textContent = previous; }, 1800);
            }
        }
    </script>
@endsection
