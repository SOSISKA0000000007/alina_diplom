document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM загружен');

    // Функция для открытия модального окна
    function openModal(modalId) {
        console.log('Попытка открыть модальное окно:', modalId);
        const modal = document.getElementById(modalId);
        if (modal) {
            modal.style.display = 'block';
            console.log('Модальное окно открыто');
        } else {
            console.error('Модальное окно не найдено:', modalId);
        }
    }
    
    // Функция для закрытия модального окна
    function closeModal(modalId) {
        console.log('Попытка закрыть модальное окно:', modalId);
        const modal = document.getElementById(modalId);
        if (modal) {
            modal.style.display = 'none';
            console.log('Модальное окно закрыто');
        }
    }
    
    // Обработчики для кнопок открытия модальных окон
    const addDateButtons = document.querySelectorAll('.btn-add-date');
    console.log('Найдено кнопок добавления даты:', addDateButtons.length);
    
    addDateButtons.forEach(button => {
        button.addEventListener('click', function() {
            console.log('Нажата кнопка добавления даты');
            const tourId = this.getAttribute('data-tour-id');
            console.log('ID тура:', tourId);
            document.getElementById('tour_id').value = tourId;
            openModal('addDateModal');
        });
    });
    
    const editPriceButtons = document.querySelectorAll('.btn-edit-price');
    console.log('Найдено кнопок редактирования цены:', editPriceButtons.length);
    
    editPriceButtons.forEach(button => {
        button.addEventListener('click', function() {
            console.log('Нажата кнопка редактирования цены');
            const tourId = this.getAttribute('data-tour-id');
            const priceId = this.getAttribute('data-price-id');
            console.log('ID тура:', tourId, 'ID цены:', priceId);
            document.getElementById('price_tour_id').value = tourId;
            document.getElementById('price_id').value = priceId || '';
            openModal('editPriceModal');
        });
    });
    
    // Обработчики для кнопок закрытия
    const closeButtons = document.querySelectorAll('.close-modal');
    console.log('Найдено кнопок закрытия:', closeButtons.length);
    
    closeButtons.forEach(button => {
        button.addEventListener('click', function() {
            console.log('Нажата кнопка закрытия');
            const modal = this.closest('.modal');
            if (modal) {
                modal.style.display = 'none';
                console.log('Модальное окно закрыто');
            }
        });
    });
    
    // Закрытие модального окна при клике вне его
    window.addEventListener('click', function(event) {
        if (event.target.classList.contains('modal')) {
            console.log('Клик вне модального окна');
            event.target.style.display = 'none';
        }
    });
    
    // Обработка формы добавления даты
    const addDateForm = document.getElementById('addDateForm');
    if (addDateForm) {
        console.log('Форма добавления даты найдена');
        addDateForm.addEventListener('submit', function(event) {
            event.preventDefault();
            console.log('Отправка формы добавления даты');
            const tourId = document.getElementById('tour_id').value;
            const formData = new FormData(this);
            
            fetch(`/admin/tours/${tourId}/dates`, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            })
            .then(response => {
                if (response.ok) {
                    console.log('Дата успешно добавлена');
                    window.location.reload();
                }
            });
        });
    }
    
    // Обработка формы редактирования цен
    const editPriceForm = document.getElementById('editPriceForm');
    if (editPriceForm) {
        console.log('Форма редактирования цен найдена');
        editPriceForm.addEventListener('submit', function(event) {
            event.preventDefault();
            console.log('Отправка формы редактирования цен');
            const tourId = document.getElementById('price_tour_id').value;
            const priceId = document.getElementById('price_id').value;
            const formData = new FormData(this);
            
            const url = priceId 
                ? `/admin/tours/${tourId}/prices/${priceId}`
                : `/admin/tours/${tourId}/prices`;
            
            const method = priceId ? 'PUT' : 'POST';
            
            fetch(url, {
                method: method,
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'X-HTTP-Method-Override': method
                }
            })
            .then(response => {
                if (response.ok) {
                    console.log('Цены успешно обновлены');
                    window.location.reload();
                } else {
                    console.error('Ошибка при обновлении цен:', response.status);
                    response.text().then(text => console.error('Текст ошибки:', text));
                }
            })
            .catch(error => {
                console.error('Ошибка при отправке запроса:', error);
            });
        });
    }
}); 