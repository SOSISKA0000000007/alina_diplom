<?php

namespace App\Http\Controllers;

use App\Models\Tour;
use App\Models\TourDate;
use App\Models\Booking;
use Illuminate\Http\Request;

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
}
