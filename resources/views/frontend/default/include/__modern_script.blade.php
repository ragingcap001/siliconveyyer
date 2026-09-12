<script src="{{ asset('global/js/jquery.min.js') }}"></script>

{{-- notify + shared global helpers --}}
<script src="{{ asset('global/js/simple-notify.min.js') }}"></script>
<script src="{{ asset('global/js/custom.js?var=6') }}"></script>

<script>
    // lucide icons (partials render <i icon-name="..."> placeholders)
    window.addEventListener('DOMContentLoaded', function () {
        if (window.lucide) {
            lucide.createIcons();
        }
    });

    // Reveal-on-scroll for anything tagged [data-reveal]
    window.addEventListener('DOMContentLoaded', function () {
        var items = document.querySelectorAll('[data-reveal]');
        if (!items.length) return;

        if (!('IntersectionObserver' in window)) {
            items.forEach(function (el) { el.classList.add('is-visible'); });
            return;
        }

        var io = new IntersectionObserver(function (entries) {
            entries.forEach(function (entry) {
                if (entry.isIntersecting) {
                    var delay = entry.target.getAttribute('data-reveal-delay') || 0;
                    setTimeout(function () { entry.target.classList.add('is-visible'); }, delay);
                    io.unobserve(entry.target);
                }
            });
        }, { threshold: 0.12, rootMargin: '0px 0px -40px 0px' });

        items.forEach(function (el) { io.observe(el); });
    });
</script>

@include('global.__t_notify')

@if(auth()->check())
    <script src="{{ asset('global/js/pusher.min.js') }}"></script>
    @include('global.__notification_script',['for'=>'user','userId' => auth()->user()->id])
@endif

@php
    $googleAnalytics = plugin_active('Google Analytics');
    $tawkChat = plugin_active('Tawk Chat');
    $fb = plugin_active('Facebook Messenger');
@endphp

@if($googleAnalytics)
    @include('frontend::plugin.google_analytics',['GoogleAnalyticsId' => json_decode($googleAnalytics?->data,true)['app_id']])
@endif
@if($tawkChat)
    @include('frontend::plugin.tawk',['data' => json_decode($tawkChat->data, true)])
@endif
@if($fb)
    @include('frontend::plugin.fb',['data' => json_decode($fb->data, true)])
@endif

@notifyJs
@yield('script')
@stack('script')
