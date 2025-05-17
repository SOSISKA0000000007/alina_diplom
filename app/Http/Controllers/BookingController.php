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

        // Проверяем доступное количество мест
        $date = TourDate::findOrFail($request->date_id);
        $totalBooked = Booking::where('tour_date_id', $date->id)->sum('people_count');
        $availablePlaces = 6 - $totalBooked;

        if ($request->people_count > $availablePlaces) {
            return response()->json([
                'success' => false,
                'message' => "Доступно только {$availablePlaces} мест"
            ], 422);
        }

        // Создаем бронирование
        $booking = new Booking();
        $booking->user_id = auth()->id();
        $booking->tour_id = $request->tour_id;
        $booking->tour_date_id = $request->date_id;
        $booking->people_count = $request->people_count;
        $booking->status = 'pending';
        $booking->save();

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
