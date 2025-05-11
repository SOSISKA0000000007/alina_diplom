@extends('layouts.header')

@section('content')
<div class="container">
    <h1>Управление арендой</h1>

    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Пользователь</th>
                    <th>Товар</th>
                    <th>Размер</th>
                    <th>Дата начала</th>
                    <th>Дата окончания</th>
                    <th>Стоимость</th>
                    <th>Статус</th>
                    <th>Действия</th>
                </tr>
            </thead>
            <tbody>
                @foreach($rentals as $rental)
                <tr>
                    <td>{{ $rental->id }}</td>
                    <td>{{ $rental->user->name }}</td>
                    <td>{{ $rental->product->name }}</td>
                    <td>{{ $rental->size }}</td>
                    <td>{{ $rental->start_date->format('d.m.Y') }}</td>
                    <td>{{ $rental->end_date->format('d.m.Y') }}</td>
                    <td>{{ $rental->total_price }} ₽</td>
                    <td>
                        @switch($rental->status)
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
                        @if($rental->status === 'pending')
                            <form action="{{ route('admin.rentals.confirm', $rental) }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-success btn-sm">Подтвердить</button>
                            </form>
                            <form action="{{ route('admin.rentals.reject', $rental) }}" method="POST" class="d-inline">
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
