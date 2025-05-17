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
</head>
<body>
    <header class="header">
        <div class="container">
            <div class="nav-container">
                <a href="/" class="logo"><img src="{{ asset('images/logo.svg') }}" alt="Логотип"></a>
                <nav class="nav">
                    <div class="nav-links">
                        <a href="/about-us" class="nav-link">О нас</a>
                        <a href="#tours" class="nav-link">Туры</a>
                        <a href="/rent" class="nav-link">Аренда</a>
                        @auth
                            @if(auth()->user()->is_admin)
                                <a href="/admin" class="nav-link">Админ-панель</a>
                            @endif
                        @endauth
                    </div>
                </nav>
                <div class="nav-buttons">
                    <button class="nav-button">Забронировать</button>
                    <button class="nav-button-profile">
                        <img src="{{ asset('images/header_profile.svg') }}" alt="profile">
                    </button>
                </div>
            </div>
            <div class="title">
                <h3>ОТКРОЙТЕ ДЛЯ СЕБЯ МИР</h3>
                <h1>ДАЙВИНГА НА БАЛТИКЕ</h1>
            </div>
        </div>
    </header>


    @include('auth.register-modal')
    @include('auth.login-modal')
    @include('booking-modal')

    <script src="{{ asset('js/modal.js') }}"></script>
    <script src="{{ asset('js/admin.js') }}"></script>
    <script src="{{ asset('js/booking.js') }}"></script>
</body>
</html>
