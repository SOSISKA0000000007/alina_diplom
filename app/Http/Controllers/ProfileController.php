<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;

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
} 