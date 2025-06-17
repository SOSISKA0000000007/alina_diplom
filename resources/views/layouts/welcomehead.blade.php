<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="auth-check" content="{{ auth()->check() ? 'true' : 'false' }}">
    <meta name="is-admin" content="{{ auth()->check() && auth()->user()->is_admin ? 'true' : 'false' }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css" />
</head>
<body>
@include('layouts.app')

<main class="main">
    @yield('content')
</main>

@include('layouts.footer')

{{--@include('auth.register-modal')--}}
{{--@include('auth.login-modal')--}}
{{--@include('booking-modal')--}}

<script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
{{--<script src="{{ asset('js/modal.js') }}"></script>--}}
{{--<script src="{{ asset('js/admin.js') }}"></script>--}}
{{--<script src="{{ asset('js/booking.js') }}"></script>--}}
{{--@yield('scripts')--}}
</body>
</html>
