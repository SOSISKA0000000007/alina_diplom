@extends('layouts.hf')

@section('content')
    <div class="container">
        <div class="admin-layout">
            @include('components.admin-sidebar')

            <div class="bookings-wrapper">
                <h2>Управление бронированиями</h2>

                <div class="bookings-table">
                    <table>
                        <tbody>
                        @forelse($bookings as $booking)
                            <tr>
                                <td>
                                    <span class="label">ID</span><br><br>
                                    {{ $booking->id }}
                                </td>
                                <td>
                                    <span class="label">Пользователь</span><br><br>
                                    {{ $booking->user->name }}
                                </td>
                                <td>
                                    <span class="label">Тур</span><br><br>
                                    {{ $booking->tour->title }}
                                </td>
                                <td>
                                    <span class="label">Период</span><br><br>
                                    {{ \Carbon\Carbon::parse($booking->tourDate->start_date)->format('d.m.Y') }} -
                                    {{ \Carbon\Carbon::parse($booking->tourDate->end_date)->format('d.m.Y') }}
                                </td>
                                <td>
                                    <span class="label">Количество человек</span><br><br>
                                    {{ $booking->people_count }} {{ trans_choice('человек|человека|человек', $booking->people_count) }}
                                </td>
                                <td>
                                    <span class="label">Итоговая стоимость</span><br><br>
                                    {{ number_format($booking->people_count * $booking->tour->prices->first()->regular_price, 0, ',', ' ') }} ₽
                                </td>
                                <td>
                                    <span class="label">Статус</span><br><br>
                                    @switch($booking->status)
                                        @case('pending')
                                            Ожидает подтверждения
                                            @break
                                        @case('confirmed')
                                            Подтверждено
                                            @break
                                        @case('rejected')
                                            Отклонено
                                            @break
                                    @endswitch
                                </td>
                                <td>
                                    @if($booking->status === 'pending')
                                        <form action="{{ route('admin.bookings.confirm', $booking) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="admin-form-button">Подтвердить</button>
                                        </form>
                                        <form action="{{ route('admin.bookings.reject', $booking) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="admin-form-button">Отклонить</button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="no-bookings">Нет бронирований</td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
