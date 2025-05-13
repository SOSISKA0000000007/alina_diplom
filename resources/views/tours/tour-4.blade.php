@extends('layouts.hf')

@section('content')
    <div class="container">
        <h2 class="program-h2">Программа погружения "Баржа"</h2>
        <!-- Program Section -->
        <section class="program">
            <div class="program-wrapper">
                <p>Тур включает уютное проживание в домиках у Балтийского моря, трёхразовое питание и трансфер по всему маршруту, включая встречу в аэропорту.</p>
                <p>Одним из главных впечатлений станет погружение к затонувшему в 1907 году кораблю «Баржа» — редкая возможность прикоснуться к истории в формате подводного приключения.</p>
                <p>Сопровождение сертифицированного гида 24/7 обеспечит безопасность и поддержку. Дополнят программу сап-прогулки, посиделки у костра и другие активности, делающие тур насыщенным и запоминающимся.</p>
            </div>
            <div class="video-container">
                <video width="740" controls>
                    <source src="{{ asset('videos/tour-1.mp4') }}" type="video/mp4">
                    Your browser does not support the video tag.
                </video>
            </div>
        </section>

        <!-- Accommodation Section with Slider -->
        <section class="accommodation">
            <h2>Место проживания</h2>
            <div class="place-of-residence">
                <div class="place-of-residence-wrapper">
                    <div class="residence-image"><img src="{{ asset('images/tours/res-1.png') }}" alt="Accommodation 1"></div>
                    <div class="residence-image"><img src="{{ asset('images/tours/res-2.png') }}" alt="Accommodation 2"></div>
                    <div class="residence-image"><img src="{{ asset('images/tours/res-3.png') }}" alt="Accommodation 3"></div>
                </div>
                <div class="place-of-residence-wrapper">
                    <div class="residence-image"><img src="{{ asset('images/tours/res-4.png') }}" alt="Accommodation 4"></div>
                    <div class="residence-image"><img src="{{ asset('images/tours/res-5.png') }}" alt="Accommodation 5"></div>
                    <div class="residence-image"><img src="{{ asset('images/tours/res-6.png') }}" alt="Accommodation 6"></div>
                </div>
            </div>
        </section>

        <!-- Diving Schedule Section with Sliders -->
        <section class="diving-schedule">
            <h2>Подводное расписание каждого дня</h2>
            <div class="schedule">
                <h3>День 1: Введение и базовая подготовка</h3>
                <ul>
                    <li>09:00-10:00: Приветствие и регистрация участников. Знакомство с инструктором и группой. Краткий обзор программы.</li>
                    <li>10:00-12:00: Теоретическое занятие. Основы дайвинга: оборудование, безопасность, подводная коммуникация.</li>
                    <li>12:00-13:00: Обед.</li>
                    <li>13:00-16:00: Практика на суше. Знакомство с оборудованием, отработка техники погружения, основные правила поведения под водой.</li>
                    <li>16:00-18:00: Вечерняя прогулка по побережью, знакомство с местной природой. Фотосессия.</li>
                    <li>18:00-19:00: Ужин.</li>
                    <li>19:00-21:00: Свободное время. Дополнительные активности: лекции о морских экосистемах Балтики или просмотр тематического фильма.</li>
                </ul>
                <div class="swiper diving-slider day-1-slider">
                    <div class="swiper-wrapper">
                        <div class="swiper-slide"><img src="{{ asset('images/barja/barja-1-1.png') }}" alt="Diving 1-1"></div>
                        <div class="swiper-slide"><img src="{{ asset('images/barja/barja-1-2.png') }}" alt="Diving 1-2"></div>
                        <div class="swiper-slide"><img src="{{ asset('images/barja/barja-1-3.png') }}" alt="Diving 1-3"></div>
                    </div>
                    <div class="swiper-button-next"></div>
                    <div class="swiper-button-prev"></div>
                </div>

                <h3>День 2: Первая тренировка на воде</h3>
                <ul>
                    <li>09:00-10:00: Утренняя разминка, подготовка к занятиям.</li>
                    <li>10:00-12:00: Практика на мелководье или в бассейне. Освоение базовых навыков погружения, плавание с оборудованием, сигналы под водой.</li>
                    <li>12:00-13:00: Обед.</li>
                    <li>13:00-16:00: Первое погружение в море на мелководье (до 5 метров). Адаптация под водой, обучение общению с аквалангом.</li>
                    <li>16:00-18:00: Прогулка по природным местам Балтики.</li>
                    <li>18:00-19:00: Ужин.</li>
                    <li>19:00-21:00: Свободное время. Отдых у костра на побережье.</li>
                </ul>
                <div class="swiper diving-slider day-2-slider">
                    <div class="swiper-wrapper">
                        <div class="swiper-slide"><img src="{{ asset('images/barja/barja-2-1.png') }}" alt="Diving 2-1"></div>
                        <div class="swiper-slide"><img src="{{ asset('images/barja/barja-2-2.png') }}" alt="Diving 2-2"></div>
                        <div class="swiper-slide"><img src="{{ asset('images/barja/barja-2-3.png') }}" alt="Diving 2-3"></div>
                    </div>
                    <div class="swiper-button-next"></div>
                    <div class="swiper-button-prev"></div>
                </div>

                <h3>День 3: Погружение на глубину</h3>
                <ul>
                    <li>09:00-10:00: Утренняя разминка, разбор предыдущего дня.</li>
                    <li>10:00-12:00: Теория: управление плавучестью, работа с компенсатором.</li>
                    <li>12:00-13:00: Обед.</li>
                    <li>13:00-16:00: Погружение на глубину до 10 метров. Практика контроля плавучести и ориентации под водой.</li>
                    <li>16:00-18:00: Сап-прогулка по заливу.</li>
                    <li>18:00-19:00: Ужин.</li>
                    <li>19:00-21:00: Свободное время. Вечерний мастер-класс по подводной фотографии.</li>
                </ul>
                <div class="swiper diving-slider day-3-slider">
                    <div class="swiper-wrapper">
                        <div class="swiper-slide"><img src="{{ asset('images/barja/barja-3-1.png') }}" alt="Diving 3-1"></div>
                        <div class="swiper-slide"><img src="{{ asset('images/barja/barja-3-2.png') }}" alt="Diving 3-2"></div>
                        <div class="swiper-slide"><img src="{{ asset('images/barja/barja-3-3.png') }}" alt="Diving 3-3"></div>
                    </div>
                    <div class="swiper-button-next"></div>
                    <div class="swiper-button-prev"></div>
                </div>

                <h3>День 4: Подготовка к «Барже»</h3>
                <ul>
                    <li>09:00-10:00: Утренняя разминка, обсуждение плана погружения.</li>
                    <li>10:00-12:00: Теория: особенности погружений к затонувшим объектам.</li>
                    <li>12:00-13:00: Обед.</li>
                    <li>13:00-16:00: Погружение на глубину до 12 метров для отработки навыков навигации.</li>
                    <li>16:00-18:00: Прогулка по историческим местам побережья.</li>
                    <li>18:00-19:00: Ужин.</li>
                    <li>19:00-21:00: Свободное время. Рассказы о затонувших кораблях Балтики.</li>
                </ul>
                <div class="swiper diving-slider day-4-slider">
                    <div class="swiper-wrapper">
                        <div class="swiper-slide"><img src="{{ asset('images/barja/barja-4-1.png') }}" alt="Diving 4-1"></div>
                        <div class="swiper-slide"><img src="{{ asset('images/barja/barja-4-2.png') }}" alt="Diving 4-2"></div>
                        <div class="swiper-slide"><img src="{{ asset('images/barja/barja-4-3.png') }}" alt="Diving 4-3"></div>
                    </div>
                    <div class="swiper-button-next"></div>
                    <div class="swiper-button-prev"></div>
                </div>

                <h3>День 5: Погружение к «Барже»</h3>
                <ul>
                    <li>09:00-10:00: Утренняя разминка, финальный брифинг.</li>
                    <li>10:00-12:00: Подготовка оборудования, проверка снаряжения.</li>
                    <li>12:00-13:00: Обед.</li>
                    <li>13:00-16:00: Погружение к затонувшему кораблю «Баржа» (глубина до 15 метров).</li>
                    <li>16:00-18:00: Прощальная прогулка по побережью, фотосессия.</li>
                    <li>18:00-19:00: Ужин.</li>
                    <li>19:00-21:00: Церемония закрытия, вручение сертификатов, вечер у костра.</li>
                </ul>
                <div class="swiper diving-slider day-5-slider">
                    <div class="swiper-wrapper">
                        <div class="swiper-slide"><img src="{{ asset('images/barja/barja-5-1.png') }}" alt="Diving 5-1"></div>
                        <div class="swiper-slide"><img src="{{ asset('images/barja/barja-5-2.png') }}" alt="Diving 5-2"></div>
                        <div class="swiper-slide"><img src="{{ asset('images/barja/barja-5-3.png') }}" alt="Diving 5-3"></div>
                    </div>
                    <div class="swiper-button-next"></div>
                    <div class="swiper-button-prev"></div>
                </div>
            </div>
        </section>
        <section class="price-tour">
            <div class="price-tour-price">
                <h2>Стоимость тура <span class="original-price">54.900</span> <span class="new-price">49.900</span> рублей</h2>
            </div>
        </section>

    </div>

    <!-- Swiper JS -->
    <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
    <script>
        // Initialize Swiper for accommodation (unchanged)
        var accommodationSwiper = new Swiper('.accommodation-slider', {
            navigation: {
                nextEl: '.swiper-button-next',
                prevEl: '.swiper-button-prev',
            },
            loop: true,
        });

        // Initialize Swiper for each day's diving slider
        document.querySelectorAll('.diving-slider').forEach((slider, index) => {
            new Swiper(slider, {
                slidesPerView: 2,
                spaceBetween: 0, // No space between slides
                navigation: {
                    nextEl: `.day-${index + 1}-slider .swiper-button-next`,
                    prevEl: `.day-${index + 1}-slider .swiper-button-prev`,
                },
                loop: true,
                breakpoints: {
                    320: {
                        slidesPerView: 1,
                        spaceBetween: 0,
                    },
                    768: {
                        slidesPerView: 2,
                        spaceBetween: 0,
                    },
                },
            });
        });
    </script>

    <!-- Inline CSS for Slider Styling -->
    <style>
        .diving-slider {
            position: relative;
            width: 100%;
            max-width: 800px; /* Adjust based on your design */
            margin: 20px auto;
            overflow: hidden;
        }

        .diving-slider .swiper-wrapper {
            display: flex;
            align-items: center;
        }

        .diving-slider .swiper-slide {
            width: 50%; /* Each slide takes half the container width */
            height: 300px; /* Fixed height for consistency */
            overflow: hidden;
        }

        .diving-slider .swiper-slide img {
            width: 100%;
            height: 100%;
            object-fit: cover; /* Ensures images fill the slide without distortion */
            display: block;
        }

        /* Navigation Arrows */
        .diving-slider .swiper-button-next,
        .diving-slider .swiper-button-prev {
            color: #007bff; /* Blue color to match the image */
            background: none;
            width: 30px;
            height: 30px;
            margin-top: 0;
            top: 50%;
            transform: translateY(-50%);
        }


        .diving-slider .swiper-button-next::after,
        .diving-slider .swiper-button-prev::after {
            font-size: 30px;
            font-weight: bold;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .diving-slider .swiper-slide {
                width: 100%; /* Full width on mobile */
            }

            .diving-slider .swiper-button-next {
                right: 10px; /* Adjust for smaller screens */
            }

            .diving-slider .swiper-button-prev {
                left: 10px; /* Adjust for smaller screens */
            }
        }
    </style>
@endsection
