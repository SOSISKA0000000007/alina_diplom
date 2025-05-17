@extends('layouts.hf')

@section('content')
    <div class="container">
        <div class="admin-layout">
            @include('components.admin-sidebar')

            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bookings-wrapper">
                <h2>Товары для аренды</h2>

                <div class="bookings-table">
                    <table>
                        <tbody>
                        @forelse($products as $product)
                            <tr>
                                <td>
                                    <span class="label">Название</span><br><br>
                                    {{ $product->name }}
                                </td>
                                <td>
                                    <span class="label">Цена</span><br><br>
                                    {{ number_format($product->price, 0, ',', ' ') }} ₽
                                </td>
                                <td>
                                    <span class="label">Размеры и количество</span><br><br>
                                    @foreach($product->sizes_quantity as $size => $quantity)
                                        {{ strtoupper($size) }}: {{ $quantity }}{{ $loop->last ? '' : ', ' }}
                                    @endforeach
                                </td>
                                <td>
                                    <div class="admin-form-delite">
                                        <a href="{{ route('admin.rental-products.edit', $product) }}" class="admin-form-button">Редактировать</a>
                                        <form action="{{ route('admin.rental-products.destroy', $product) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="admin-form-button" onclick="return confirm('Вы уверены, что хотите удалить этот товар?')">Удалить</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="no-bookings">Нет товаров для аренды</td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
