<div class="modal" id="loginModal">
    <div class="modal-content">
        <span class="close-modal">&times;</span>
        <h2>Авторизация</h2>
        <form id="loginForm" method="POST" action="{{ route('login') }}">
            @csrf
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="password">Пароль</label>
                <input type="password" id="password" name="password" required>
            </div>
            <button type="submit" class="btn btn-primary">Войти</button>
        </form>
        <p class="switch-form">Еще не зарегистрированы? <a href="#" id="showRegister">Зарегистрироваться</a></p>
    </div>
</div> 