{{--<!DOCTYPE html>--}}
{{--<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">--}}
{{--<head>--}}
{{--    <meta charset="utf-8">--}}
{{--    <meta name="viewport" content="width=device-width, initial-scale=1">--}}
{{--    <meta name="csrf-token" content="{{ csrf_token() }}">--}}
{{--    <meta name="auth-check" content="{{ auth()->check() ? 'true' : 'false' }}">--}}
{{--    <meta name="is-admin" content="{{ auth()->check() && auth()->user()->is_admin ? 'true' : 'false' }}">--}}

{{--    <title>{{ config('app.name', 'Laravel') }}</title>--}}

{{--    <link href="{{ asset('css/style.css') }}" rel="stylesheet">--}}
{{--    <!-- Подключение Swiper.js CSS -->--}}
{{--    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css" />--}}
{{--</head>--}}
<style>
    /* Burger menu styles */
    .burger-menu {
        display: none;
        position: absolute;
        top: 20px;
        left: 20px;
        z-index: 1000;
        cursor: pointer;
        width: 30px;
        height: 20px;
        background: none;
        border: none;
        padding: 0;
    }

    .burger-icon {
        width: 100%;
        height: 100%;
        position: relative;
    }

    .burger-icon img {
        display: block;
        width: 100%;
        height: 100%;
    }

    .burger-menu.active .burger-icon img {
        content: url("{{ asset('images/burger-krest.svg') }}");
    }

    /* Mobile menu styles */
    .nav-links {
        transition: transform 0.3s ease;
    }

    @media (max-width: 1200px) {
        .burger-menu {
            display: block;
        }

        .nav-container {
            position: relative;
        }

        .logo {
            display: none; /* Hide logo in mobile view */
        }

        .nav-links {
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            width: 250px;
            background: #333;
            flex-direction: column;
            padding: 60px 20px;
            transform: translateX(-100%);
            z-index: 999;
        }

        .nav-links.active {
            transform: translateX(0);
        }

        .nav-links a {
            display: block;
            margin: 10px 0;
            color: #fff;
            text-align: left;
        }

        .nav-buttons {
            display: none; /* Hide nav-buttons in mobile view */
        }

        .header .title {
            margin-top: 60px; /* Adjust for burger menu space */
        }

        .book-button-mobile {
            display: block;
            margin: 200px auto;
            text-align: center;
        }
    }

    @media (min-width: 1201px) {
        .book-button-mobile {
            display: none; /* Hide mobile booking button in desktop view */
        }
    }
</style>
</head>
<body>
<header class="header">
    <div class="container">
        <button class="burger-menu">
            <span class="burger-icon">
                <img src="{{ asset('images/burger.svg') }}" alt="Логотип">
            </span>
        </button>
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
            <button class="nav-button book-button-mobile">Забронировать</button>
        </div>
    </div>
</header>

@include('booking-modal')

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const burgerMenu = document.querySelector('.burger-menu');
        const navLinks = document.querySelector('.nav-links');

        burgerMenu.addEventListener('click', function () {
            this.classList.toggle('active');
            navLinks.classList.toggle('active');
        });

        // Close menu when clicking a link
        navLinks.querySelectorAll('.nav-link').forEach(link => {
            link.addEventListener('click', function () {
                burgerMenu.classList.remove('active');
                navLinks.classList.remove('active');
            });
        });
    });
</script>
</body>
</html>
