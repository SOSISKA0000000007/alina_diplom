@extends('layouts.hf')

@section('content')
<div class="rental-container">
    <h1>Аренда снаряжения</h1>

    <div class="products-grid">
        @foreach($products as $product)
            <a href="{{ route('rent.show', $product) }}" class="product-card">
                <div class="product-image">
                    @if($product->image)
                        <img src="{{ $product->image }}" alt="{{ $product->name }}">
                    @else
                        <div class="no-image">Нет изображения</div>
                    @endif
                </div>
                <div class="product-info">
                    <h2>{{ $product->name }}</h2>
                    <p class="price">{{ $product->price }} ₽</p>
                </div>
            </a>
        @endforeach
    </div>
</div>

<style>
.rental-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 20px;
}

.products-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(215px, 1fr));
    gap: 20px;
    margin-top: 30px;
}

.product-card {
    width: 100%;
    background: white;
    overflow: hidden;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    text-decoration: none;
    color: inherit;
    transition: transform 0.3s;
}

.product-card:hover {
    transform: translateY(-5px);
}

.product-image {
    height: 200px;
    background: #f8f9fa;
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: hidden;
}

.product-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.no-image {
    color: #666;
    font-size: 1.2em;
}

.product-info {
    padding: 15px;
}

.product-info h2 {
    font-family: com-med;
    font-size: 16px;
    text-align: center;
}

.price {
    font-family: com-bold;
    font-size: 20px;
    text-align: center;
    color: var(--black);
    margin: 0;
}
</style>
@endsection
