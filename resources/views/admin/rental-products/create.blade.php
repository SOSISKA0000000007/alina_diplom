@extends('layouts.header')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold mb-6">Добавить новый товар для аренды</h1>

    <form action="{{ route('admin.rental-products.store') }}" method="POST" class="max-w-2xl">
        @csrf

        <div class="mb-4">
            <label for="name" class="block text-gray-700 text-sm font-bold mb-2">Название товара</label>
            <input type="text" name="name" id="name" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
        </div>

        <div class="mb-4">
            <label for="description" class="block text-gray-700 text-sm font-bold mb-2">Описание товара</label>
            <textarea name="description" id="description" rows="4" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required></textarea>
        </div>

        <div class="mb-4">
            <label for="price" class="block text-gray-700 text-sm font-bold mb-2">Цена за аренду</label>
            <input type="number" step="0.01" name="price" id="price" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
        </div>

        <div class="mb-4">
            <label for="image" class="form-label">Фотография</label>
            <input type="file" name="image" id="image" class="form-control" accept="image/*">
            @error('image')
            <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2">Размеры и количество</label>
            <div id="sizes-container">
                <div class="flex gap-4 mb-2">
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
                </div>
            </div>
            <button type="button" id="add-size" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mt-2">Добавить размер</button>
        </div>

        <div class="flex items-center justify-between">
            <button type="submit" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                Создать товар
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
