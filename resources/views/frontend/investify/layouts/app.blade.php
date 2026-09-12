<!DOCTYPE html>
<html lang="{{ str_replace('_','-',app()->getLocale()) }}" class="scroll-pt-28">
@include('frontend::include.__head')

<body class="min-h-screen bg-[rgb(var(--surface))] font-sans text-[rgb(var(--text-body))] antialiased">

<x:notify-messages/>

@include('frontend::include.__header')

<main class="pt-24 lg:pt-28">
    @yield('content')
</main>

@include('frontend::include.__footer')
@include('frontend::cookie.gdpr_cookie')

@include('frontend::include.__script')

</body>
</html>
