@if($reviews->count() > 0)
    <div class="swiper">
        <div class="swiper-wrapper">
            @foreach($reviews as $review)
                <div class="swiper-slide">
                    <div class="review-card">
                        <div class="review-card-header">
                            <h5>{{ $review->user->name }}</h5>
                            <div class="review-card-meta">
                                <p>{{ $review->tour->title }}</p>
                                <p>{{ \Carbon\Carbon::parse($review->created_at)->format('d.m.Y') }}</p>
                            </div>
                        </div>
                        <p class="review-comment">{{ \Illuminate\Support\Str::limit($review->comment, 200) }}</p>
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
        padding: 20px;
        margin: 10px;
        border-radius: 5px;
        background: #fff;
        width: 90%; /* Wider card */
        max-width: 600px;
        height: calc(100% - 20px);
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        box-sizing: border-box;
        gap: 20px;
    }
    .review-card-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin: 0;
    }
    .review-card-header h5 {
        font-family: com-bold;
        color: var(--blue);
        margin: 0;
        font-size: 24px;
        flex: 1; /* Take available space on the left */
    }
    .review-card-meta {
        display: flex;
        gap: 10px;
        justify-content: end;
    }
    .review-card-meta p {
        font-family: com-reg;
        font-size: 16px;
        margin: 0;
        color: var(--blue);
    }
    .review-comment {
        font-family: com-reg;
        font-size: 16px;
        color: var(--blue);
        margin: 0;
        flex-grow: 1;
        width: 100%; /* Full width for comment */
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
