@extends('layouts.header')

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
        </nav>
    </div>

    <div class="profile-content">
        @yield('profile-content')
    </div>
</div>
@endsection
