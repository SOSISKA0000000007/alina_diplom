@extends('layouts.hf')

@section('content')
    <div class="container">
        <div class="admin-layout">
            @include('components.admin-sidebar')

            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif

            <div class="bookings-wrapper">
                <h2>Управление турами</h2>

                <div class="bookings-table">
                    <table>
                        <tbody>
                        @forelse($tours as $tour)
                            <tr>
                                <td>
                                    <span class="label">Название</span><br><br>
                                    {{ $tour->title }}
                                </td>
                                <td>
                                    <span class="label">Даты</span><br><br>
                                    <div class="dates-list">
                                        @foreach($tour->dates as $date)
                                            <div class="date-item">
                                                <span>{{ \Carbon\Carbon::parse($date->start_date)->format('d.m.Y') }} - {{ \Carbon\Carbon::parse($date->end_date)->format('d.m.Y') }}</span>
                                                <form action="{{ route('admin.tours.dates.destroy', ['tour' => $tour->id, 'date' => $date->id]) }}" method="POST" class="delete-form">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger">×</button>
                                                </form>
                                            </div>
                                        @endforeach
                                    </div>
                                    <button class="btn btn-primary add-date" data-tour-id="{{ $tour->id }}">Добавить дату</button>
                                </td>
                                <td>
                                    <span class="label">Цены</span><br><br>
                                    <div class="prices-list">
                                        @if($tour->prices->isNotEmpty())
                                            @foreach($tour->prices as $price)
                                                <div class="price-item">
                                                    <span>Обычная: {{ number_format($price->regular_price, 0, ',', ' ') }} ₽</span>
                                                    <span>Со скидкой: {{ number_format($price->sale_price, 0, ',', ' ') }} ₽</span>
                                                    <form action="{{ route('admin.tours.prices.destroy', ['tour' => $tour->id, 'price' => $price->id]) }}" method="POST" class="delete-form">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger">×</button>
                                                    </form>
                                                </div>
                                                <button class="btn btn-primary edit-price"
                                                        data-tour-id="{{ $tour->id }}"
                                                        data-price-id="{{ $price->id }}"
                                                        data-regular-price="{{ $price->regular_price }}"
                                                        data-sale-price="{{ $price->sale_price }}">Редактировать</button>
                                            @endforeach
                                        @else
                                            <button class="btn btn-primary edit-price" data-tour-id="{{ $tour->id }}">Добавить цены</button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="no-bookings">Нет туров</td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal for adding a date -->
    <div id="addDateModal" class="modal">
        <div class="modal-content">
            <span class="close-modal">×</span>
            <h2>Добавить даты тура</h2>
            <form id="addDateForm" method="POST" class="admin-form">
                @csrf
                <input type="hidden" name="tour_id" id="tour_id">
                <div class="admin-form-container">
                    <label for="start_date" class="admin-label">Дата начала</label>
                    <input type="date" id="start_date" name="start_date" class="admin-input" required>
                </div>
                <div class="admin-form-container">
                    <label for="end_date" class="admin-label">Дата окончания</label>
                    <input type="date" id="end_date" name="end_date" class="admin-input" required>
                </div>
                <div class="admin-form-container">
                    <button type="submit" class="btn btn-primary">Сохранить</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal for editing prices -->
    <div id="editPriceModal" class="modal">
        <div class="modal-content">
            <span class="close-modal">×</span>
            <h2>Редактировать цены тура</h2>
            <form id="editPriceForm" method="POST" class="admin-form">
                @csrf
                <input type="hidden" name="_method" id="form_method" value="PUT">
                <input type="hidden" name="tour_id" id="price_tour_id">
                <input type="hidden" name="price_id" id="price_id">
                <div class="admin-form-container">
                    <label for="regular_price" class="admin-label">Обычная цена</label>
                    <input type="number" id="regular_price" name="regular_price" class="admin-input" required>
                </div>
                <div class="admin-form-container">
                    <label for="sale_price" class="admin-label">Цена со скидкой</label>
                    <input type="number" id="sale_price" name="sale_price" class="admin-input">
                </div>
                <div class="admin-form-container">
                    <button type="submit" class="btn btn-primary">Сохранить</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const addDateButtons = document.querySelectorAll('.add-date');
            const editPriceButtons = document.querySelectorAll('.edit-price');
            const addDateModal = document.getElementById('addDateModal');
            const editPriceModal = document.getElementById('editPriceModal');
            const closeModalButtons = document.querySelectorAll('.close-modal');
            const addDateForm = document.getElementById('addDateForm');
            const editPriceForm = document.getElementById('editPriceForm');

            // Open Add Date Modal
            addDateButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const tourId = this.getAttribute('data-tour-id');
                    document.getElementById('tour_id').value = tourId;
                    addDateForm.action = `{{ route('admin.tours.dates.store', ['tour' => ':tour']) }}`.replace(':tour', tourId);
                    addDateModal.style.display = 'block';
                });
            });

            // Open Edit Price Modal
            editPriceButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const tourId = this.getAttribute('data-tour-id');
                    const priceId = this.getAttribute('data-price-id') || '';
                    const regularPrice = this.getAttribute('data-regular-price') || '';
                    const salePrice = this.getAttribute('data-sale-price') || '';

                    document.getElementById('price_tour_id').value = tourId;
                    document.getElementById('price_id').value = priceId;
                    document.getElementById('regular_price').value = regularPrice;
                    document.getElementById('sale_price').value = salePrice;

                    const formMethod = document.getElementById('form_method');
                    if (priceId) {
                        editPriceForm.action = `{{ route('admin.tours.prices.update', ['tour' => ':tour', 'price' => ':price']) }}`
                            .replace(':tour', tourId).replace(':price', priceId);
                        formMethod.value = 'PUT';
                    } else {
                        editPriceForm.action = `{{ route('admin.tours.prices.store', ['tour' => ':tour']) }}`.replace(':tour', tourId);
                        formMethod.value = 'POST';
                    }
                    console.log('Form action set to:', editPriceForm.action, 'Method:', formMethod.value);
                    editPriceModal.style.display = 'block';
                });
            });

            // Handle Edit Price Form Submission
            editPriceForm.addEventListener('submit', function(e) {
                e.preventDefault();
                const formData = new FormData(this);
                const method = document.getElementById('form_method').value;

                console.log('Sending request to:', this.action, 'Method:', method);

                fetch(this.action, {
                    method: method === 'PUT' ? 'POST' : method, // Laravel обрабатывает PUT через _method
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json'
                    }
                })
                    .then(response => {
                        console.log('Response status:', response.status);
                        if (!response.ok) {
                            throw new Error(`HTTP error! Status: ${response.status}`);
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (data.success) {
                            alert(data.message);
                            window.location.reload();
                        } else {
                            alert('Ошибка: ' + (data.message || 'Не удалось сохранить изменения'));
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Произошла ошибка при сохранении: ' + error.message);
                    });
            });

            // Close Modals
            closeModalButtons.forEach(button => {
                button.addEventListener('click', function() {
                    addDateModal.style.display = 'none';
                    editPriceModal.style.display = 'none';
                });
            });

            // Close Modal on Outside Click
            window.addEventListener('click', function(event) {
                if (event.target === addDateModal) {
                    addDateModal.style.display = 'none';
                }
                if (event.target === editPriceModal) {
                    editPriceModal.style.display = 'none';
                }
            });
        });
    </script>
@endsection
