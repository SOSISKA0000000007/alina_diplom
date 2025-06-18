<div class="modal" id="registerModal">
    <div class="modal-content">
        <span class="close-modal">×</span>
        <h2>Регистрация</h2>
        <p class="switch-form">У вас уже есть аккаунт? <a href="#" id="showLogin">Войти</a></p>
        <form id="registerForm" method="POST" action="{{ route('register') }}">
            @csrf
            <div class="form-group">
                <label for="name">Имя</label>
                <input type="text" id="name" name="name" placeholder="Имя" value="{{ old('name') }}" required class="@error('name') is-invalid @enderror">
                @error('name')
                <span class="error">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" placeholder="Email" value="{{ old('email') }}" required class="@error('email') is-invalid @enderror">
                @error('email')
                <span class="error">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group">
                <label for="phone">Номер телефона</label>
                <input type="tel" id="phone" name="phone" placeholder="Номер телефона" value="{{ old('phone') }}" required class="@error('phone') is-invalid @enderror">
                @error('phone')
                <span class="error">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group">
                <label for="password">Пароль</label>
                <input type="password" id="password" name="password" placeholder="Пароль" required class="@error('password') is-invalid @enderror">
                @error('password')
                <span class="error">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group">
                <label for="password_confirmation">Подтверждение пароля</label>
                <input type="password" id="password_confirmation" name="password_confirmation" placeholder="Подтверждение пароля" required>
            </div>
            <button type="submit" class="btn btn-primary">Зарегистрироваться</button>
        </form>
    </div>
</div>
