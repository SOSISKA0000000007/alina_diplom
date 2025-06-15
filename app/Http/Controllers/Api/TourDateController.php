<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\TourDate;
use App\Models\Booking;
use Illuminate\Http\Request;

class TourDateController extends Controller
{
    public function getAvailablePlaces($dateId)
    {
        $date = TourDate::findOrFail($dateId);
        $totalBooked = Booking::where('tour_date_id', $date->id)->sum('people_count');
        $availablePlaces = max(0, 6 - $totalBooked);

        return response()->json([
            'available_places' => $availablePlaces
        ]);
    }
}
