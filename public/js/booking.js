
document.addEventListener('DOMContentLoaded', function() {

    const bookingModal = document.getElementById('bookingModal');
    const bookingForm = document.getElementById('bookingForm');
    const tourSelect = document.getElementById('tour_id');
    const dateSelect = document.getElementById('date_id');
    const peopleCountInput = document.getElementById('people_count');
    const availablePlacesSpan = document.getElementById('available_places');
    const pricePerPersonSpan = document.getElementById('price_per_person');
    const totalPriceSpan = document.getElementById('total_price');
    const bookButton = document.querySelector('.nav-button');
    const errorMessage = document.createElement('div');
    errorMessage.className = 'error-message';
    bookingForm.appendChild(errorMessage);


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
        bookButton
    });

    // Обработчик клика по кнопке "Забронировать" в шапке
    bookButton.addEventListener('click', function() {
        console.log('Открытие модального окна');
        bookingModal.style.display = 'block';
    });

    // Обработчик изменения выбора тура
    tourSelect.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        console.log('Выбран тур:', selectedOption.value);
        console.log('Атрибуты опции:', {
            dates: selectedOption.getAttribute('data-dates'),
            prices: selectedOption.getAttribute('data-prices')
        });

        try {
            const dates = JSON.parse(selectedOption.getAttribute('data-dates') || '[]');
            const prices = JSON.parse(selectedOption.getAttribute('data-prices') || '[]');

            console.log('Распарсенные данные:', {
                dates,
                prices
            });

            // Очищаем и заполняем select с датами
            dateSelect.innerHTML = '<option value="">Выберите период</option>';
            dateSelect.disabled = dates.length === 0;

            if (dates.length > 0) {
                dates.forEach(date => {
                    console.log('Обработка даты:', date);
                    const option = document.createElement('option');
                    option.value = date.id;
                    const startDate = new Date(date.start_date);
                    const endDate = new Date(date.end_date);
                    option.textContent = `${startDate.toLocaleDateString()} - ${endDate.toLocaleDateString()}`;
                    dateSelect.appendChild(option);
                });
                dateSelect.disabled = false;
            }

            // Обновляем цены
            if (prices.length > 0) {
                const price = prices[0];
                console.log('Установка цены:', price);
                pricePerPersonSpan.textContent = price.regular_price;
                updateTotalPrice();
            }
        } catch (error) {
            console.error('Ошибка при обработке данных тура:', error);
        }
    });

    // Обработчик изменения выбора даты
    dateSelect.addEventListener('change', function() {
        if (this.value) {
            fetch(`/api/available-places/${this.value}`)
                .then(response => response.json())
                .then(data => {
                    availablePlacesSpan.textContent = data.available_places;
                    peopleCountInput.max = data.available_places;
                    if (parseInt(peopleCountInput.value) > data.available_places) {
                        peopleCountInput.value = data.available_places;
                        updateTotalPrice();
                    }
                });
        }
    });

    // Обработчик изменения количества человек
    peopleCountInput.addEventListener('change', function() {
        const availablePlaces = parseInt(availablePlacesSpan.textContent);
        const selectedPlaces = parseInt(this.value);

        if (selectedPlaces > availablePlaces) {
            errorMessage.textContent = `Доступно только ${availablePlaces} мест`;
            errorMessage.style.display = 'block';
            this.value = availablePlaces;
        } else {
            errorMessage.style.display = 'none';
        }

        updateTotalPrice();
    });

    // Функция обновления итоговой цены
    function updateTotalPrice() {
        const peopleCount = parseInt(peopleCountInput.value) || 0;
        const pricePerPerson = parseInt(pricePerPersonSpan.textContent) || 0;
        const totalPrice = peopleCount * pricePerPerson;
        totalPriceSpan.textContent = totalPrice;
    }

    // Обработчик отправки формы
    bookingForm.addEventListener('submit', function(event) {
        event.preventDefault();

        const formData = new FormData(this);
        const availablePlaces = parseInt(availablePlacesSpan.textContent);
        const selectedPlaces = parseInt(peopleCountInput.value);

        if (selectedPlaces > availablePlaces) {
            errorMessage.textContent = `Доступно только ${availablePlaces} мест`;
            errorMessage.style.display = 'block';
            return;
        }

        if (!tourSelect.value || !dateSelect.value || !peopleCountInput.value) {
            errorMessage.textContent = 'Пожалуйста, заполните все поля';
            errorMessage.style.display = 'block';
            return;
        }

        errorMessage.style.display = 'none';

        // fetch(this.action, {
        //     method: 'POST',
        //     body: formData,
        //     headers: {
        //         'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        //     }
        // })
        // .then(response => response.json())
        // .then(data => {
        //     if (data.success) {
        //         bookingModal.style.display = 'none';
        //         bookingForm.reset();
        //         alert('Бронирование успешно создано!');
        //     } else {
        //         errorMessage.textContent = data.message || 'Произошла ошибка при создании бронирования';
        //         errorMessage.style.display = 'block';
        //     }
        // })
        // .catch(error => {
        //     console.error('Ошибка:', error);
        //     errorMessage.textContent = 'Произошла ошибка при создании бронирования';
        //     errorMessage.style.display = 'block';
        // });
    });

    // Закрытие модального окна
    const closeModal = document.querySelector('#bookingModal .close-modal');
    closeModal.addEventListener('click', function() {
        bookingModal.style.display = 'none';
    });

    window.addEventListener('click', function(event) {
        if (event.target === bookingModal) {
            bookingModal.style.display = 'none';
        }
    });
});
