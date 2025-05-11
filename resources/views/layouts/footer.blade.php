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
    <!-- Подключение Swiper.js CSS -->
    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css" />
    <!-- Подключение Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<footer class="headerr">
    <div class="container">
        <div class="nav-container">
            <nav class="nav">
                <div class="nav-links">
                    <a href="/about-us" class="nav-link">О нас</a>
                    <a href="#tours" class="nav-link">Туры</a>
                    <a href="/rent" class="nav-link">Аренда</a>
                </div>
            </nav>
            <div class="logo">
                <a href="/"><img src="{{ asset('images/logo.svg') }}" alt="Логотип"></a>
            </div>
            <div class="nav-buttons">
                <p class="nav-copyright">©бебебебебебббебббебебеб</p>
            </div>
        </div>
    </div>
</footer>

@include('auth.register-modal')
@include('auth.login-modal')
@include('booking-modal')

<script src="{{ asset('js/modal.js') }}"></script>
<script src="{{ asset('js/admin.js') }}"></script>
<script src="{{ asset('js/booking.js') }}"></script>
</body>
</html>
