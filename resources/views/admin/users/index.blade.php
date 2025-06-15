@extends('layouts.hf')

@section('content')
    <div class="container">
        <h1>Список пользователей</h1>

        <!-- Кнопки фильтрации -->
        <div class="mb-3">
            <a href="{{ route('admin.users.index', ['status' => 'confirmed']) }}"
               class="btn {{ $filter === 'confirmed' ? 'btn-primary' : 'btn-outline-primary' }} me-2">Подтверждено</a>
            <a href="{{ route('admin.users.index', ['status' => 'pending']) }}"
               class="btn {{ $filter === 'pending' ? 'btn-primary' : 'btn-outline-primary' }} me-2">Ожидает</a>
            <a href="{{ route('admin.users.index', ['status' => 'no_bookings']) }}"
               class="btn {{ $filter === 'no_bookings' ? 'btn-primary' : 'btn-outline-primary' }}">Нет</a>
            <a href="{{ route('admin.users.index') }}"
               class="btn {{ $filter === 'all' ? 'btn-primary' : 'btn-outline-primary' }} ms-2">Все</a>
        </div>

        <!-- Таблица пользователей -->
        @if($users->isEmpty())
            <p>Пользователи не найдены.</p>
        @else
            <table class="table table-bordered table-striped">
                <thead>
                <tr>
                    <th>Имя</th>
                    <th>Телефон</th>
                    <th>Email</th>
                    <th>Тур</th>
                    <th>Дата начала</th>
                    <th>Дата окончания</th>
                    <th>Количество человек</th>
                    <th>Стоимость</th>
                    <th>Статус</th>
                </tr>
                </thead>
                <tbody>
                @foreach($users as $user)
                    @if($filter === 'no_bookings' || $user->bookings->isEmpty())
                        <tr>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->phone ?? 'Не указан' }}</td>
                            <td>{{ $user->email }}</td>
                            <td colspan="6">Бронирований нет</td>
                        </tr>
                    @else
                        @foreach($user->bookings as $booking)
                            @if($filter === 'all' || $booking->status === $filter)
                                <tr>
                                    <td>{{ $user->name }}</td>
                                    <td>{{ $user->phone ?? 'Не указан' }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>{{ $booking->tour->title ?? 'Тур не найден' }}</td>
                                    <td>{{ $booking->tourDate ? \Carbon\Carbon::parse($booking->tourDate->start_date)->translatedFormat('d F Y') : 'Не указана' }}</td>
                                    <td>{{ $booking->tourDate ? \Carbon\Carbon::parse($booking->tourDate->end_date)->translatedFormat('d F Y') : 'Не указана' }}</td>
                                    <td>{{ $booking->number_of_people ?? 'Не указано' }}</td>
                                    <td>{{ number_format($booking->total_price, 0, ',', ' ') }} ₽</td>
                                    <td>
                                        @if($booking->status === 'pending')
                                            <span class="badge bg-warning">Ожидает</span>
                                        @elseif($booking->status === 'confirmed')
                                            <span class="badge bg-success">Подтверждено</span>
                                        @elseif($booking->status === 'rejected')
                                            <span class="badge bg-danger">Отклонено</span>
                                        @else
                                            {{ $booking->status }}
                                        @endif
                                    </td>
                                </tr>
                            @endif
                        @endforeach
                    @endif
                @endforeach
                </tbody>
            </table>
        @endif
    </div>
@endsection
