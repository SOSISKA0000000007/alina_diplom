@extends('layouts.hf')

@section('content')
    <div class="profile-wrapper">
        <div class="profile-sidebar">
            <nav class="profile-nav">
                <a href="{{ route('profile.index') }}" class="profile-nav-link {{ request()->routeIs('profile.index') ? 'active' : '' }}">
                    Профиль
                </a>
                <a href="{{ route('profile.bookings') }}" class="profile-nav-link {{ request()->routeIs('profile.bookings') ? 'active' : '' }}">
                    Бронь
                </a>
                <a href="{{ route('profile.rent') }}" class="profile-nav-link {{ request()->routeIs('profile.rent') ? 'active' : '' }}">
                    Аренда
                </a>
                @auth
                    <a href="{{ route('logout') }}" class="profile-nav-link" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        Выход
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                @endauth
            </nav>
        </div>

        <div class="profile-content">
            @yield('profile-content')
        </div>
    </div>
@endsection
