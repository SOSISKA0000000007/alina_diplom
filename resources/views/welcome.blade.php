@extends('layouts.app')

@section('content')
<section class="container">
    <div class="fifteen-years">
        <h1>
            15 ЛЕТ ПОГРУЖАЕМ ВАС В МИР ЭКСТРИМА
        </h1>
        <div>
            <p>Откройте подводный мир Балтики – от уникальных рифов до загадочных затонувших кораблей. Незабываемые погружения с профессиональными инструкторами для дайверов всех уровней.</p>
            <p>Мы – команда профессионалов, страстно увлеченных подводным миром. Наш клуб предлагает курсы дайвинга и погружения на уникальные локации Балтийского моря, где вас ждут как захватывающие виды, так и безопасные, комфортные условия. </p>
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
                <!-- Первый ряд: только 2 тура -->
                <div class="tours-row tours-row--first">
                    @foreach($tours->take(2) as $tour)
                        <div class="tours-card">
                            <div class="tours-card-image" style="background-image: url('{{ asset('storage/' . $tour->image) }}')">
                                <div class="tours-card-overlay">
                                    <div class="tours-card-header">
                                    <div class="tours-card-date">
                                        @if($tour->dates->first())
                                            <p>{{ \Carbon\Carbon::parse($tour->dates->first()->start_date)->format('d F Y') }}-{{ \Carbon\Carbon::parse($tour->dates->first()->end_date)->format('d F Y') }}</p>
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
                                    <button class="tours-card-button">
                                        ПОДРОБНЕЕ
                                    </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                <!-- Второй ряд: остальные туры -->
                <div class="tours-row tours-row--second">
                    @foreach($tours->skip(2) as $tour)
                        <div class="tours-card">
                            <div class="tours-card-image" style="background-image: url('{{ asset('storage/' . $tour->image) }}')">
                                <div class="tours-card-overlay">
                                <div class="tours-card-header">
                                    <div class="tours-card-date">
                                        @if($tour->dates->first())
                                            <p>{{ \Carbon\Carbon::parse($tour->dates->first()->start_date)->format('d F Y') }}-{{ \Carbon\Carbon::parse($tour->dates->first()->end_date)->format('d F Y') }}</p>
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
                                    <button class="tours-card-button">ПОДРОБНЕЕ</button>
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
                    <img src="{{ asset('/images/entertainment.svg') }}" alt="Логотип">
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
                                        <p><strong>Опыт:</strong> {{ Str::limit($instructor->experience, 100) }}</p>
                                        <p><strong>О себе:</strong> {{ Str::limit($instructor->about, 150) }}</p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <!-- Навигация -->
                    <div class="swiper-button-prev"></div>
                    <div class="swiper-button-next"></div>
                    <!-- Пагинация -->
                    <div class="swiper-pagination"></div>
                </div>
            @else
                <!-- Статический ряд для 4 или менее инструкторов -->
                <div class="row">
                    @foreach($instructors as $instructor)
                        <div class="col-md-3">
                            <div class="instructor-card" style="background-image: url('{{ $instructor->photo ? asset('storage/' . $instructor->photo) : 'https://via.placeholder.com/300' }}')">
                                <div class="instructor-overlay">
                                    <h3>{{ $instructor->name }}</h3>
                                    <p><strong>Опыт:</strong> {{ Str::limit($instructor->experience, 100) }}</p>
                                    <p><strong>О себе:</strong> {{ Str::limit($instructor->about, 150) }}</p>
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

<!-- Подключение Swiper.js JS -->
<script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
<!-- Подключение Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Инициализация Swiper.js только если инструкторов больше 4
    @if($instructors->count() > 4)
    const swiper = new Swiper('.swiper', {
        slidesPerView: 4,
        spaceBetween: 20,
        navigation: {
            nextEl: '.swiper-button-next',
            prevEl: '.swiper-button-prev',
        },
        pagination: {
            el: '.swiper-pagination',
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
</script>
@endsection
