<?php

namespace App\Http\Controllers;

use App\Models\Tour;
use App\Models\TourDate;
use App\Models\Booking;
use App\Models\TourReview;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class BookingController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'tour_id' => 'required|exists:tours,id',
            'date_id' => 'required|exists:tour_dates,id',
            'people_count' => 'required|integer|min:1|max:6',
        ]);

        $userId = auth()->id();
        $tourId = $request->tour_id;
        $dateId = $request->date_id;

        // Проверка на повторную бронь
        $alreadyBooked = Booking::where('user_id', $userId)
            ->where('tour_id', $tourId)
            ->where('tour_date_id', $dateId)
            ->exists();

        if ($alreadyBooked) {
            return response()->json([
                'success' => false,
                'message' => 'Вы уже забронировали этот тур на выбранную дату.',
            ], 409); // 409 Conflict
        }

        // Проверяем доступное количество мест
        $date = TourDate::findOrFail($dateId);
        $totalBooked = Booking::where('tour_date_id', $date->id)->sum('people_count');
        $availablePlaces = 6 - $totalBooked;

        if ($request->people_count > $availablePlaces) {
            return response()->json([
                'success' => false,
                'message' => "Доступно только {$availablePlaces} мест"
            ], 422);
        }

        // Создаем бронирование
        Booking::create([
            'user_id' => $userId,
            'tour_id' => $tourId,
            'tour_date_id' => $dateId,
            'people_count' => $request->people_count,
            'status' => 'pending',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Бронирование успешно создано'
        ]);
    }

    public function cancel(Booking $booking)
    {
        if ($booking->user_id !== auth()->id()) {
            abort(403);
        }

        $booking->delete();

        return redirect()
            ->route('profile.bookings')
            ->with('success', 'Бронирование успешно отменено');
    }

    public function storeReview(Request $request, Tour $tour)
    {
        $request->validate([
            'booking_id' => 'required|exists:bookings,id',
            'comment' => 'required|string|max:1000',
        ]);

        $booking = Booking::findOrFail($request->booking_id);

        // Проверка: пользователь, бронирование, тур, статус и дата завершения
        if ($booking->user_id !== Auth::id() ||
            $booking->tour_id !== $tour->id ||
            $booking->status !== 'confirmed' ||
            $booking->tourDate->end_date >= Carbon::today()) {
            Log::warning('Unauthorized review attempt', [
                'user_id' => Auth::id(),
                'booking_id' => $booking->id,
                'tour_id' => $tour->id,
                'status' => $booking->status,
                'end_date' => $booking->tourDate->end_date->toDateString()
            ]);
            return response()->json(['error' => 'Вы не можете оставить отзыв для этого тура'], 403);
        }

        // Проверка: не оставлен ли уже отзыв
        if ($booking->tourReview()->exists()) {
            Log::warning('Review already exists for booking', ['booking_id' => $booking->id]);
            return response()->json(['error' => 'Вы уже оставили отзыв для этого тура'], 422);
        }

        $review = TourReview::create([
            'tour_id' => $tour->id,
            'booking_id' => $booking->id,
            'user_id' => Auth::id(),
            'comment' => $request->comment,
        ]);

        Log::info('Tour review created', [
            'review_id' => $review->id,
            'tour_id' => $tour->id,
            'booking_id' => $booking->id,
            'user_id' => Auth::id()
        ]);

        return response()->json(['success' => true, 'message' => 'Отзыв успешно добавлен']);
    }

    public function getReviews(Request $request)
    {
        $perPage = 10;

        $reviews = TourReview::with(['user', 'tour'])
            ->latest()
            ->paginate($perPage);

        $html = view('tours.partials.reviews', compact('reviews'))->render();

        return response()->json([
            'html' => $html,
            'hasMore' => $reviews->hasMorePages(),
            'nextPage' => $reviews->currentPage() + 1
        ]);
    }
}
