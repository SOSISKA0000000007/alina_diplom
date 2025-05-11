@extends('layouts.hf')

@section('content')
<div class="admin-container">
    <div class="admin-layout">
        @include('components.admin-sidebar')

        <div class="admin-content">
            <h1>Админ-панель</h1>

            <div class="admin-section">
                <h2>Управление турами</h2>
                <div class="tours-table">
                    <table>
                        <thead>
                            <tr>
                                <th>Название</th>
                                <th>Даты</th>
                                <th>Цены</th>
                                <th>Действия</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($tours as $tour)
                                <tr>
                                    <td>{{ $tour->title }}</td>
                                    <td>
                                        <div class="dates-list">
                                            @foreach($tour->dates as $date)
                                                <div class="date-item">
                                                    <span>{{ $date->start_date->format('d.m.Y') }} - {{ $date->end_date->format('d.m.Y') }}</span>
                                                    <form action="{{ route('admin.tours.dates.destroy', ['tour' => $tour->id, 'date' => $date->id]) }}" method="POST" class="delete-form">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn-delete">×</button>
                                                    </form>
                                                </div>
                                            @endforeach
                                        </div>
                                        <button class="btn-add-date" data-tour-id="{{ $tour->id }}">Добавить дату</button>
                                    </td>
                                    <td>
                                        <div class="prices-list">
                                            @if($tour->prices->isNotEmpty())
                                                @foreach($tour->prices as $price)
                                                    <div class="price-item">
                                                        <span>Обычная: {{ $price->regular_price }} ₽</span>
                                                        <span>Со скидкой: {{ $price->sale_price }} ₽</span>
                                                        <button class="btn-edit-price" data-tour-id="{{ $tour->id }}" data-price-id="{{ $price->id }}">Редактировать</button>
                                                    </div>
                                                @endforeach
                                            @else
                                                <button class="btn-edit-price" data-tour-id="{{ $tour->id }}">Добавить цены</button>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        <button class="btn-edit" data-tour-id="{{ $tour->id }}">Редактировать</button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Модальное окно для добавления даты -->
<div id="addDateModal" class="modal">
    <div class="modal-content">
        <span class="close-modal">&times;</span>
        <h2>Добавить даты тура</h2>
        <form id="addDateForm" method="POST">
            @csrf
            <input type="hidden" name="tour_id" id="tour_id">
            <div class="form-group">
                <label for="start_date">Дата начала</label>
                <input type="date" id="start_date" name="start_date" required>
            </div>
            <div class="form-group">
                <label for="end_date">Дата окончания</label>
                <input type="date" id="end_date" name="end_date" required>
            </div>
            <button type="submit" class="btn btn-primary">Сохранить</button>
        </form>
    </div>
</div>

<!-- Модальное окно для редактирования цен -->
<div id="editPriceModal" class="modal">
    <div class="modal-content">
        <span class="close-modal">&times;</span>
        <h2>Редактировать цены тура</h2>
        <form id="editPriceForm" method="POST">
            @csrf
            @method('PUT')
            <input type="hidden" name="tour_id" id="price_tour_id">
            <input type="hidden" name="price_id" id="price_id">
            <div class="form-group">
                <label for="regular_price">Обычная цена</label>
                <input type="number" id="regular_price" name="regular_price" required>
            </div>
            <div class="form-group">
                <label for="sale_price">Цена со скидкой</label>
                <input type="number" id="sale_price" name="sale_price">
            </div>
            <button type="submit" class="btn btn-primary">Сохранить</button>
        </form>
    </div>
</div>

<style>
.admin-layout {
    display: flex;
    min-height: 100vh;
}

.admin-content {
    flex: 1;
    margin-left: 250px;
    padding: 20px;
}

.admin-container {
    margin-top: 20px;
}
</style>
@endsection

@section('scripts')
<script>
    console.log('Скрипты загружены');
</script>
@endsection
