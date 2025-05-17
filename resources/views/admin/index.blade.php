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
                                                </div>
                                                    <button class="btn btn-primary edit-price" data-tour-id="{{ $tour->id }}" data-price-id="{{ $price->id }}">Редактировать</button>

                                            @endforeach
                                        @else
                                            <button class="btn btn-primary edit-price" data-tour-id="{{ $tour->id }}">Добавить цены</button>
                                        @endif
                                    </div>
                                </td>
{{--                                <td>--}}
{{--                                    <span class="label">Действия</span><br><br>--}}
{{--                                    <button class="btn btn-primary edit-tour" data-tour-id="{{ $tour->id }}">Редактировать</button>--}}
{{--                                </td>--}}
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
                @method('PUT')
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
            const editTourButtons = document.querySelectorAll('.edit-tour');
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
                    addDateForm.action = `{{ url('admin/tours') }}/${tourId}/dates`;
                    addDateModal.style.display = 'block';
                });
            });

            // Open Edit Price Modal
            editPriceButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const tourId = this.getAttribute('data-tour-id');
                    const priceId = this.getAttribute('data-price-id') || '';
                    document.getElementById('price_tour_id').value = tourId;
                    document.getElementById('price_id').value = priceId;
                    editPriceForm.action = priceId ? `{{ url('admin/tours') }}/${tourId}/prices/${priceId}` : `{{ url('admin/tours') }}/${tourId}/prices`;
                    editPriceModal.style.display = 'block';
                });
            });

            // Open Edit Tour (assuming redirect or modal, redirect for simplicity)
            editTourButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const tourId = this.getAttribute('data-tour-id');
                    window.location.href = `{{ url('admin/tours') }}/${tourId}/edit`;
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
