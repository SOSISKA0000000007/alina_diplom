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
        try {
            $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'required|string',
                'image' => 'required|string',
            ]);

            Tour::create($request->all());

            return redirect()->route('admin.index')->with('success', 'Тур успешно создан.');
        } catch (\Exception $e) {
            Log::error('Error storing tour', ['error' => $e->getMessage()]);
            return redirect()->route('admin.index')->with('error', 'Ошибка при создании тура.');
        }
    }

    public function storeTourDate(Request $request, Tour $tour)
    {
        try {
            $request->validate([
                'start_date' => 'required|date',
                'end_date' => 'required|date|after:start_date',
            ]);

            $tour->dates()->create($request->all());

            return redirect()->route('admin.index')->with('success', 'Даты тура успешно добавлены.');
        } catch (\Exception $e) {
            Log::error('Error storing tour date', ['tour_id' => $tour->id, 'error' => $e->getMessage()]);
            return redirect()->route('admin.index')->with('error', 'Ошибка при добавлении дат.');
        }
    }

    public function storeTourPrice(Request $request, Tour $tour)
    {
        try {
            $request->validate([
                'regular_price' => 'required|numeric|min:0',
                'sale_price' => 'nullable|numeric|min:0',
            ]);

            $tour->prices()->create($request->all());

            return response()->json([
                'success' => true,
                'message' => 'Цены тура успешно добавлены.'
            ]);
        } catch (\Exception $e) {
            Log::error('Error storing tour price', [
                'tour_id' => $tour->id,
                'error' => $e->getMessage(),
                'request_data' => $request->all()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Ошибка при добавлении цен: ' . $e->getMessage()
            ], 422);
        }
    }

    public function updateTourPrice(Request $request, Tour $tour, Price $price)
    {
        try {
            $request->validate([
                'regular_price' => 'required|numeric|min:0',
                'sale_price' => 'nullable|numeric|min:0',
            ]);

            $price->update($request->all());

            return response()->json([
                'success' => true,
                'message' => 'Цены тура успешно обновлены.'
            ]);
        } catch (\Exception $e) {
            Log::error('Error updating tour price', [
                'tour_id' => $tour->id,
                'price_id' => $price->id,
                'error' => $e->getMessage(),
                'request_data' => $request->all()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Ошибка при обновлении цен: ' . $e->getMessage()
            ], 422);
        }
    }

    public function destroyTourDate(Tour $tour, TourDate $date)
    {
        try {
            $date->delete();
            return redirect()->route('admin.index')->with('success', 'Дата тура успешно удалена.');
        } catch (\Exception $e) {
            Log::error('Error deleting tour date', ['tour_id' => $tour->id, 'date_id' => $date->id, 'error' => $e->getMessage()]);
            return redirect()->route('admin.index')->with('error', 'Ошибка при удалении даты.');
        }
    }

    public function destroyTourPrice(Tour $tour, Price $price)
    {
        try {
            $price->delete();
            return redirect()->route('admin.index')->with('success', 'Цена тура успешно удалена.');
        } catch (\Exception $e) {
            Log::error('Error deleting tour price', ['tour_id' => $tour->id, 'price_id' => $price->id, 'error' => $e->getMessage()]);
            return redirect()->route('admin.index')->with('error', 'Ошибка при удалении цены.');
        }
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
        try {
            if ($booking->status === 'pending') {
                $booking->update(['status' => 'confirmed']);
                Log::info('Booking confirmed', ['booking_id' => $booking->id]);
                return back()->with('success', 'Бронирование успешно подтверждено');
            }
            throw new \Exception('Cannot confirm booking with status: ' . $booking->status);
        } catch (\Exception $e) {
            Log::warning('Cannot confirm booking', ['booking_id' => $booking->id, 'status' => $booking->status, 'error' => $e->getMessage()]);
            return back()->with('error', 'Невозможно подтвердить это бронирование');
        }
    }

    public function rejectBooking(Booking $booking)
    {
        try {
            if ($booking->status === 'pending') {
                $booking->update(['status' => 'rejected']);
                Log::info('Booking rejected', ['booking_id' => $booking->id]);
                return back()->with('success', 'Бронирование отклонено');
            }
            throw new \Exception('Cannot reject booking with status: ' . $booking->status);
        } catch (\Exception $e) {
            Log::warning('Cannot reject booking', ['booking_id' => $booking->id, 'status' => $booking->status, 'error' => $e->getMessage()]);
            return back()->with('error', 'Невозможно отклонить это бронирование');
        }
    }
}
