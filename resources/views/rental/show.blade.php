@extends('layouts.hf')

@section('content')
    <div class="rental-container">
        <div class="product-detail">
            <div>
                <div class="product-image">
                    @if($product->images && count($product->images) > 0)
                        <img src="{{ Storage::url($product->images[0]) }}" alt="{{ $product->name }}">
                    @else
                        <div class="no-image">Нет изображения</div>
                    @endif
                </div>

                <!-- Галерея дополнительных изображений -->
                @if($product->images && count($product->images) > 1)
                    <div class="image-gallery">
                        @foreach($product->images as $image)
                            <img src="{{ Storage::url($image) }}" alt="{{ $product->name }}" class="gallery-image">
                        @endforeach
                    </div>
                @endif
            </div>
            <div class="product-info">
                <h1>{{ $product->name }}</h1>
                <p class="price">{{ $product->price }} ₽</p>
                <p>В наличии: {{ $product->sizes_quantity ? array_sum($product->sizes_quantity) : 0 }}</p>
                <p>Размер: {{ strtoupper(implode(', ', array_keys($product->sizes_quantity))) }}</p>

                <form action="{{ route('rent.store', $product) }}" method="POST" class="rent-form" id="rent-form">
                    @csrf
                    <div class="form-group">
                        <div class="size-buttons">
                            @foreach($product->sizes_quantity as $size => $quantity)
                                <button type="button" class="size-button {{ $quantity > 0 ? 'available' : '' }}" data-size="{{ $size }}" data-quantity="{{ $quantity }}" {{ $quantity == 0 ? 'disabled' : '' }}>
                                    {{ strtoupper($size) }}
                                </button>
                            @endforeach
                            <input type="hidden" name="size" id="size" required>
                        </div>
                        @error('size')
                        <span class="error">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group quantity-selector">
                        <div class="quantity-controls">
                            <button type="button" class="quantity-btn minus">-</button>
                            <input type="number" name="quantity" id="quantity" value="1" min="1" readonly>
                            <button type="button" class="quantity-btn plus">+</button>
                        </div>
                        <span class="quantity-available"></span>
                        @error('quantity')
                        <span class="error">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group date-button">
                        <span class="date-label">Период аренды:</span>
                        <a href="#" class="date-select-link">выбрать дату</a>
                    </div>

                    <div class="form-group date-selector" style="display: none;">
                        <label for="start_date">Дата начала аренды:</label>
                        <input type="date" name="start_date" id="start_date" required min="{{ date('Y-m-d') }}">
                        @error('start_date')
                        <span class="error">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group date-selector" style="display: none;">
                        <label for="end_date">Дата окончания аренды:</label>
                        <input type="date" name="end_date" id="end_date" required>
                        @error('end_date')
                        <span class="error">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="availability-message" style="display: none;"></div>

                    <button type="button" class="btn-clear" style="display: none;">Очистить выбор</button>
                    <button type="submit" class="btn-rent" disabled>Арендовать</button>
                </form>

                <!-- Tabs -->
                <div class="tabs">
                    <button class="tab-button active" data-tab="description">О товаре</button>
                    <button class="tab-button" data-tab="reviews">Отзывы</button>
                </div>

                <!-- Tab Content -->
                <div id="description" class="tab-content">
                    <p>{{ $product->description }}</p>
                </div>
                <div id="reviews" class="tab-content" style="display: none;">
                    @if($canLeaveReview)
                        <button id="show-review-form" class="btn-rent">Оставить отзыв</button>
                        <form id="review-form" class="review-form" style="display: none;">
                            @csrf
                            <div class="form-group">
                                <label for="rental_id">Аренда:</label>
                                <select name="rental_id" id="rental_id" required>
                                    @foreach(\App\Models\Rental::where('user_id', Auth::id())->where('rental_product_id', $product->id)->where('status', 'confirmed')->where('end_date', '<', now())->whereDoesntHave('review')->get() as $rental)
                                        <option value="{{ $rental->id }}">Аренда с {{ $rental->start_date->format('d.m.Y') }} по {{ $rental->end_date->format('d.m.Y') }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="pros">Плюсы:</label>
                                <textarea name="pros" id="pros" required maxlength="500"></textarea>
                            </div>
                            <div class="form-group">
                                <label for="cons">Минусы:</label>
                                <textarea name="cons" id="cons" required maxlength="500"></textarea>
                            </div>
                            <div class="form-group">
                                <label for="comment">Комментарий:</label>
                                <textarea name="comment" id="comment" required maxlength="1000"></textarea>
                            </div>
                            <button type="submit" class="btn-rent">Отправить отзыв</button>
                            <div class="review-message" style="display: none;"></div>
                        </form>
                    @endif
                    <div id="reviews-list"></div>
                    <div id="reviews-loading" style="display: none;">Загрузка...</div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .rental-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        .product-detail {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 40px;
            margin-top: 30px;
        }

        .product-image {
            width: 100%;
            height: 620px;
            background: #f8f9fa;
            overflow: hidden;
        }

        .product-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .no-image {
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #666;
            font-size: 1.5em;
        }

        .image-gallery {
            display: flex;
            gap: 10px;
            margin-top: 10px;
            flex-wrap: wrap;
            height: 300px;
        }

        .gallery-image {
            height: 100%;
            object-fit: cover;
            border-radius: 4px;
            cursor: pointer;
        }

        .gallery-image:hover {
            opacity: 0.8;
        }

        .product-info h1 {
            margin: 0 0 20px 0;
            color: var(--black);
            font-family: com-med;
            font-size: 24px;
        }

        .product-info p {
            margin: 0 0 20px 0;
            color: var(--black);
            font-family: com-reg;
            font-size: 16px;
        }

        .price {
            font-family: com-bold !important;
            font-size: 36px !important;
            color: var(--blue) !important;
            margin: 0 0 20px 0 !important;
        }

        .rent-form {
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
            color: #333;
        }

        .form-group select,
        .form-group input[type="date"],
        .form-group textarea {
            width: 100%;
            padding: 8px 0;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 1em;
            height: 47px;
        }

        .form-group textarea {
            height: 100px;
            resize: vertical;
        }

        .size-buttons {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }

        .size-button {
            padding: 8px 16px;
            border: 1px solid #ddd;
            border-radius: 4px;
            background: #fff;
            cursor: pointer;
            font-size: 1em;
            transition: all 0.3s;
            margin: 0;
        }

        .size-button.available {
            border-color: var(--blue);
        }

        .size-button.selected {
            background: var(--blue);
            color: white;
            border-color: var(--blue);
        }

        .size-button:disabled {
            background: #f8f9fa;
            color: #aaa;
            cursor: not-allowed;
            border-color: #ddd;
        }

        .quantity-selector {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .quantity-controls {
            display: flex;
            align-items: center;
            width: 100%;
            height: 50px;
            background: #fff;
        }

        .quantity-btn {
            width: 20%;
            height: 100%;
            border: 1px solid #ddd;
            background: #fff;
            cursor: pointer;
            font-size: 1.5em;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: background-color 0.3s;
            padding: 0;
        }

        .quantity-btn.minus {
            border-radius: 4px 0 0 4px;
        }

        .quantity-btn.plus {
            border-radius: 0 4px 4px 0;
        }

        .quantity-btn:hover {
            background-color: #f0f0f0;
        }

        .quantity-btn:disabled {
            background: #f8f9fa;
            color: #aaa;
            cursor: not-allowed;
        }

        #quantity {
            flex: 1;
            height: 100%;
            text-align: center;
            border: 1px solid #ddd;
            border-left: none;
            border-right: none;
            border-radius: 0;
            padding: 0;
            margin: 0;
            font-size: 1.2em;
            background: transparent;
            outline: none;
            line-height: normal;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .quantity-available {
            display: none;
        }

        .date-button {
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .date-label {
            font-size: 16px;
            font-family: com-med;
            margin: 0;
            color: rgba(54, 54, 54, 1);
        }

        .date-select-link {
            margin: 0;
            color: var(--black);
            text-decoration: none;
            font-family: com-reg;
            font-size: 16px;
            cursor: pointer;
            transition: color 0.3s;
        }

        .date-select-link:hover {
            color: var(--blue);
        }

        .availability-message {
            margin-bottom: 15px;
        }

        .availability-message.success {
            color: green;
            font-family: com-reg;
            font-size: 20px;
        }

        .availability-message.error {
            font-family: com-reg;
            font-size: 20px;
            color: red;
        }

        .error {
            color: #721c24;
            font-size: 0.9em;
        }

        .btn-clear {
            display: none;
            width: 100%;
            background: #6c757d;
            color: white;
            border: none;
            padding: 12px;
            border-radius: 4px;
            font-size: 1.1em;
            cursor: pointer;
            margin-bottom: 10px;
            transition: background-color 0.3s;
        }

        .btn-clear:hover {
            background: #5a6268;
        }

        .btn-rent {
            width: 100%;
            font-family: com-med;
            background-color: var(--blue);
            color: white;
            border: none;
            padding: 12px;
            border-radius: 4px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .btn-rent:hover:not(:disabled) {
            background-color: #0056b3;
        }

        .btn-rent:disabled {
            background: #6c757d;
            cursor: not-allowed;
        }

        /* Tabs Styles */
        .tabs {
            display: flex;
            gap: 10px;
            margin: 20px 0;
        }

        .tab-button {
            padding: 10px 20px;
            border: none;
            border-radius: 0;
            background: #fff;
            cursor: pointer;
            font-family: com-reg;
            font-size: 20px;
            transition: all 0.3s;
        }

        .tab-button.active {
            color: var(--blue);
            border-bottom: 1px solid var(--blue);
        }

        .tab-content {
            margin-top: 20px;
        }

        /* Review Styles */
        #show-review-form {
            margin-bottom: 20px;
        }

        .review-form {
            margin-bottom: 30px;
            transition: opacity 0.3s ease;
        }

        .review-form .form-group {
            margin-bottom: 20px;
        }

        .review-form label {
            font-family: com-med;
            font-size: 16px;
        }

        .review-form textarea,
        .review-form select {
            font-family: com-reg;
            font-size: 16px;
        }

        .review-message {
            margin-top: 10px;
            font-family: com-reg;
            font-size: 16px;
        }

        .review-message.success {
            color: green;
        }

        .review-message.error {
            color: red;
        }

        .review-item {
            border-bottom: 1px solid #ddd;
            padding: 20px 0;
        }

        .review-header {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
        }

        .review-user {
            font-family: com-med;
            font-size: 16px;
            color: var(--black);
        }

        .review-date {
            font-family: com-reg;
            font-size: 14px;
            color: #666;
        }

        .review-content p {
            margin: 10px 0;
            font-family: com-reg;
            font-size: 16px;
        }

        .review-content strong {
            font-family: com-med;
        }

        #reviews-loading {
            text-align: center;
            font-family: com-reg;
            font-size: 16px;
            color: #666;
            padding: 20px;
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('rent-form');
            const startDateInput = document.getElementById('start_date');
            const endDateInput = document.getElementById('end_date');
            const sizeInput = document.getElementById('size');
            const quantityInput = document.getElementById('quantity');
            const sizeButtons = document.querySelectorAll('.size-button');
            const quantitySelector = document.querySelector('.quantity-selector');
            const dateButton = document.querySelector('.date-button');
            const dateSelectors = document.querySelectorAll('.date-selector');
            const dateSelectLink = document.querySelector('.date-select-link');
            const minusBtn = document.querySelector('.quantity-btn.minus');
            const plusBtn = document.querySelector('.quantity-btn.plus');
            const availabilityMessage = document.querySelector('.availability-message');
            const submitBtn = document.querySelector('.btn-rent');
            const clearBtn = document.querySelector('.btn-clear');
            const quantityAvailable = document.querySelector('.quantity-available');
            const galleryImages = document.querySelectorAll('.gallery-image');
            const mainImage = document.querySelector('.product-image img');

            let selectedSize = null;
            let maxQuantity = 0;
            let isAvailable = false;
            let isChecking = false;

            function debounce(func, wait) {
                let timeout;
                return function executedFunction(...args) {
                    const later = () => {
                        clearTimeout(timeout);
                        func(...args);
                    };
                    clearTimeout(timeout);
                    timeout = setTimeout(later, wait);
                };
            }

            function checkAvailability() {
                if (isChecking) {
                    console.log('Check availability skipped: request in progress');
                    return;
                }

                if (!sizeInput.value || !quantityInput.value || !startDateInput.value || !endDateInput.value) {
                    submitBtn.disabled = true;
                    availabilityMessage.style.display = 'block';
                    availabilityMessage.className = 'availability-message error';
                    availabilityMessage.textContent = 'Пожалуйста, заполните все поля';
                    isAvailable = false;
                    console.log('Check availability skipped: missing input values', {
                        size: sizeInput.value,
                        quantity: quantityInput.value,
                        start_date: startDateInput.value,
                        end_date: endDateInput.value
                    });
                    return;
                }

                const quantity = parseInt(quantityInput.value);
                if (isNaN(quantity) || quantity < 1) {
                    console.error('Invalid quantity:', quantityInput.value);
                    availabilityMessage.style.display = 'block';
                    availabilityMessage.className = 'availability-message error';
                    availabilityMessage.textContent = 'Некорректное количество';
                    submitBtn.disabled = true;
                    isAvailable = false;
                    return;
                }

                isChecking = true;
                availabilityMessage.style.display = 'block';
                availabilityMessage.className = 'availability-message';
                availabilityMessage.textContent = 'Проверка доступности...';

                fetch('{{ route('rent.check-availability', $product) }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        size: sizeInput.value,
                        quantity: quantity,
                        start_date: startDateInput.value,
                        end_date: endDateInput.value
                    })
                })
                    .then(response => {
                        console.log('checkAvailability response status:', response.status);
                        if (!response.ok) {
                            return response.json().then(errorData => {
                                throw new Error(errorData.message || `HTTP error! status: ${response.status}`);
                            });
                        }
                        return response.json();
                    })
                    .then(data => {
                        console.log('checkAvailability response:', data);
                        availabilityMessage.style.display = 'block';
                        if (data.available) {
                            availabilityMessage.className = 'availability-message success';
                            availabilityMessage.textContent = `Товар доступен для аренды! Остаток: ${data.remaining}`;
                            submitBtn.disabled = false;
                            isAvailable = true;
                            quantityAvailable.textContent = `Доступно: ${data.remaining}`;
                            quantityInput.max = data.remaining;
                            if (parseInt(quantityInput.value) > data.remaining) {
                                quantityInput.value = data.remaining;
                                console.log('Adjusted quantity to:', quantityInput.value);
                            }
                        } else {
                            availabilityMessage.className = 'availability-message error';
                            availabilityMessage.textContent = data.errors ? Object.values(data.errors).join(', ') : `Товар недоступен в указанные даты. Остаток: ${data.remaining}`;
                            submitBtn.disabled = true;
                            isAvailable = false;
                            quantityAvailable.textContent = `Доступно: ${data.remaining}`;
                            quantityInput.max = data.remaining;
                            if (parseInt(quantityInput.value) > data.remaining) {
                                quantityInput.value = data.remaining;
                                console.log('Adjusted quantity to:', quantityInput.value);
                            }
                        }
                        updateQuantityButtons();
                        console.log('Button state:', { disabled: submitBtn.disabled, isAvailable, response: data });
                    })
                    .catch(error => {
                        console.error('checkAvailability error:', error);
                        availabilityMessage.style.display = 'block';
                        availabilityMessage.className = 'availability-message error';
                        availabilityMessage.textContent = `Ошибка: ${error.message}`;
                        submitBtn.disabled = true;
                        isAvailable = false;
                        quantityAvailable.textContent = '';
                        console.log('Button state:', { disabled: submitBtn.disabled, isAvailable });
                    })
                    .finally(() => {
                        isChecking = false;
                    });
            }

            const debouncedCheckAvailability = debounce(checkAvailability, 500);

            sizeButtons.forEach(button => {
                button.addEventListener('click', function() {
                    if (!this.disabled) {
                        sizeButtons.forEach(btn => btn.classList.remove('selected'));
                        this.classList.add('selected');
                        sizeInput.value = this.dataset.size;
                        selectedSize = this.dataset.size;
                        maxQuantity = parseInt(this.dataset.quantity);
                        quantityInput.value = 1;
                        quantityInput.max = maxQuantity;
                        clearBtn.style.display = 'block';
                        submitBtn.disabled = true;
                        isAvailable = false;
                        availabilityMessage.style.display = 'none';
                        quantityAvailable.textContent = `Доступно: ${maxQuantity}`;
                        updateQuantityButtons();
                        console.log('Size selected:', {
                            size: this.dataset.size,
                            maxQuantity: maxQuantity
                        });
                        if (startDateInput.value && endDateInput.value) {
                            debouncedCheckAvailability();
                        }
                    }
                });
            });

            dateSelectLink.addEventListener('click', function(event) {
                event.preventDefault();
                dateButton.style.display = 'none';
                dateSelectors.forEach(selector => selector.style.display = 'block');
                if (sizeInput.value && startDateInput.value && endDateInput.value) {
                    debouncedCheckAvailability();
                }
            });

            minusBtn.addEventListener('click', function() {
                let currentValue = parseInt(quantityInput.value);
                if (currentValue > 1) {
                    quantityInput.value = currentValue - 1;
                    updateQuantityButtons();
                    if (sizeInput.value && startDateInput.value && endDateInput.value) {
                        debouncedCheckAvailability();
                    }
                }
            });

            plusBtn.addEventListener('click', function() {
                let currentValue = parseInt(quantityInput.value);
                if (currentValue < maxQuantity) {
                    quantityInput.value = currentValue + 1;
                    updateQuantityButtons();
                    if (sizeInput.value && startDateInput.value && endDateInput.value) {
                        debouncedCheckAvailability();
                    }
                }
            });

            startDateInput.addEventListener('change', function() {
                endDateInput.min = this.value;
                if (endDateInput.value && endDateInput.value < this.value) {
                    endDateInput.value = this.value;
                }
                if (sizeInput.value) {
                    debouncedCheckAvailability();
                }
            });

            endDateInput.addEventListener('change', function() {
                if (sizeInput.value) {
                    debouncedCheckAvailability();
                }
            });

            clearBtn.addEventListener('click', function() {
                sizeButtons.forEach(btn => btn.classList.remove('selected'));
                sizeInput.value = '';
                quantityInput.value = 1;
                quantitySelector.style.display = 'flex';
                dateButton.style.display = 'flex';
                dateSelectors.forEach(selector => selector.style.display = 'none');
                availabilityMessage.style.display = 'none';
                submitBtn.disabled = true;
                isAvailable = false;
                clearBtn.style.display = 'none';
                quantityAvailable.textContent = '';
                startDateInput.value = '';
                endDateInput.value = '';
                maxQuantity = 0;
                updateQuantityButtons();
                console.log('Form cleared');
            });

            function updateQuantityButtons() {
                minusBtn.disabled = parseInt(quantityInput.value) <= 1;
                plusBtn.disabled = maxQuantity === 0 || parseInt(quantityInput.value) >= Math.min(maxQuantity, parseInt(quantityInput.max) || maxQuantity);
                console.log('Quantity buttons updated:', {
                    minusDisabled: minusBtn.disabled,
                    plusDisabled: plusBtn.disabled,
                    currentQuantity: quantityInput.value,
                    maxQuantity: maxQuantity
                });
            }

            form.addEventListener('submit', function(event) {
                if (!isAvailable) {
                    event.preventDefault();
                    availabilityMessage.style.display = 'block';
                    availabilityMessage.className = 'availability-message error';
                    availabilityMessage.textContent = 'Нельзя отправить форму: товар недоступен';
                    console.warn('Form submission blocked: item not available');
                } else {
                    console.log('Form submission allowed:', {
                        size: sizeInput.value,
                        quantity: quantityInput.value,
                        start_date: startDateInput.value,
                        end_date: endDateInput.value
                    });
                }
            });

            updateQuantityButtons();

            // Переключение изображений в галерее
            galleryImages.forEach(image => {
                image.addEventListener('click', function() {
                    if (mainImage) {
                        mainImage.src = this.src;
                    }
                });
            });

            // Tabs functionality
            document.querySelectorAll('.tab-button').forEach(button => {
                button.addEventListener('click', function() {
                    document.querySelectorAll('.tab-button').forEach(btn => btn.classList.remove('active'));
                    document.querySelectorAll('.tab-content').forEach(content => content.style.display = 'none');
                    this.classList.add('active');
                    document.getElementById(this.dataset.tab).style.display = 'block';

                    if (this.dataset.tab === 'reviews' && !reviewsLoaded) {
                        loadReviews(1);
                    }
                });
            });

            // Show review form
            const showReviewFormBtn = document.getElementById('show-review-form');
            const reviewForm = document.getElementById('review-form');
            if (showReviewFormBtn) {
                showReviewFormBtn.addEventListener('click', function() {
                    reviewForm.style.display = 'block';
                    showReviewFormBtn.style.display = 'none';
                });
            }

            // Reviews functionality
            let reviewsLoaded = false;
            let currentPage = 1;
            let hasMoreReviews = true;
            let isLoadingReviews = false;

            function loadReviews(page) {
                if (isLoadingReviews || !hasMoreReviews) return;

                isLoadingReviews = true;
                document.getElementById('reviews-loading').style.display = 'block';

                fetch(`{{ route('rent.reviews', $product) }}?page=${page}`, {
                    headers: {
                        'Accept': 'application/json'
                    }
                })
                    .then(response => response.json())
                    .then(data => {
                        document.getElementById('reviews-loading').style.display = 'none';
                        document.getElementById('reviews-list').insertAdjacentHTML('beforeend', data.html);
                        hasMoreReviews = data.hasMore;
                        currentPage = data.nextPage;
                        isLoadingReviews = false;
                        reviewsLoaded = true;
                    })
                    .catch(error => {
                        console.error('Error loading reviews:', error);
                        document.getElementById('reviews-loading').style.display = 'none';
                        document.getElementById('reviews-list').innerHTML += '<p class="error">Ошибка загрузки отзывов</p>';
                        isLoadingReviews = false;
                    });
            }

            // Infinite scroll for reviews
            const reviewsContainer = document.getElementById('reviews');
            reviewsContainer.addEventListener('scroll', function() {
                if (reviewsContainer.scrollTop + reviewsContainer.clientHeight >= reviewsContainer.scrollHeight - 20) {
                    loadReviews(currentPage);
                }
            });

            // Review form submission
            if (reviewForm) {
                reviewForm.addEventListener('submit', function(event) {
                    event.preventDefault();
                    const formData = new FormData(reviewForm);
                    const reviewMessage = document.querySelector('.review-message');

                    fetch('{{ route('rent.reviews.store', $product) }}', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Accept': 'application/json'
                        },
                        body: formData
                    })
                        .then(response => response.json())
                        .then(data => {
                            reviewMessage.style.display = 'block';
                            if (data.success) {
                                reviewMessage.className = 'review-message success';
                                reviewMessage.textContent = data.message;
                                reviewForm.reset();
                                reviewForm.style.display = 'none';
                                showReviewFormBtn.style.display = 'none';
                                document.getElementById('reviews-list').innerHTML = '';
                                currentPage = 1;
                                hasMoreReviews = true;
                                loadReviews(1);
                            } else {
                                reviewMessage.className = 'review-message error';
                                reviewMessage.textContent = data.error || 'Ошибка отправки отзыва';
                            }
                        })
                        .catch(error => {
                            console.error('Error submitting review:', error);
                            reviewMessage.style.display = 'block';
                            reviewMessage.className = 'review-message error';
                            reviewMessage.textContent = 'Ошибка отправки отзыва';
                        });
                });
            }
        });
    </script>
@endsection
