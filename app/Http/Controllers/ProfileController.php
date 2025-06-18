<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;
use Illuminate\Support\Facades\Validator;

class ProfileController extends Controller
{
    public function index()
    {
        return view('profile.index');
    }

    public function bookings()
    {
        $bookings = auth()->user()
            ->bookings()
            ->with(['tour.prices', 'tourDate'])
            ->get();

        return view('profile.bookings', compact('bookings'));
    }

    public function rent()
    {
        $rentals = auth()->user()->rentals()->with('product')->get();
        return view('profile.rent', compact('rentals'));
    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . auth()->id()],
            'phone' => ['nullable', 'string', 'max:20'],
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        auth()->user()->update([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
        ]);

        return redirect()->route('profile.index')->with('success', 'Данные успешно обновлены');
    }
}
