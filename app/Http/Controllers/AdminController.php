<?php

namespace App\Http\Controllers;

use App\Models\Tour;
use App\Models\TourDate;
use App\Models\Price;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AdminController extends Controller
{
    public function index()
    {
        $tours = Tour::with(['dates', 'prices'])->get();
        return view('admin.index', compact('tours'));
    }

    public function createTour()
    {
        return view('admin.tours.create');
    }

    public function storeTour(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'image' => 'required|string',
        ]);

        Tour::create($request->all());

        return redirect()->route('admin.index')->with('success', 'Тур успешно создан.');
    }

    public function storeTourDate(Request $request, Tour $tour)
    {
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
        ]);

        $tour->dates()->create($request->all());

        return redirect()->route('admin.index')->with('success', 'Даты тура успешно добавлены.');
    }

    public function storeTourPrice(Request $request, Tour $tour)
    {
        $request->validate([
            'regular_price' => 'required|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0',
        ]);

        $tour->prices()->create($request->all());

        return redirect()->route('admin.index')->with('success', 'Цены тура успешно добавлены.');
    }

    public function destroyTourDate(Tour $tour, TourDate $date)
    {
        $date->delete();
        return redirect()->route('admin.index')->with('success', 'Дата тура успешно удалена.');
    }

    public function destroyTourPrice(Tour $tour, Price $price)
    {
        $price->delete();
        return redirect()->route('admin.index')->with('success', 'Цена тура успешно удалена.');
    }

    public function updateTourPrice(Request $request, Tour $tour, Price $price)
    {
        $request->validate([
            'regular_price' => 'required|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0',
        ]);

        $price->update($request->all());

        return response()->json(['success' => true, 'message' => 'Цены тура успешно обновлены.']);
    }

    public function rentals()
    {
        $rentals = \App\Models\Rental::with(['user', 'product'])->latest()->get();
        return view('admin.rentals.index', compact('rentals'));
    }

    public function bookings()
    {
        $bookings = Booking::with(['user', 'tour', 'tourDate'])->latest()->get();
        return view('admin.bookings.index', compact('bookings'));
    }

    public function confirmBooking(Booking $booking)
    {
        if ($booking->status === 'pending') {
            $booking->update(['status' => 'confirmed']);
            Log::info('Booking confirmed', ['booking_id' => $booking->id]);
            return back()->with('success', 'Бронирование успешно подтверждено');
        }

        Log::warning('Cannot confirm booking', ['booking_id' => $booking->id, 'status' => $booking->status]);
        return back()->with('error', 'Невозможно подтвердить это бронирование');
    }

    public function rejectBooking(Booking $booking)
    {
        if ($booking->status === 'pending') {
            $booking->update(['status' => 'rejected']);
            Log::info('Booking rejected', ['booking_id' => $booking->id]);
            return back()->with('success', 'Бронирование отклонено');
        }

        Log::warning('Cannot reject booking', ['booking_id' => $booking->id, 'status' => $booking->status]);
        return back()->with('error', 'Невозможно отклонить это бронирование');
    }
}
