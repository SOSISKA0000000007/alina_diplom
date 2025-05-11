@extends('profile.layout')

@section('profile-content')
    <div class="rentals-wrapper">
        <h2>Аренда аксессуаров</h2>

        <div class="rentals-table">
            <table>
                <thead>
                <tr>
                    <th>Тип</th>
                    <th>Название</th>
                    <th>Период</th>
                    <th>Размер</th>
                    <th>Стоимость</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                @forelse($rentals as $rental)
                    <tr>
                        <td>Одежда</td>
                        <td>{{ $rental->product->name }}</td>
                        <td>
                            {{ $rental->start_date->format('d.m.Y') }} - {{ $rental->end_date->format('d.m.Y') }}
                        </td>
                        <td>{{ strtoupper($rental->size) }}</td>
                        <td>{{ number_format($rental->total_price, 0, ',', ' ') }} ₽</td>
{{--                        <td>--}}
{{--                            <form method="POST" action="{{ route('rental.cancel', $rental->id) }}" class="cancel-rental-form">--}}
{{--                                @csrf--}}
{{--                                @method('DELETE')--}}
{{--                                <button type="submit" class="btn-cancel">ОТМЕНИТЬ</button>--}}
{{--                            </form>--}}
{{--                        </td>--}}
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="no-rentals">У вас пока нет арендованных товаров</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <style>
        .rentals-wrapper {
            max-width: 800px;
            margin: 0 auto;
        }

        .rentals-table table {
            width: 100%;
            border-collapse: collapse;
            background-color: #fff;
        }

        .rentals-table th, .rentals-table td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        .rentals-table th {
            background-color: #f8f9fa;
            font-weight: bold;
            color: #333;
        }

        .rentals-table td {
            color: #666;
        }

        .no-rentals {
            text-align: center;
            padding: 20px;
            color: #666;
        }

        .btn-cancel {
            padding: 5px 10px;
            background-color: #dc3545;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .btn-cancel:hover {
            background-color: #c82333;
        }
    </style>
@endsection
