@extends('layouts.hf')

@section('content')
    <div class="container">
        <div class="admin-layout">
            @include('components.admin-sidebar')

            <div class="bookings-wrapper">
                <h2>Управление арендами</h2>

                <div class="bookings-table">
                    <table>
                        <tbody>
                        @forelse($rentals as $rental)
                            <tr>
                                <td>
                                    <span class="label">ID</span><br><br>
                                    {{ $rental->id }}
                                </td>
                                <td>
                                    <span class="label">Пользователь</span><br><br>
                                    {{ $rental->user->name }}
                                </td>
                                <td>
                                    <span class="label">Товар</span><br><br>
                                    {{ $rental->product->name }}
                                </td>
                                <td>
                                    <span class="label">Размер</span><br><br>
                                    {{ strtoupper($rental->size) }}
                                </td>
                                <td>
                                    <span class="label">Дата начала</span><br><br>
                                    {{ \Carbon\Carbon::parse($rental->start_date)->format('d.m.Y') }}
                                </td>
                                <td>
                                    <span class="label">Дата окончания</span><br><br>
                                    {{ \Carbon\Carbon::parse($rental->end_date)->format('d.m.Y') }}
                                </td>
                                <td>
                                    <span class="label">Стоимость</span><br><br>
                                    {{ number_format($rental->total_price, 0, ',', ' ') }} ₽
                                </td>
                                <td>
                                    <span class="label">Статус</span><br><br>
                                    @switch($rental->status)
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
                                    @if($rental->status === 'pending')
                                        <form action="{{ route('admin.rentals.confirm', $rental) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="admin-form-button">Подтвердить</button>
                                        </form>
                                        <form action="{{ route('admin.rentals.reject', $rental) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="admin-form-button">Отклонить</button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="no-bookings">Нет аренд</td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
