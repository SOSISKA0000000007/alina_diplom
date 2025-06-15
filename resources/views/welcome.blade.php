@extends('layouts.welcomehead')

@section('content')
    @php
        \Carbon\Carbon::setLocale('ru');
    @endphp

    <section class="container">
        <div class="fifteen-years">
            <h1>15 ЛЕТ ПОГРУЖАЕМ ВАС В МИР ЭКСТРИМА</h1>
            <div>
                <p>Откройте подводный мир Балтики – от уникальных рифов до загадочных затонувших кораблей. Незабываемые погружения с профессиональными инструкторами для дайверов всех уровней.</p>
                <p>Мы – команда профессионалов, страстно увлеченных подводным миром. Наш клуб предлагает курсы дайвинга и погружения на уникальные локации Балтийского моря, где вас ждут как захватывающие виды, так и безопасные, комфортные условия. <a class="nav-about-us" href="/about-us">Узнать больше</a></p>
            </div>
        </div>
        <img src="{{ asset('images/volny.svg') }}" alt="Логотип" class="volny">
    </section>

    <section class="tours" id="tours">
        <div class="container">
            <div class="tours-title">
                <h2>ТАИНСТВЕННЫЙ МИР ПОГРУЖЕНИЯ</h2>
            </div>
            <div class="tours-cards">
                <!-- Первый ряд: первые 2 тура -->
                <div class="tours-row tours-row--first">
                    @foreach($tours->take(2) as $index => $tour)
                        <div class="tours-card">
                            <div class="tours-card-image" style="background-image: url('{{ $tour->image ? asset('storage/' . $tour->image) : 'https://via.placeholder.com/300' }}')">
                                <div class="tours-card-overlay">
                                    <div class="tours-card-header">
                                        <div class="tours-card-date">
                                            @if($tour->dates->first())
                                                <p>{{ \Illuminate\Support\Str::upper(\Carbon\Carbon::parse($tour->dates->first()->start_date)->translatedFormat('d F Y')) }}-{{ \Illuminate\Support\Str::upper(\Carbon\Carbon::parse($tour->dates->first()->end_date)->translatedFormat('d F Y')) }}</p>
                                            @endif
                                        </div>
                                        <div class="tours-card-group">
                                            <p>В группе 2-6 человек</p>
                                        </div>
                                    </div>
                                    <h3 class="tours-card-title">{{ $tour->title }}</h3>
                                    <p class="tours-card-description">{{ $tour->description }}</p>
                                    <div class="tours-card-footer">
                                        @if($tour->prices->first())
                                            <div class="tours-card-prices">
                                                <p class="tours-card-price-new">{{ number_format($tour->prices->first()->sale_price, 0, ',', ' ') }}₽</p>
                                                <p class="tours-card-price-old">{{ number_format($tour->prices->first()->regular_price, 0, ',', ' ') }}₽</p>
                                            </div>
                                        @endif
                                        <a href="{{ route('tours.tour-' . ($index + 1)) }}" class="tours-card-button">ПОДРОБНЕЕ</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                <!-- Второй ряд: остальные туры (3, 4, 5) -->
                <div class="tours-row tours-row--second">
                    @foreach($tours->slice(2, 3) as $index => $tour)
                        <div class="tours-card">
                            <div class="tours-card-image" style="background-image: url('{{ $tour->image ? asset('storage/' . $tour->image) : 'https://via.placeholder.com/300' }}')">
                                <div class="tours-card-overlay">
                                    <div class="tours-card-header">
                                        <div class="tours-card-date">
                                            @if($tour->dates->first())
                                                <p>{{ \Illuminate\Support\Str::upper(\Carbon\Carbon::parse($tour->dates->first()->start_date)->translatedFormat('d F Y')) }}-{{ \Illuminate\Support\Str::upper(\Carbon\Carbon::parse($tour->dates->first()->end_date)->translatedFormat('d F Y')) }}</p>
                                            @endif
                                        </div>
                                        <div class="tours-card-group">
                                            <p>В группе 2-6 человек</p>
                                        </div>
                                    </div>
                                    <h3 class="tours-card-title">{{ $tour->title }}</h3>
                                    <p class="tours-card-description">{{ $tour->description }}</p>
                                    <div class="tours-card-footer">
                                        @if($tour->prices->first())
                                            <div class="tours-card-prices">
                                                <p class="tours-card-price-new">{{ number_format($tour->prices->first()->sale_price, 0, ',', ' ') }}₽</p>
                                                <p class="tours-card-price-old">{{ number_format($tour->prices->first()->regular_price, 0, ',', ' ') }}₽</p>
                                            </div>
                                        @endif
                                        <a href="{{ route('tours.tour-' . ($index + 1)) }}" class="tours-card-button">ПОДРОБНЕЕ</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    <section class="container">
        <div class="plusses">
            <h1>В ТУР ВКЛЮЧЕНО ВСЕ ЧТО НУЖНО</h1>
            <div class="plusses-contain">
                <div class="plusses-wrapper">
                    <img src="{{ asset('images/transfer.svg') }}" alt="Логотип">
                    <p>Трансфер</p>
                    <span>На протяжении всего отдыха личный транспорт</span>
                </div>
                <div class="plusses-wrapper">
                    <img src="{{ asset('images/food.svg') }}" alt="Логотип">
                    <p>Питание</p>
                    <span>Завтрак, обед и ужин</span>
                </div>
                <div class="plusses-wrapper">
                    <img src="{{ asset('images/instructors.svg') }}" alt="Логотип">
                    <p>Инструктор</p>
                    <span>Личный инструктор на протяжении всего отдыха</span>
                </div>
                <div class="plusses-wrapper">
                    <img src="{{ asset('images/entertainment.svg') }}" alt="Логотип">
                    <p>Развлечения</p>
                    <span>Теплые вечера у костра, прогулки у моря</span>
                </div>
                <div class="plusses-wrapper">
                    <img src="{{ asset('images/home.svg') }}" alt="Логотип">
                    <p>Проживание</p>
                    <span>Три гостевых домика у моря</span>
                </div>
            </div>
        </div>
    </section>

    <section class="container">
        <div class="instructors">
            <h1>НАШИ ИНСТРУКТОРЫ</h1>
            @if($instructors->count() > 0)
                @if($instructors->count() > 4)
                    <!-- Слайдер для более чем 4 инструкторов -->
                    <div class="swiper">
                        <div class="swiper-wrapper">
                            @foreach($instructors as $instructor)
                                <div class="swiper-slide">
                                    <div class="instructor-card" style="background-image: url('{{ $instructor->photo ? asset('storage/' . $instructor->photo) : 'https://via.placeholder.com/300' }}')">
                                        <div class="instructor-overlay">
                                            <h3>{{ $instructor->name }}</h3>
                                            <p>Опыт: {{ \Illuminate\Support\Str::limit($instructor->experience, 100) }}</p>
                                            <p>О себе: {{ \Illuminate\Support\Str::limit($instructor->about, 150) }}</p>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <!-- Навигация -->
                        <div class="swiper-button-prev"></div>
                        <div class="swiper-button-next"></div>
                    </div>
                @else
                    <!-- Статический ряд для 4 или менее инструкторов -->
                    <div class="row">
                        @foreach($instructors as $instructor)
                            <div class="col-md-3">
                                <div class="instructor-card" style="background-image: url('{{ $instructor->photo ? asset('storage/' . $instructor->photo) : 'https://via.placeholder.com/300' }}')">
                                    <div class="instructor-overlay">
                                        <h3>{{ $instructor->name }}</h3>
                                        <p>Опыт: {{ \Illuminate\Support\Str::limit($instructor->experience, 100) }}</p>
                                        <p>О себе: {{ \Illuminate\Support\Str::limit($instructor->about, 150) }}</p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            @else
                <p>Инструкторы пока не добавлены.</p>
            @endif
        </div>
    </section>

    <section class="container">
        <div class="tour-reviews">
            <h1>ОТЗЫВЫ О ТУРАХ</h1>
            <div class="reviews-controls">
                @auth
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#reviewModal">Оставить отзыв</button>
                @else
                    <a href="{{ route('login') }}" class="btn btn-primary">Войдите, чтобы оставить отзыв</a>
                @endauth
            </div>
            <div id="reviews-container"></div>
        </div>
    </section>

    <!-- Модальное окно для отзыва -->
    <div class="modal fade" id="reviewModal" tabindex="-1" aria-labelledby="reviewModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="reviewModalLabel">Оставить отзыв</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="reviewForm">
                        @csrf
                        <div class="mb-3">
                            <label for="tour_id" class="form-label">Выберите тур</label>
                            <select name="tour_id" id="tour_id" class="form-select" required>
                                <option value="">Выберите тур</option>
                                @auth
                                    @foreach(auth()->user()->bookings()->where('status', 'confirmed')->with('tour')->get() as $booking)
                                        @if($booking->tourDate->end_date < now())
                                            <option value="{{ $booking->tour->id }}" data-booking-id="{{ $booking->id }}">{{ $booking->tour->title }}</option>
                                        @endif
                                    @endforeach
                                @endauth
                            </select>
                            <div id="tour-error" class="text-danger" style="display: none;"></div>
                        </div>
                        <div class="mb-3">
                            <label for="comment" class="form-label">Ваш отзыв</label>
                            <textarea name="comment" id="comment" class="form-control" rows="5" required maxlength="1000"></textarea>
                            <div id="comment-error" class="text-danger" style="display: none;"></div>
                        </div>
                        <input type="hidden" name="booking_id" id="booking_id">
                        <button type="submit" class="btn btn-primary">Отправить</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="container">
        <section class="contact-form">
            <h2>Свяжитесь с нами</h2>
            <form id="contactForm" class="needs-validation" novalidate>
                @csrf
                <div class="mb-3">
                    <label for="name" class="form-label">Имя</label>
                    <input type="text" class="form-control" id="name" name="name" required minlength="2" maxlength="50">
                    <div class="invalid-feedback">Введите имя (от 2 до 50 символов).</div>
                </div>
                <div class="mb-3">
                    <label for="phone" class="form-label">Телефон</label>
                    <input type="tel" class="form-control" id="phone" name="phone" required pattern="\+?[0-9\s\-\(\)]{10,15}">
                    <div class="invalid-feedback">Введите корректный номер телефона.</div>
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" name="email" required>
                    <div class="invalid-feedback">Введите корректный email.</div>
                </div>
                <button type="submit" class="btn btn-primary">Отправить</button>
                <div id="contact-success" class="text-success mt-3" style="display: none;">Сообщение успешно отправлено!</div>
                <div id="contact-error" class="text-danger mt-3" style="display: none;"></div>
            </form>
        </section>
    </div>

    <!-- Подключение Swiper.js JS -->
    <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
    <!-- Подключение Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Инициализация Swiper.js для инструкторов
        @if($instructors->count() > 4)
        const instructorSwiper = new Swiper('.instructors .swiper', {
            slidesPerView: 4,
            spaceBetween: 20,
            navigation: {
                nextEl: '.instructors .swiper-button-next',
                prevEl: '.instructors .swiper-button-prev',
            },
            pagination: {
                el: '.instructors .swiper-pagination',
                clickable: true,
            },
            breakpoints: {
                320: {
                    slidesPerView: 1,
                    spaceBetween: 10,
                },
                768: {
                    slidesPerView: 2,
                    spaceBetween: 15,
                },
                992: {
                    slidesPerView: 3,
                    spaceBetween: 20,
                },
                1200: {
                    slidesPerView: 4,
                    spaceBetween: 20,
                },
            },
        });
        @endif

        // Загрузка всех отзывов при загрузке страницы
        let reviewSwiper = null;
        window.addEventListener('load', function () {
            fetchReviews();
        });

        function fetchReviews() {
            fetch('/tours/reviews', {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
                .then(response => response.json())
                .then(data => {
                    document.getElementById('reviews-container').innerHTML = data.html;
                    if (reviewSwiper) {
                        reviewSwiper.destroy(true, true);
                    }
                    if (document.querySelector('.tour-reviews .swiper')) {
                        reviewSwiper = new Swiper('.tour-reviews .swiper', {
                            slidesPerView: 3,
                            spaceBetween: 20,
                            navigation: {
                                nextEl: '.tour-reviews .swiper-button-next',
                                prevEl: '.tour-reviews .swiper-button-prev',
                            },
                            pagination: {
                                el: '.tour-reviews .swiper-pagination',
                                clickable: true,
                            },
                            breakpoints: {
                                320: {
                                    slidesPerView: 1,
                                    spaceBetween: 10,
                                },
                                768: {
                                    slidesPerView: 2,
                                    spaceBetween: 15,
                                },
                                992: {
                                    slidesPerView: 3,
                                    spaceBetween: 20,
                                },
                            },
                        });
                    }
                })
                .catch(error => {
                    console.error('Error fetching reviews:', error);
                    document.getElementById('reviews-container').innerHTML = '<p>Ошибка при загрузке отзывов</p>';
                });
        }

        // Обработка отправки отзыва
        document.getElementById('reviewForm').addEventListener('submit', function (e) {
            e.preventDefault();
            const form = this;
            const tourId = document.getElementById('tour_id').value;
            const bookingId = document.getElementById('tour_id').selectedOptions[0].dataset.bookingId;
            const comment = document.getElementById('comment').value;
            const token = document.querySelector('input[name="_token"]').value;

            document.getElementById('tour-error').style.display = 'none';
            document.getElementById('comment-error').style.display = 'none';

            fetch(`/tours/${tourId}/reviews`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': token
                },
                body: JSON.stringify({
                    tour_id: tourId,
                    booking_id: bookingId,
                    comment: comment
                })
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert(data.message);
                        document.getElementById('reviewForm').reset();
                        document.getElementById('reviewModal').querySelector('.btn-close').click();
                        fetchReviews();
                    } else {
                        if (data.error.includes('не можете оставить отзыв')) {
                            document.getElementById('tour-error').innerText = data.error;
                            document.getElementById('tour-error').style.display = 'block';
                        } else if (data.error.includes('уже оставили отзыв')) {
                            document.getElementById('comment-error').innerText = data.error;
                            document.getElementById('comment-error').style.display = 'block';
                        }
                    }
                })
                .catch(error => {
                    console.error('Error submitting review:', error);
                    document.getElementById('comment-error').innerText = 'Ошибка при отправке отзыва';
                    document.getElementById('comment-error').style.display = 'block';
                });
        });

        // Установка booking_id при выборе тура
        document.getElementById('tour_id').addEventListener('change', function () {
            document.getElementById('booking_id').value = this.selectedOptions[0].dataset.bookingId || '';
        });

        // Обработка отправки контактной формы
        document.getElementById('contactForm').addEventListener('submit', function (e) {
            e.preventDefault();
            const form = this;
            if (!form.checkValidity()) {
                form.classList.add('was-validated');
                return;
            }

            const name = document.getElementById('name').value;
            const phone = document.getElementById('phone').value;
            const email = document.getElementById('email').value;
            const token = document.querySelector('input[name="_token"]').value;

            document.getElementById('contact-success').style.display = 'none';
            document.getElementById('contact-error').style.display = 'none';

            fetch('/contact', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': token
                },
                body: JSON.stringify({ name, phone, email })
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        form.reset();
                        form.classList.remove('was-validated');
                        document.getElementById('contact-success').style.display = 'block';
                    } else {
                        document.getElementById('contact-error').innerText = data.error || 'Ошибка при отправке сообщения';
                        document.getElementById('contact-error').style.display = 'block';
                    }
                })
                .catch(error => {
                    console.error('Error submitting contact form:', error);
                    document.getElementById('contact-error').innerText = 'Ошибка при отправке сообщения';
                    document.getElementById('contact-error').style.display = 'block';
                });
        });
    </script>

    <style>
        .contact-form {
            max-width: 600px;
            margin: 40px auto;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 8px;
            background: #fff;
        }
        .contact-form h2 {
            text-align: center;
            margin-bottom: 20px;
        }
        .contact-form .form-control {
            border-radius: 4px;
        }
        .contact-form .btn-primary {
            width: 100%;
            padding: 10px;
            border-radius: 4px;
        }
        .contact-form .invalid-feedback {
            font-size: 0.875em;
        }
    </style>
@endsection
