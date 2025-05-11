document.addEventListener('DOMContentLoaded', function() {
    const registerModal = document.getElementById('registerModal');
    const loginModal = document.getElementById('loginModal');
    const profileBtn = document.querySelector('.nav-button-profile');
    const showLoginBtn = document.getElementById('showLogin');
    const showRegisterBtn = document.getElementById('showRegister');
    const closeBtns = document.querySelectorAll('.close-modal');

    // Проверяем, авторизован ли пользователь
    const isAuthenticated = document.querySelector('meta[name="auth-check"]').getAttribute('content') === 'true';

    // Обработка клика по кнопке профиля
    profileBtn.addEventListener('click', function() {
        if (isAuthenticated) {
            // Если пользователь авторизован, перенаправляем на страницу профиля
            window.location.href = '/profile';
        } else {
            // Если не авторизован, показываем окно входа
            loginModal.style.display = 'block';
        }
    });

    // Переключение между модальными окнами
    showLoginBtn.addEventListener('click', function(e) {
        e.preventDefault();
        registerModal.style.display = 'none';
        loginModal.style.display = 'block';
    });

    showRegisterBtn.addEventListener('click', function(e) {
        e.preventDefault();
        loginModal.style.display = 'none';
        registerModal.style.display = 'block';
    });

    // Закрытие модальных окон
    closeBtns.forEach(function(btn) {
        btn.addEventListener('click', function() {
            registerModal.style.display = 'none';
            loginModal.style.display = 'none';
        });
    });

    // Закрытие модальных окон при клике вне их области
    window.addEventListener('click', function(event) {
        if (event.target === registerModal) {
            registerModal.style.display = 'none';
        }
        if (event.target === loginModal) {
            loginModal.style.display = 'none';
        }
    });
}); 