@foreach($reviews as $review)
    <div class="review-item">
        <div class="review-header">
            <span class="review-user">{{ $review->user->name }}</span>
            <span class="review-date">{{ $review->created_at->format('d.m.Y') }}</span>
        </div>
        <div class="review-content">
            <p><strong>Плюсы:</strong> {{ $review->pros }}</p>
            <p><strong>Минусы:</strong> {{ $review->cons }}</p>
            <p><strong>Комментарий:</strong> {{ $review->comment }}</p>
        </div>
    </div>
@endforeach
