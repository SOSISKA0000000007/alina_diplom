@foreach($products as $product)
    <a href="{{ route('rent.show', $product) }}" class="product-card">
        <div class="product-image">
            @if($product->images && count($product->images) > 0)
                <img src="{{ Storage::url($product->images[0]) }}" alt="{{ $product->name }}">
            @else
                <div class="no-image">Нет изображения</div>
            @endif
        </div>
        <div class="product-info">
            <h2>{{ $product->name }}</h2>
            <p class="type">{{ $product->type }}</p>
            <p class="price">{{ $product->price }} ₽</p>
        </div>
    </a>
@endforeach
