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

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('admin.rental-products.store') }}" method="POST" class="admin-form container" enctype="multipart/form-data">
                @csrf

                <div class="admin-form-container">
                    <label for="name" class="admin-label">Название</label>
                    <input type="text" name="name" id="name" class="admin-input" value="{{ old('name') }}" required>
                    @error('name')
                    <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="admin-form-container">
                    <label for="price" class="admin-label">Цена</label>
                    <input type="number" step="0.01" name="price" id="price" class="admin-input" value="{{ old('price') }}" required>
                    @error('price')
                    <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="admin-form-container">
                    <label for="images" class="admin-label">Фотографии</label>
                    <input type="file" name="images[]" id="images" class="admin-input-photo" accept="image/*" multiple>
                    @error('images')
                    <div class="text-danger">{{ $message }}</div>
                    @enderror
                    @error('images.*')
                    <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="admin-form-container">
                    <label for="description" class="admin-label">Описание</label>
                    <textarea name="description" id="description" rows="4" class="admin-input" required>{{ old('description') }}</textarea>
                    @error('description')
                    <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="admin-form-container">
                    <label class="admin-label">Размеры и количество</label>
                    <div id="sizes-container">
                        <div class="admin-size">
                            <select name="sizes[]" class="admin-select">
                                <option value="xs">XS</option>
                                <option value="s">S</option>
                                <option value="m">M</option>
                                <option value="l">L</option>
                                <option value="xl">XL</option>
                                <option value="xxl">XXL</option>
                            </select>
                            <input type="number" name="quantities[]" min="1" class="admin-input" placeholder="Количество" value="{{ old('quantities.0') }}">
                            <button type="button" class="admin-form-button remove-size">Удалить</button>
                            <button type="button" id="add-size" class="admin-form-button">Добавить</button>
                        </div>
                    </div>
                </div>

                <div class="admin-button-container">
                    <button type="submit" class="admin-form-button-create">
                        Создать товар
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sizesContainer = document.getElementById('sizes-container');
            const addSizeButton = document.getElementById('add-size');

            addSizeButton.addEventListener('click', function() {
                const sizeDiv = document.createElement('div');
                sizeDiv.className = 'admin-size';
                sizeDiv.innerHTML = `
                    <select name="sizes[]" class="admin-select">
                        <option value="xs">XS</option>
                        <option value="s">S</option>
                        <option value="m">M</option>
                        <option value="l">L</option>
                        <option value="xl">XL</option>
                        <option value="xxl">XXL</option>
                    </select>
                    <input type="number" name="quantities[]" min="1" class="admin-input" placeholder="Количество">
                    <button type="button" class="admin-form-button remove-size">Удалить</button>
                `;
                sizesContainer.appendChild(sizeDiv);
            });

            sizesContainer.addEventListener('click', function(e) {
                if (e.target.classList.contains('remove-size')) {
                    e.target.parentElement.remove();
                }
            });
        });
    </script>
@endsection
