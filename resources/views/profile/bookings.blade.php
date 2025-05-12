@extends('profile.layout')
@section('profile-content')
    <div class="bookings-wrapper">
        <h2>Забронированные туры</h2>

        <div class="bookings-table">
            <table>
                <tbody>
                @forelse($bookings as $booking)
                    <tr>
                        <td>
                            <span class="label">Название</span><br><br>
                            {{ $booking->tour->title }}
                        </td>
                        <td>
                            <span class="label">Период</span><br><br>
                            {{ \Carbon\Carbon::parse($booking->tourDate->start_date)->format('d.m.Y') }}
                            -
                            {{ \Carbon\Carbon::parse($booking->tourDate->end_date)->format('d.m.Y') }}
                        </td>
                        <td>
                            <span class="label">Размер</span><br><br>
                            {{ $booking->people_count }} {{ trans_choice('человек|человека|человек', $booking->people_count) }}
                        </td>
                        <td>
                            <span class="label">Стоимость</span><br><br>
                            {{ number_format($booking->tour->prices->first()->regular_price, 0, ',', ' ') }} ₽
                        </td>
                        <td>
                            <span class="label">Итоговая стоимость</span><br><br>
                            {{ number_format($booking->people_count * $booking->tour->prices->first()->regular_price, 0, ',', ' ') }} ₽
                        </td>
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
