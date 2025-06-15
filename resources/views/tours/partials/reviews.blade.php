@if($reviews->count() > 0)
    <div class="swiper">
        <div class="swiper-wrapper">
            @foreach($reviews as $review)
                <div class="swiper-slide">
                    <div class="review-card">
                        <h5>{{ $review->user->name }}</h5>
                        <h6>{{ $review->tour->title }}</h6>
                        <p>{{ \Illuminate\Support\Str::limit($review->comment, 200) }}</p>
                        <small>{{ \Carbon\Carbon::parse($review->created_at)->translatedFormat('d F Y') }}</small>
                    </div>
                </div>
            @endforeach
        </div>
        <!-- Навигация -->
        <div class="swiper-button-prev"></div>
        <div class="swiper-button-next"></div>
        <div class="swiper-pagination"></div>
    </div>
@else
    <p>Отзывы для туров пока отсутствуют.</p>
@endif

<style>
    .review-card {
        border: 1px solid #ddd;
        padding: 15px;
        margin: 10px;
        border-radius: 5px;
        background: #fff;
        height: calc(100% - 20px);
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }
    .review-card h5 {
        margin: 0 0 5px;
        font-size: 1.2em;
    }
    .review-card h6 {
        margin: 0 0 10px;
        font-size: 1em;
        color: #555;
    }
    .review-card p {
        margin: 0 0 10px;
        flex-grow: 1;
    }
    .review-card small {
        color: #666;
    }
    .reviews-controls {
        display: flex;
        justify-content: flex-end;
        align-items: center;
        margin-bottom: 20px;
    }
    .tour-reviews .swiper {
        padding-bottom: 40px;
    }
    .tour-reviews .swiper-pagination {
        bottom: 10px;
    }
    .tour-reviews .swiper-button-prev,
    .tour-reviews .swiper-button-next {
        color: #333;
    }
</style>
