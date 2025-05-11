<?php

namespace App\Http\Controllers;

use App\Models\Tour;
use Illuminate\Support\Facades\Log;

class WelcomeController extends Controller
{
    public function index()
    {
        $tours = Tour::with(['dates', 'prices'])->get();
        
        // Отладочная информация
        foreach ($tours as $tour) {
            Log::info("Tour: {$tour->title}", [
                'id' => $tour->id,
                'dates' => $tour->dates->toArray(),
                'prices' => $tour->prices->toArray()
            ]);
        }
        
        return view('welcome', compact('tours'));
    }
} 