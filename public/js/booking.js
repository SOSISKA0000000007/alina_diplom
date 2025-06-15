document.addEventListener('DOMContentLoaded', function() {
    const bookingModal = document.getElementById('bookingModal');
    const bookingForm = document.getElementById('bookingForm');
    const tourSelect = document.getElementById('tour_id');
    const dateSelect = document.getElementById('date_id');
    const peopleCountInput = document.getElementById('people_count');
    const availablePlacesSpan = document.getElementById('available_places');
    const pricePerPersonSpan = document.getElementById('price_per_person');
    const totalPriceSpan = document.getElementById('total_price');
    const peopleCountButtons = document.querySelectorAll('.people-count-btn');
    const bookButton = document.querySelector('.nav-button');
    const messageContainer = document.getElementById('messageContainer');

    // Защита от повторной инициализации
    if (window.bookingInitialized) {
        console.warn('booking.js уже инициализирован — повторное выполнение остановлено');
        return;
    }
    window.bookingInitialized = true;

    console.log('Элементы найдены:', {
        bookingModal,
        bookingForm,
        tourSelect,
        dateSelect,
        bookButton,
        peopleCountButtons: peopleCountButtons.length,
        messageContainer
    });

    // Форматирование дат
    function formatDateRange(startDate, endDate) {
        try {
            const start = new Date(startDate);
            const end = new Date(endDate);
            if (isNaN(start.getTime()) || isNaN(end.getTime())) {
                throw new Error('Invalid date');
            }
            const startFormatted = start.toLocaleDateString('ru-RU', { day: '2-digit', month: '2-digit', year: 'numeric' });
            const endFormatted = end.toLocaleDateString('ru-RU', { day: '2-digit', month: '2-digit', year: 'numeric' });
            return `${startFormatted} - ${endFormatted}`;
        } catch (error) {
            console.error('Error formatting dates:', startDate, endDate, error);
            return 'Некорректная дата';
        }
    }

    // Показать сообщение
    function showMessage(message, type) {
        messageContainer.textContent = message;
        messageContainer.className = `message-container ${type}`;
        messageContainer.style.display = 'block';
    }

    // Скрыть сообщение
    function hideMessage() {
        messageContainer.textContent = '';
        messageContainer.className = 'message-container';
        messageContainer.style.display = 'none';
    }

    // Обработчик клика по кнопке "Забронировать" в шапке
    if (bookButton) {
        bookButton.addEventListener('click', function() {
            console.log('Открытие модального окна');
            bookingModal.style.display = 'block';
        });
    }

    // Обработка выбора количества человек
    if (peopleCountButtons.length > 0) {
        peopleCountButtons.forEach(button => {
            button.addEventListener('click', function() {
                peopleCountButtons.forEach(btn => btn.classList.remove('active'));
                this.classList.add('active');
                peopleCountInput.value = this.dataset.count;
                const availablePlaces = parseInt(availablePlacesSpan.textContent);
                const selectedPlaces = parseInt(peopleCountInput.value);
                if (selectedPlaces > availablePlaces) {
                    showMessage(`Доступно только ${availablePlaces} мест`, 'error');
                    peopleCountInput.value = availablePlaces;
                    peopleCountButtons.forEach(btn => btn.classList.remove('active'));
                    peopleCountButtons[availablePlaces - 1].classList.add('active');
                } else {
                    hideMessage();
                }
                updateTotalPrice();
            });
        });
    }

    // Обработка выбора тура
    if (tourSelect) {
        tourSelect.addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            let dates = [];
            let prices = [];

            try {
                dates = JSON.parse(selectedOption.getAttribute('data-dates') || '[]');
                prices = JSON.parse(selectedOption.getAttribute('data-prices') || '[]');
            } catch (error) {
                console.error('Error parsing dates or prices:', error);
                showMessage('Ошибка загрузки данных тура.', 'error');
                return;
            }

            console.log('Выбран тур:', selectedOption.text);
            console.log('Распарсенные данные:', { dates, prices });

            // Очищаем и отключаем селект дат
            dateSelect.innerHTML = '<option value="">Выберите период</option>';
            dateSelect.disabled = true;
            availablePlacesSpan.textContent = '6';
            pricePerPersonSpan.textContent = '0';
            totalPriceSpan.textContent = '0';
            hideMessage();

            // Проверяем наличие дат
            if (dates.length === 0) {
                showMessage('Для этого тура нет доступных дат.', 'error');
                return;
            }

            // Включаем селект и заполняем даты
            dateSelect.disabled = false;
            dates.forEach(date => {
                console.log('Обработка даты:', date);
                const option = document.createElement('option');
                option.value = date.id;
                option.text = formatDateRange(date.start_date, date.end_date);
                dateSelect.appendChild(option);
            });

            // Обновляем цену
            if (prices.length > 0) {
                pricePerPersonSpan.textContent = parseFloat(prices[0].sale_price) || 0;
                updateTotalPrice();
            }
        });
    }

    // Обработка выбора даты
    if (dateSelect) {
        dateSelect.addEventListener('change', function() {
            const dateId = this.value;
            let prices = [];

            try {
                prices = JSON.parse(tourSelect.options[tourSelect.selectedIndex].getAttribute('data-prices') || '[]');
            } catch (error) {
                console.error('Error parsing prices:', error);
                showMessage('Ошибка загрузки цен.', 'error');
                return;
            }

            hideMessage();

            if (dateId) {
                fetch(`/api/available-places/${dateId}`, {
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error(`HTTP error! status: ${response.status}`);
                        }
                        return response.json();
                    })
                    .then(data => {
                        availablePlacesSpan.textContent = data.available_places;

                        // Ограничиваем выбор количества человек
                        peopleCountButtons.forEach(button => {
                            const count = parseInt(button.dataset.count);
                            button.disabled = count > data.available_places;
                            if (count > data.available_places && button.classList.contains('active')) {
                                button.classList.remove('active');
                                peopleCountInput.value = '1';
                                peopleCountButtons[0].classList.add('active');
                            }
                        });

                        // Проверяем текущее количество человек
                        const selectedPlaces = parseInt(peopleCountInput.value);
                        if (selectedPlaces > data.available_places) {
                            peopleCountInput.value = data.available_places;
                            peopleCountButtons.forEach(btn => btn.classList.remove('active'));
                            peopleCountButtons[data.available_places - 1].classList.add('active');
                            showMessage(`Доступно только ${data.available_places} мест`, 'error');
                        }

                        // Обновляем цену
                        const price = prices.length > 0 ? parseFloat(prices[0].sale_price) : 0;
                        pricePerPersonSpan.textContent = price;
                        updateTotalPrice();
                    })
                    .catch(error => {
                        console.error('Error fetching available places:', error);
                        availablePlacesSpan.textContent = '6';
                        showMessage('Не удалось загрузить доступные места. Попробуйте снова.', 'error');
                    });
            } else {
                availablePlacesSpan.textContent = '6';
                pricePerPersonSpan.textContent = '0';
                totalPriceSpan.textContent = '0';
            }
        });
    }

    // Обновление итоговой стоимости
    function updateTotalPrice() {
        const peopleCount = parseInt(peopleCountInput.value) || 1;
        const pricePerPerson = parseFloat(pricePerPersonSpan.textContent) || 0;
        totalPriceSpan.textContent = (peopleCount * pricePerPerson).toFixed(0);
    }

    // Обработка отправки формы
    let isSubmitting = false;

    if (bookingForm) {
        bookingForm.addEventListener('submit', function(event) {
            event.preventDefault();

            if (isSubmitting) return;
            isSubmitting = true;

            const formData = new FormData(this);
            const submitButton = this.querySelector('button[type="submit"]');
            const availablePlaces = parseInt(availablePlacesSpan.textContent);
            const selectedPlaces = parseInt(peopleCountInput.value);

            if (!tourSelect.value || !dateSelect.value || !peopleCountInput.value) {
                showMessage('Пожалуйста, заполните все поля', 'error');
                isSubmitting = false;
                submitButton.disabled = false;
                return;
            }

            if (selectedPlaces > availablePlaces) {
                showMessage(`Доступно только ${availablePlaces} мест`, 'error');
                isSubmitting = false;
                submitButton.disabled = false;
                return;
            }

            submitButton.disabled = true;
            submitButton.textContent = 'Обработка...';

            fetch(this.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            })
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    isSubmitting = false;
                    submitButton.disabled = false;
                    submitButton.textContent = 'Забронировать';

                    if (data.success) {
                        showMessage(data.message, 'success');
                        bookingForm.reset();
                        bookingModal.style.display = 'none';
                        availablePlacesSpan.textContent = '6';
                        pricePerPersonSpan.textContent = '0';
                        totalPriceSpan.textContent = '0';
                        dateSelect.innerHTML = '<option value="">Сначала выберите тур</option>';
                        dateSelect.disabled = true;
                        peopleCountButtons.forEach(btn => btn.classList.remove('active'));
                        peopleCountButtons[0].classList.add('active');
                        peopleCountInput.value = '1';
                    } else {
                        showMessage(data.message || 'Произошла ошибка при создании бронирования', 'error');
                    }
                })
                .catch(error => {
                    isSubmitting = false;
                    submitButton.disabled = false;
                    submitButton.textContent = 'Забронировать';
                    console.error('Ошибка бронирования:', error);
                    showMessage('Произошла ошибка при бронировании. Попробуйте снова.', 'error');
                });
        });
    }

    // Закрытие модального окна
    const closeModal = document.querySelector('#bookingModal .close-modal');
    if (closeModal) {
        closeModal.addEventListener('click', function() {
            bookingModal.style.display = 'none';
        });
    }

    if (bookingModal) {
        window.addEventListener('click', function(event) {
            if (event.target === bookingModal) {
                bookingModal.style.display = 'none';
            }
        });
    }
});
