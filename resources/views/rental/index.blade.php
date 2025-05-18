@extends('layouts.hf')

@section('content')
    <div class="rental-container">
        <h1>Аренда снаряжения</h1>

        <div class="type-filter">
            <button class="type-button {{ !$selectedType ? 'active' : '' }}" data-type="">Все</button>
            @foreach($types as $type)
                <button class="type-button {{ $selectedType == $type ? 'active' : '' }}" data-type="{{ $type }}">{{ $type }}</button>
            @endforeach
        </div>

        <div class="sort-filter" style="margin: 20px 0;">
            <label for="sort-select" style="margin: 0">Сортировать:</label>
            <select id="sort-select" class="sort-select">
                <option value="" selected>По умолчанию</option>
                <option value="name_asc">По алфавиту (А-Я)</option>
                <option value="name_desc">По алфавиту (Я-А)</option>
                <option value="price_asc">Дешевле</option>
                <option value="price_desc">Дороже</option>
            </select>
        </div>

        <div class="products-grid" id="products-grid">
            @include('rental.partials.products')
        </div>

        <div id="error-message" style="display: none; color: red; margin-top: 20px;">
            Ошибка при загрузке товаров. Пожалуйста, попробуйте позже или обратитесь в поддержку.
        </div>

        <button id="retry-button" style="display: none; margin-top: 10px;" onclick="retryLastFilter()">Повторить</button>

        <div id="loading" style="display: none; text-align: center; margin-top: 20px;">Загрузка...</div>
    </div>

    <style>
        .rental-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        .rental-container h1 {
            font: 40px yes-bold;
            color: var(--blue);
        }

        .type-filter {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin: 20px 0;
        }

        .type-button {
            padding: 8px 16px;
            border: 1px solid #ddd;
            border-radius: 4px;
            background: #fff;
            color: #333;
            font-family: com-reg;
            font-size: 16px;
            cursor: pointer;
            transition: all 0.3s;
        }

        .type-button:hover {
            background: #f0f0f0;
        }

        .type-button.active {
            background: var(--blue);
            color: white;
            border-color: var(--blue);
        }

        .sort-filter {
            display: flex;
            align-items: center;
            gap: 10px;
            margin: 0;
            justify-content: space-between;
        }

        .sort-select {
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-family: com-reg;
            font-size: 16px;
            cursor: pointer;
            margin: 0;
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

        .product-info .type {
            font-family: com-reg;
            font-size: 14px;
            color: #666;
            text-align: center;
            margin: 5px 0;
        }

        .price {
            font-family: com-bold;
            font-size: 20px;
            text-align: center;
            color: var(--black);
            margin: 0;
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const typeButtons = document.querySelectorAll('.type-button');
            const sortSelect = document.getElementById('sort-select');
            const productsGrid = document.getElementById('products-grid');
            const errorMessage = document.getElementById('error-message');
            const retryButton = document.getElementById('retry-button');
            const loading = document.getElementById('loading');

            let lastType = '';
            let lastSort = '';

            function fetchProducts(type, sort) {
                // Скрываем сообщение об ошибке и кнопку повторной попытки, показываем загрузку
                errorMessage.style.display = 'none';
                retryButton.style.display = 'none';
                loading.style.display = 'block';
                productsGrid.innerHTML = '';

                // Формируем URL с параметрами
                let url = '/rent/filter-products';
                const params = new URLSearchParams();
                if (type) params.append('type', type);
                if (sort) params.append('sort', sort);
                if (params.toString()) url += `?${params.toString()}`;

                console.log('Fetching products with URL:', url);

                // Отправляем AJAX-запрос
                fetch(url, {
                    method: 'GET',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                })
                    .then(response => {
                        console.log('Response status:', response.status);
                        if (!response.ok) {
                            return response.json().then(errorData => {
                                throw new Error(`HTTP ${response.status}: ${errorData.message || 'Unknown error'}`);
                            });
                        }
                        return response.json();
                    })
                    .then(data => {
                        productsGrid.innerHTML = data.html;
                        loading.style.display = 'none';

                        // Обновляем URL без перезагрузки страницы
                        const newUrl = `/rent${params.toString() ? `?${params.toString()}` : ''}`;
                        history.pushState({}, '', newUrl);
                    })
                    .catch(error => {
                        console.error('Error fetching products:', error);
                        errorMessage.innerHTML = `Ошибка при загрузке товаров: ${error.message}. Попробуйте позже или обратитесь в поддержку.`;
                        errorMessage.style.display = 'block';
                        retryButton.style.display = 'block';
                        loading.style.display = 'none';
                        productsGrid.innerHTML = '';
                    });
            }

            // Обработчик для кнопок типа
            typeButtons.forEach(button => {
                button.addEventListener('click', function () {
                    lastType = this.getAttribute('data-type');

                    // Удаляем класс active у всех кнопок и добавляем к текущей
                    typeButtons.forEach(btn => btn.classList.remove('active'));
                    this.classList.add('active');

                    fetchProducts(lastType, lastSort);
                });
            });

            // Обработчик для выбора сортировки
            sortSelect.addEventListener('change', function () {
                lastSort = this.value;
                fetchProducts(lastType, lastSort);
            });

            // Обработчик для кнопки повторной попытки
            window.retryLastFilter = function () {
                fetchProducts(lastType, lastSort);
            };
        });
    </script>
@endsection
