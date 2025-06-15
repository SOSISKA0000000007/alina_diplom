<div class="profile-sidebar">
    <nav class="profile-nav">
        <a href="{{ route('admin.rental-products.create') }}" class="profile-nav-link  {{ request()->routeIs('admin.rental-products.create') ? 'active' : '' }}">
            Создать товар
        </a>
{{--        <a href="{{ route('admin.tours.create') }}" class="profile-nav-link  {{ request()->routeIs('admin.tours.create') ? 'active' : '' }}">--}}
{{--            Создать тур--}}
{{--        </a>--}}
        <a href="{{ route('admin.instructors.create') }}" class="profile-nav-link  {{ request()->routeIs('admin.instructors.create') ? 'active' : '' }}">
            Создать инструктора
        </a>
        <a href="{{ route('admin.rentals.index') }}" class="profile-nav-link  {{ request()->routeIs('admin.rentals.index') ? 'active' : '' }}">
            Управление арендой
        </a>
        <a href="{{ route('admin.bookings.index') }}" class="profile-nav-link  {{ request()->routeIs('admin.bookings.index') ? 'active' : '' }}">
            Управление бронированиями
        </a>
        <a href="{{ route('admin.rental-products.index') }}" class="profile-nav-link  {{ request()->routeIs('admin.rental-products.index') ? 'active' : '' }}">
            Редактировать товар
        </a>
        <a href="{{ route('admin.users.index') }}" class="profile-nav-link  {{ request()->routeIs('admin.users.index') ? 'active' : '' }}">
            Пользователи
        </a>
    </nav>
</div>

<style>

.admin-nav ul {
    list-style: none;
    padding: 0;
    margin: 0;
}

.admin-nav-link {
    display: block;
    padding: 12px 15px;
    color: #333;
    text-decoration: none;
    border-radius: 4px;
    margin-bottom: 5px;
    transition: background-color 0.3s;
}

.admin-nav-link:hover {
    background-color: #e9ecef;
}

.admin-nav-link.active {
    background-color: #007bff;
    color: white;
}
</style>
