<div class="admin-sidebar">
    <nav class="admin-nav">
        <ul>
            <li>
                <a href="{{ route('admin.rental-products.create') }}" class="admin-nav-link">
                    <span>Создать товар</span>
                </a>
            </li>
            <li>
                <a href="{{ route('admin.tours.create') }}" class="admin-nav-link">
                    <span>Создать тур</span>
                </a>
            </li>
            <li>
                <a href="{{ route('admin.instructors.create') }}" class="admin-nav-link">
                    <span>Создать инструктора</span>
                </a>
            </li>
            <li>
                <a href="{{ route('admin.rentals.index') }}" class="admin-nav-link">
                    <span>Управление арендой</span>
                </a>
            </li>
        </ul>
    </nav>
</div>

<style>
.admin-sidebar {
    width: 250px;
    background-color: #f8f9fa;
    padding: 20px;
    height: 100vh;
    position: fixed;
    left: 0;
    top: 0;
    border-right: 1px solid #dee2e6;
}

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
