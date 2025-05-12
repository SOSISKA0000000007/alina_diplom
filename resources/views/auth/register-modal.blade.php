<div class="modal" id="registerModal">
    <div class="modal-content">
        <span class="close-modal">&times;</span>
        <h2>Регистрация</h2>
        <p class="switch-form">У вас уже есть аккаунт ?<a href="#" id="showLogin">Войти</a></p>
        <form id="registerForm" method="POST" action="{{ route('register') }}">
            @csrf
            <div class="form-group">
                <label for="name"></label>
                <input type="text" id="name" name="name" placeholder="Имя" required>
            </div>
            <div class="form-group">
                <label for="email"></label>
                <input type="email" id="email" name="email" placeholder="Email" required>
            </div>
            <div class="form-group">
                <label for="phone"></label>
                <input type="tel" id="phone" name="phone" placeholder="Номер телефона" required>
            </div>
            <div class="form-group">
                <label for="password"></label>
                <input type="password" id="password" name="password" placeholder="Пароль" required>
            </div>
            <div class="form-group">
                <label for="password_confirmation"></label>
                <input type="password" id="password_confirmation" name="password_confirmation" placeholder="Подтверждение пароля" required>
            </div>
            <button type="submit" class="btn btn-primary">Зарегистрироваться</button>
        </form>
    </div>
</div>
