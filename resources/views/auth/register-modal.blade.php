<div class="modal" id="registerModal">
    <div class="modal-content">
        <span class="close-modal">&times;</span>
        <h2>Регистрация</h2>
        <form id="registerForm" method="POST" action="{{ route('register') }}">
            @csrf
            <div class="form-group">
                <label for="name">Имя</label>
                <input type="text" id="name" name="name" required>
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="phone">Номер телефона</label>
                <input type="tel" id="phone" name="phone" required>
            </div>
            <div class="form-group">
                <label for="password">Пароль</label>
                <input type="password" id="password" name="password" required>
            </div>
            <div class="form-group">
                <label for="password_confirmation">Подтверждение пароля</label>
                <input type="password" id="password_confirmation" name="password_confirmation" required>
            </div>
            <button type="submit" class="btn btn-primary">Зарегистрироваться</button>
        </form>
        <p class="switch-form">Уже зарегистрированы? <a href="#" id="showLogin">Войти</a></p>
    </div>
</div> 