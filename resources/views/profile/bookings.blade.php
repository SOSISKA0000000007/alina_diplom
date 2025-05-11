@extends('profile.layout')

@section('profile-content')
<div class="bookings-wrapper">
    <h2>Забронированные туры</h2>

    <div class="bookings-table">
        <table>
            <thead>
                <tr>
                    <th>Название тура</th>
                    <th>Период</th>
                    <th>Кол-во человек</th>
                    <th>Стоимость тура</th>
                    <th>Итоговая стоимость</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse($bookings as $booking)
                    <tr>
                        <td>{{ $booking->tour->title }}</td>
                        <td>
                            {{ \Carbon\Carbon::parse($booking->tourDate->start_date)->format('d.m.Y') }}
                            -
                            {{ \Carbon\Carbon::parse($booking->tourDate->end_date)->format('d.m.Y') }}
                        </td>
                        <td>{{ $booking->people_count }} {{ trans_choice('человек|человека|человек', $booking->people_count) }}</td>
                        <td>{{ number_format($booking->tour->prices->first()->regular_price, 0, ',', ' ') }} ₽</td>
                        <td>{{ number_format($booking->people_count * $booking->tour->prices->first()->regular_price, 0, ',', ' ') }} ₽</td>
                        <td>
                            <form method="POST" action="{{ route('booking.cancel', $booking->id) }}" class="cancel-booking-form">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-cancel">ОТМЕНИТЬ</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="no-bookings">У вас нет забронированных туров</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
