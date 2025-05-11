<div class="modal" id="bookingModal">
    <div class="modal-content">
        <span class="close-modal">&times;</span>
        <h2>Бронирование тура</h2>
        <form id="bookingForm" method="POST" action="{{ route('booking.store') }}">
            @csrf
            <div class="form-group">
                <label for="tour_id">Выберите тур</label>
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
                <label for="date_id">Выберите период</label>
                <select id="date_id" name="date_id" required disabled>
                    <option value="">Сначала выберите тур</option>
                </select>
            </div>

            <div class="form-group">
                <label for="people_count">Количество человек</label>
                <input type="number" id="people_count" name="people_count" min="1" max="6" required>
                <span class="available-places">Доступно мест: <span id="available_places">6</span></span>
            </div>

            <div class="price-info">
                <div class="form-group">
                    <label>Цена за человека:</label>
                    <span id="price_per_person">0</span> ₽
                </div>
                <div class="form-group">
                    <label>Итоговая стоимость:</label>
                    <span id="total_price">0</span> ₽
                </div>
            </div>

            <button type="submit" class="btn btn-primary">Забронировать</button>
        </form>
    </div>
</div> 