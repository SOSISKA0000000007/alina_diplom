@extends('layouts.hf')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <h1 class="text-2xl font-bold mb-6">Редактировать товар</h1>

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.rental-products.update', $rentalProduct) }}" method="POST" class="max-w-2xl" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="mb-4">
                <label for="name" class="block text-gray-700 text-sm font-bold mb-2">Название товара</label>
                <input type="text" name="name" id="name" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" value="{{ old('name', $rentalProduct->name) }}" required>
                @error('name')
                <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-4">
                <label for="description" class="block text-gray-700 text-sm font-bold mb-2">Описание товара</label>
                <textarea name="description" id="description" rows="4" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>{{ old('description', $rentalProduct->description) }}</textarea>
                @error('description')
                <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-4">
                <label for="price" class="block text-gray-700 text-sm font-bold mb-2">Цена за аренду</label>
                <input type="number" step="0.01" name="price" id="price" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" value="{{ old('price', $rentalProduct->price) }}" required>
                @error('price')
                <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">Текущие фотографии</label>
                @if ($rentalProduct->images && count($rentalProduct->images) > 0)
                    <div class="flex gap-4 mb-4">
                        @foreach ($rentalProduct->images as $image)
                            <img src="{{ Storage::url($image) }}" alt="Текущая фотография" class="w-24 h-24 object-cover">
                        @endforeach
                    </div>
                @else
                    <p>Фотографии отсутствуют</p>
                @endif
            </div>

            <div class="mb-4">
                <label for="images" class="block text-gray-700 text-sm font-bold mb-2">Добавить новые фотографии (заменят текущие)</label>
                <input type="file" name="images[]" id="images" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" accept="image/*" multiple>
                @error('images')
                <div class="text-danger">{{ $message }}</div>
                @enderror
                @error('images.*')
                <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">Размеры и количество</label>
                <div id="sizes-container">
                    @foreach ($rentalProduct->sizes_quantity as $size => $quantity)
                        <div class="flex gap-4 mb-2">
                            <select name="sizes[]" class="shadow appearance-none border rounded py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                <option value="xs" {{ $size == 'xs' ? 'selected' : '' }}>XS</option>
                                <option value="s" {{ $size == 's' ? 'selected' : '' }}>S</option>
                                <option value="m" {{ $size == 'm' ? 'selected' : '' }}>M</option>
                                <option value="l" {{ $size == 'l' ? 'selected' : '' }}>L</option>
                                <option value="xl" {{ $size == 'xl' ? 'selected' : '' }}>XL</option>
                                <option value="xxl" {{ $size == 'xxl' ? 'selected' : '' }}>XXL</option>
                            </select>
                            <input type="number" name="quantities[]" min="1" class="shadow appearance-none border rounded py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" placeholder="Количество" value="{{ $quantity }}">
                            <button type="button" class="remove-size bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">Удалить</button>
                        </div>
                    @endforeach
                </div>
                <button type="button" id="add-size" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mt-2">Добавить размер</button>
            </div>

            <div class="flex items-center justify-between">
                <button type="submit" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                    Обновить товар
                </button>
            </div>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sizesContainer = document.getElementById('sizes-container');
            const addSizeButton = document.getElementById('add-size');

            addSizeButton.addEventListener('click', function() {
                const sizeDiv = document.createElement('div');
                sizeDiv.className = 'flex gap-4 mb-2';
                sizeDiv.innerHTML = `
            <select name="sizes[]" class="shadow appearance-none border rounded py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                <option value="xs">XS</option>
                <option value="s">S</option>
                <option value="m">M</option>
                <option value="l">L</option>
                <option value="xl">XL</option>
                <option value="xxl">XXL</option>
            </select>
            <input type="number" name="quantities[]" min="1" class="shadow appearance-none border rounded py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" placeholder="Количество">
            <button type="button" class="remove-size bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">Удалить</button>
        `;
                sizesContainer.appendChild(sizeDiv);
            });

            document.addEventListener('click', function(e) {
                if (e.target.classList.contains('remove-size')) {
                    e.target.parentElement.remove();
                }
            });
        });
    </script>
@endsection
