<div class="modal" id="loginModal">
    <div class="modal-content">
        <span class="close-modal">&times;</span>
        <h2>Войдите в свою учетную запись</h2>
        <p class="switch-form">Нет аккаунта? <a href="#" id="showRegister">Зарегистрироваться</a></p>
        <form id="loginForm" method="POST" action="{{ route('login') }}">
            @csrf
            <div class="form-group">
                <label for="email"></label>
                <input type="email" id="email" name="email" placeholder="Email" required>
            </div>
            <div class="form-group">
                <label for="password"></label>
                <input type="password" id="password" name="password" placeholder="Пароль" required>
            </div>
            <button type="submit" class="btn btn-primary">Войти</button>
        </form>
    </div>
</div>
