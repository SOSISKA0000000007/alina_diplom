<meta name="csrf-token" content="{{ csrf_token() }}">

<div class="modal" id="bookingModal">
    <div class="modal-content">
        <span class="close-modal">×</span>
        <h2>Бронирование тура</h2>
        <form id="bookingForm" method="POST" action="{{ route('booking.store') }}">
            @csrf
            <div id="messageContainer" class="message-container" style="display: none;"></div>

            <div class="form-group">
                <label for="tour_id">Название тура</label>
                <select id="tour_id" name="tour_id" required>
                    <option value="">Выберите тур</option>
                    @foreach($tours as $tour)
                        <option value="{{ $tour->id }}"
                                data-dates="{{ $tour->dates->toJson() }}"
                                data-prices="{{ $tour->prices->toJson() }}">
                            {{ $tour->title }}
                        </option>
                        <!-- Отладочная информация -->
                        <script>
                            console.log('Tour: {{ $tour->title }}');
                            console.log('Dates:', @json($tour->dates));
                            console.log('Prices:', @json($tour->prices));
                        </script>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label>Количество человек</label>
                <div class="people-count-buttons">
                    <input type="hidden" id="people_count" name="people_count" value="1">
                    @for($i = 1; $i <= 6; $i++)
                        <button type="button" class="btn btn-outline-primary people-count-btn {{ $i == 1 ? 'active' : '' }}" data-count="{{ $i }}">{{ $i }}</button>
                    @endfor
                </div>
                <span class="available-places">Доступно мест: <span id="available_places">6</span></span>
            </div>

            <div class="form-group">
                <label for="date_id">Период</label>
                <select id="date_id" name="date_id" required disabled>
                    <option value="">Сначала выберите тур</option>
                </select>
            </div>

            <div class="price-info">
                <div class="form-group">
                    <label>Цена турв</label>
                    <span>
                    <span id="price_per_person">0</span> ₽
                    </span>
                </div>
                <div class="form-group">
                    <label>Итоговая стоимость:</label>
                    <span>
                    <span id="total_price">0</span> ₽
                    </span>
                </div>
            </div>




            <button type="submit" class="btn btn-primary">Забронировать</button>
        </form>
    </div>
</div>

<style>
    .available-places{
        font: 16px com-reg;
    }
    .date_id{
        margin: 30px 0;
    }
    .people-count-buttons {
        display: flex;
        gap: 10px;
        margin: 10px 0;
    }

    .people-count-btn {
        margin: 0;
        padding: 8px 15px;
        border: 2px solid var(--blue);
        border-radius: 5px;
        cursor: pointer;
        transition: all 0.2s;
        color: var(--black);
        font: 16px com-reg;
    }

    .people-count-btn.active {
        background: var(--blue);
        color: white;
    }

    .people-count-btn:hover {
        background: var(--blue);
        color: white;
    }
</style>



{{--<script>--}}
{{--    document.addEventListener('DOMContentLoaded', function() {--}}
{{--        const tourSelect = document.getElementById('tour_id');--}}
{{--        const dateSelect = document.getElementById('date_id');--}}
{{--        const availablePlacesSpan = document.getElementById('available_places');--}}
{{--        const peopleCountInput = document.getElementById('people_count');--}}
{{--        const pricePerPersonSpan = document.getElementById('price_per_person');--}}
{{--        const totalPriceSpan = document.getElementById('total_price');--}}
{{--        const buttons = document.querySelectorAll('.people-count-btn');--}}
{{--        const bookingForm = document.getElementById('bookingForm');--}}
{{--        const messageContainer = document.getElementById('messageContainer');--}}

{{--        // Форматирование дат--}}
{{--        function formatDateRange(startDate, endDate) {--}}
{{--            try {--}}
{{--                const start = new Date(startDate);--}}
{{--                const end = new Date(endDate);--}}
{{--                if (isNaN(start.getTime()) || isNaN(end.getTime())) {--}}
{{--                    throw new Error('Invalid date');--}}
{{--                }--}}
{{--                const startFormatted = start.toLocaleDateString('ru-RU', { day: '2-digit', month: '2-digit', year: 'numeric' });--}}
{{--                const endFormatted = end.toLocaleDateString('ru-RU', { day: '2-digit', month: '2-digit', year: 'numeric' });--}}
{{--                return `${startFormatted} - ${endFormatted}`;--}}
{{--            } catch (error) {--}}
{{--                console.error('Error formatting dates:', startDate, endDate, error);--}}
{{--                return 'Некорректная дата';--}}
{{--            }--}}
{{--        }--}}

{{--        // Показать сообщение--}}
{{--        function showMessage(message, type) {--}}
{{--            messageContainer.textContent = message;--}}
{{--            messageContainer.className = `message-container ${type}`;--}}
{{--            messageContainer.style.display = 'block';--}}
{{--        }--}}

{{--        // Скрыть сообщение--}}
{{--        function hideMessage() {--}}
{{--            messageContainer.textContent = '';--}}
{{--            messageContainer.className = 'message-container';--}}
{{--            messageContainer.style.display = 'none';--}}
{{--        }--}}

{{--        // Обработка выбора количества человек--}}
{{--        buttons.forEach(button => {--}}
{{--            button.addEventListener('click', function() {--}}
{{--                buttons.forEach(btn => btn.classList.remove('active'));--}}
{{--                this.classList.add('active');--}}
{{--                peopleCountInput.value = this.dataset.count;--}}
{{--                updateTotalPrice();--}}
{{--                hideMessage();--}}
{{--            });--}}
{{--        });--}}

{{--        // Обработка выбора тура--}}
{{--        tourSelect.addEventListener('change', function() {--}}
{{--            const selectedOption = this.options[this.selectedIndex];--}}
{{--            let dates = [];--}}
{{--            let prices = [];--}}

{{--            try {--}}
{{--                dates = JSON.parse(selectedOption.dataset.dates || '[]');--}}
{{--                prices = JSON.parse(selectedOption.dataset.prices || '[]');--}}
{{--            } catch (error) {--}}
{{--                console.error('Error parsing dates or prices:', error);--}}
{{--                showMessage('Ошибка загрузки данных тура.', 'error');--}}
{{--                return;--}}
{{--            }--}}

{{--            console.log('Selected tour:', selectedOption.text);--}}
{{--            console.log('Dates data:', dates);--}}
{{--            console.log('Prices data:', prices);--}}

{{--            // Очищаем и отключаем селект дат--}}
{{--            dateSelect.innerHTML = '<option value="">Выберите дату</option>';--}}
{{--            dateSelect.disabled = true;--}}
{{--            availablePlacesSpan.textContent = '6';--}}
{{--            pricePerPersonSpan.textContent = '0';--}}
{{--            totalPriceSpan.textContent = '0';--}}
{{--            hideMessage();--}}

{{--            // Проверяем наличие дат--}}
{{--            if (dates.length === 0) {--}}
{{--                showMessage('Для этого тура нет доступных дат.', 'error');--}}
{{--                return;--}}
{{--            }--}}

{{--            // Включаем селект и заполняем даты--}}
{{--            dateSelect.disabled = false;--}}
{{--            dates.forEach(date => {--}}
{{--                console.log('Adding date:', date);--}}
{{--                const option = document.createElement('option');--}}
{{--                option.value = date.id;--}}
{{--                option.text = formatDateRange(date.start_date, date.end_date);--}}
{{--                dateSelect.appendChild(option);--}}
{{--            });--}}

{{--            // Обновляем цену--}}
{{--            if (prices.length > 0) {--}}
{{--                pricePerPersonSpan.textContent = parseFloat(prices[0].sale_price) || 0;--}}
{{--                updateTotalPrice();--}}
{{--            }--}}
{{--        });--}}

{{--        // Обработка выбора даты--}}
{{--        dateSelect.addEventListener('change', function() {--}}
{{--            const dateId = this.value;--}}
{{--            const selectedTourOption = tourSelect.options[tourSelect.selectedIndex];--}}
{{--            let prices = [];--}}

{{--            try {--}}
{{--                prices = JSON.parse(selectedTourOption.dataset.prices || '[]');--}}
{{--            } catch (error) {--}}
{{--                console.error('Error parsing prices:', error);--}}
{{--                showMessage('Ошибка загрузки цен.', 'error');--}}
{{--                return;--}}
{{--            }--}}

{{--            hideMessage();--}}

{{--            if (dateId) {--}}
{{--                fetch(`/api/available-places/${dateId}`, {--}}
{{--                    headers: {--}}
{{--                        'Accept': 'application/json',--}}
{{--                        'X-Requested-With': 'XMLHttpRequest',--}}
{{--                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content--}}
{{--                    }--}}
{{--                })--}}
{{--                    .then(response => {--}}
{{--                        if (!response.ok) {--}}
{{--                            throw new Error(`HTTP error! status: ${response.status}`);--}}
{{--                        }--}}
{{--                        return response.json();--}}
{{--                    })--}}
{{--                    .then(data => {--}}
{{--                        availablePlacesSpan.textContent = data.available_places;--}}

{{--                        // Ограничиваем выбор количества человек--}}
{{--                        buttons.forEach(button => {--}}
{{--                            const count = parseInt(button.dataset.count);--}}
{{--                            button.disabled = count > data.available_places;--}}
{{--                            if (count > data.available_places && button.classList.contains('active')) {--}}
{{--                                button.classList.remove('active');--}}
{{--                                peopleCountInput.value = '1';--}}
{{--                                buttons[0].classList.add('active');--}}
{{--                            }--}}
{{--                        });--}}

{{--                        // Обновляем цену--}}
{{--                        const price = prices.length > 0 ? parseFloat(prices[0].sale_price) : 0;--}}
{{--                        pricePerPersonSpan.textContent = price;--}}
{{--                        updateTotalPrice();--}}
{{--                    })--}}
{{--                    .catch(error => {--}}
{{--                        console.error('Error fetching available places:', error);--}}
{{--                        availablePlacesSpan.textContent = '6';--}}
{{--                        showMessage('Не удалось загрузить доступные места. Попробуйте снова.', 'error');--}}
{{--                    });--}}
{{--            } else {--}}
{{--                availablePlacesSpan.textContent = '6';--}}
{{--                pricePerPersonSpan.textContent = '0';--}}
{{--                totalPriceSpan.textContent = '0';--}}
{{--            }--}}
{{--        });--}}

{{--        // Обновление итоговой стоимости--}}
{{--        function updateTotalPrice() {--}}
{{--            const peopleCount = parseInt(peopleCountInput.value) || 1;--}}
{{--            const pricePerPerson = parseFloat(pricePerPersonSpan.textContent) || 0;--}}
{{--            totalPriceSpan.textContent = (peopleCount * pricePerPerson).toFixed(0);--}}
{{--        }--}}

{{--        // Обработка отправки формы--}}
{{--        let isSubmitting = false;--}}

{{--        bookingForm.addEventListener('submit', function(event) {--}}
{{--            event.preventDefault();--}}

{{--            if (isSubmitting) return;--}}
{{--            isSubmitting = true;--}}

{{--            const formData = new FormData(bookingForm);--}}
{{--            const submitButton = bookingForm.querySelector('button[type="submit"]');--}}

{{--            submitButton.disabled = true;--}}
{{--            submitButton.textContent = 'Обработка...';--}}

{{--            fetch(bookingForm.action, {--}}
{{--                method: 'POST',--}}
{{--                body: formData,--}}
{{--                headers: {--}}
{{--                    'Accept': 'application/json',--}}
{{--                    'X-Requested-With': 'XMLHttpRequest',--}}
{{--                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content--}}
{{--                }--}}
{{--            })--}}
{{--                .then(response => {--}}
{{--                    if (!response.ok) {--}}
{{--                        throw new Error(`HTTP error! status: ${response.status}`);--}}
{{--                    }--}}
{{--                    return response.json();--}}
{{--                })--}}
{{--                .then(data => {--}}
{{--                    isSubmitting = false;--}}
{{--                    submitButton.disabled = false;--}}
{{--                    submitButton.textContent = 'Забронировать';--}}

{{--                    if (data.success) {--}}
{{--                        showMessage(data.message, 'success');--}}
{{--                        bookingForm.reset();--}}
{{--                        availablePlacesSpan.textContent = '6';--}}
{{--                        pricePerPersonSpan.textContent = '0';--}}
{{--                        totalPriceSpan.textContent = '0';--}}
{{--                        dateSelect.innerHTML = '<option value="">Сначала выберите тур</option>';--}}
{{--                        dateSelect.disabled = true;--}}
{{--                        buttons.forEach(btn => btn.classList.remove('active'));--}}
{{--                        buttons[0].classList.add('active');--}}
{{--                        peopleCountInput.value = '1';--}}
{{--                    } else {--}}
{{--                        showMessage(data.message, 'error');--}}
{{--                    }--}}
{{--                })--}}
{{--                .catch(error => {--}}
{{--                    isSubmitting = false;--}}
{{--                    submitButton.disabled = false;--}}
{{--                    submitButton.textContent = 'Забронировать';--}}
{{--                    console.error('Ошибка бронирования:', error);--}}
{{--                    showMessage('Произошла ошибка при бронировании. Попробуйте снова.', 'error');--}}
{{--                });--}}
{{--        });--}}
{{--    });--}}
{{--</script>--}}
