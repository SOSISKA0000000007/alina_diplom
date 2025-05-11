@extends('layouts.app')

@section('content')
<div class="auth-container">
    <div class="auth-form">
        <h2>Вход в личный кабинет</h2>
        
        <form method="POST" action="{{ route('login') }}" id="loginForm">
            @csrf
            
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required autofocus>
                @error('email')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="password">Пароль</label>
                <input type="password" id="password" name="password" required>
                @error('password')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>

            <button type="submit" class="btn-primary">Войти</button>
            
            <div class="auth-links">
                <p>Нет аккаунта? <a href="{{ route('register') }}">Зарегистрироваться</a></p>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.getElementById('loginForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    fetch(this.action, {
        method: 'POST',
        body: new FormData(this),
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            window.location.href = '/profile';
        } else {
            alert(data.message || 'Произошла ошибка при входе');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Произошла ошибка при входе');
    });
});
</script>
@endpush 