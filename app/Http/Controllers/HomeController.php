<?php

namespace App\Http\Controllers;

use App\Models\Instructor;
use App\Models\Tour;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $tours = Tour::with(['dates', 'prices'])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        $instructors = Instructor::all();

        return view('welcome', compact('tours', 'instructors'));
    }
}
