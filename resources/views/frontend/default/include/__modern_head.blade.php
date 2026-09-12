<head>
    <meta charset="UTF-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <meta name="csrf-token" content="{{ csrf_token() }}"/>
    <meta name="keywords" content="@yield('meta_keywords',setting('site_title','global'))"/>
    <meta name="description" content="@yield('meta_description',setting('site_title','global'))"/>
    <link rel="canonical" href="{{ url()->current() }}"/>

    <link rel="icon" href="{{ asset(setting('site_favicon','global')) }}" type="image/x-icon"/>
    <link rel="shortcut icon" href="{{ asset(setting('site_favicon','global')) }}" type="image/x-icon"/>

    <link rel="preconnect" href="https://fonts.googleapis.com"/>
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin/>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Sora:wght@500;600;700;800&display=swap"
          rel="stylesheet"/>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- notify + lucide are still used by shared partials --}}
    <link rel="stylesheet" href="{{ asset('global/css/simple-notify.min.css') }}"/>
    <script src="{{ asset('global/js/lucide.min.js') }}" defer></script>

    @notifyCss
    @stack('style')
    @yield('style')

    <style>{!! \App\Models\CustomCss::first()?->css !!}</style>

    {{-- Apply the stored colour mode before first paint so there is no flash --}}
    <script>
        (function () {
            try {
                // No site-wide default setting ships with the app, so dark is the
                // initial state and the visitor's own choice wins afterwards.
                var stored = localStorage.getItem('site-color-mode');
                document.documentElement.classList.toggle('dark', stored !== 'light');
            } catch (e) {
                document.documentElement.classList.add('dark');
            }
        })();
    </script>

    <title>{{ setting('site_title', 'global') }} - @yield('title')</title>
</head>
