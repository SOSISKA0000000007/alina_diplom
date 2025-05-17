<!DOCTYPE html>
<html>
<head>
    <title>Управление бронированиями</title>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body>
@extends('layouts.hf')

@section('content')
    <div class="container">
        <h1>Управление бронированиями</h1>

        <div class="table-responsive">
            <table class="table">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Пользователь</th>
                    <th>Тур</th>
                    <th>Период</th>
                    <th>Количество человек</th>
                    <th>Стоимость</th>
                    <th>Статус</th>
                    <th>Действия</th>
                </tr>
                </thead>
                <tbody>
                @foreach($bookings as $booking)
                    <tr>
                        <td>{{ $booking->id }}</td>
                        <td>{{ $booking->user->name }}</td>
                        <td>{{ $booking->tour->title }}</td>
                        <td>
                            {{ $booking->tourDate->start_date->format('d.m.Y') }} -
                            {{ $booking->tourDate->end_date->format('d.m.Y') }}
                        </td>
                        <td>{{ $booking->people_count }}</td>
                        <td>
                            {{ number_format($booking->people_count * $booking->tour->prices->first()->regular_price, 0, ',', ' ') }} ₽
                        </td>
                        <td>
                            @switch($booking->status)
                                @case('pending')
                                    <span class="badge bg-warning">Ожидает подтверждения</span>
                                    @break
                                @case('confirmed')
                                    <span class="badge bg-success">Подтверждено</span>
                                    @break
                                @case('rejected')
                                    <span class="badge bg-danger">Отклонено</span>
                                    @break
                            @endswitch
                        </td>
                        <td>
                            @if($booking->status === 'pending')
                                <form action="{{ route('admin.bookings.confirm', $booking) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-success btn-sm">Подтвердить</button>
                                </form>
                                <form action="{{ route('admin.bookings.reject', $booking) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-danger btn-sm">Отклонить</button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
</body>
</html>
