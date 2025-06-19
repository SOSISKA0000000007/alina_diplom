@extends('layouts.hf')

@section('content')
    <div class="container">
        <div class="admin-layout">
            @include('components.admin-sidebar')

            <div class="bookings-wrapper">
                <h2>Список пользователей</h2>

                <!-- Кнопки фильтрации -->
                <div class="filter-user">
                    <a href="{{ route('admin.users.index', ['status' => 'confirmed']) }}"
                       class="btn {{ $filter === 'confirmed' ?  'btn-outline-primary':  'btn-primary-filter'}}">Подтверждено</a>
                    <a href="{{ route('admin.users.index', ['status' => 'pending']) }}"
                       class="btn {{ $filter === 'pending' ? 'btn-outline-primary'  : 'btn-primary-filter'}} ">Ожидает</a>
                    <a href="{{ route('admin.users.index', ['status' => 'no_bookings']) }}"
                       class="btn {{ $filter === 'no_bookings' ? 'btn-outline-primary' : 'btn-primary-filter' }}">Нет</a>
                    <a href="{{ route('admin.users.index') }}"
                       class="btn {{ $filter === 'all' ? 'btn-outline-primary' : 'btn-primary-filter' }}">Все</a>
                </div>

                <!-- Таблица пользователей -->
                <div class="bookings-table">
                    @if($users->isEmpty())
                        <p class="no-bookings">Пользователи не найдены.</p>
                    @else
                        <table>
                            <tbody>
                            @foreach($users as $user)
                                @if($filter === 'no_bookings' || $user->bookings->isEmpty())
                                    <tr>
                                        <td>
                                            <span class="label">Имя</span><br><br>
                                            {{ $user->name }}
                                        </td>
                                        <td>
                                            <span class="label">Телефон</span><br><br>
                                            {{ $user->phone ?? 'Не указан' }}
                                        </td>
                                        <td>
                                            <span class="label">Email</span><br><br>
                                            {{ $user->email }}
                                        </td>
                                        <td colspan="6">
                                            <span class="label">Бронирования</span><br><br>
                                            Бронирований нет
                                        </td>
                                    </tr>
                                @else
                                    @foreach($user->bookings as $booking)
                                        @if($filter === 'all' || $booking->status === $filter)
                                            <tr>
                                                <td>
                                                    <span class="label">Имя</span><br><br>
                                                    {{ $user->name }}
                                                </td>
                                                <td>
                                                    <span class="label">Телефон</span><br><br>
                                                    {{ $user->phone ?? 'Не указан' }}
                                                </td>
                                                <td>
                                                    <span class="label">Email</span><br><br>
                                                    {{ $user->email }}
                                                </td>
                                                <td>
                                                    <span class="label">Тур</span><br><br>
                                                    {{ $booking->tour->title ?? 'Тур не найден' }}
                                                </td>
                                                <td>
                                                    <span class="label">Дата начала</span><br><br>
                                                    {{ $booking->tourDate ? \Carbon\Carbon::parse($booking->tourDate->start_date)->translatedFormat('d.m.Y') : 'Не указана' }}
                                                </td>
                                                <td>
                                                    <span class="label">Дата окончания</span><br><br>
                                                    {{ $booking->tourDate ? \Carbon\Carbon::parse($booking->tourDate->end_date)->translatedFormat('d.m.Y') : 'Не указана' }}
                                                </td>
                                                <td>
                                                    <span class="label">Количество человек</span><br><br>
                                                    {{ $booking->people_count ?? 'Не указано' }}
                                                </td>
                                                <td>
                                                    <span class="label">Стоимость</span><br><br>
                                                    {{ number_format($booking->total_price, 0, ',', ' ') }} ₽
                                                </td>
                                                <td>
                                                    <span class="label">Статус</span><br><br>
                                                    @if($booking->status === 'pending')
                                                        Ожидает подтверждения
                                                    @elseif($booking->status === 'confirmed')
                                                        Подтверждено
                                                    @elseif($booking->status === 'rejected')
                                                        Отклонено
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
            </div>
        </div>
    </div>
    <style>
        .btn-outline-primary{
            text-decoration: underline;
            color: var(--black);
            font-size: 18px;
            font-family: com-reg;
        }
        .btn-primary-filter{
            text-decoration: none;
            color: var(--black);
            font-size: 18px;
            font-family: com-reg;
        }
        .filter-user{
            margin-bottom: 30px;
            display: flex;
            gap: 10px;
        }
        .filter-user a{
            margin: 0;
        }
    </style>
@endsection
