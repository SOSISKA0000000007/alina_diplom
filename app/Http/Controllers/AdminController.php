<?php

namespace App\Http\Controllers;

use App\Models\Tour;
use App\Models\TourDate;
use App\Models\Price;
use Illuminate\Http\Request;

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
}
