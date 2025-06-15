<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class AdminUserController extends Controller
{
    public function index(Request $request)
    {
        $filter = $request->query('status', 'all'); // По умолчанию показываем всех

        $usersQuery = User::with(['bookings.tour', 'bookings.tourDate']);

        if ($filter === 'confirmed') {
            // Пользователи с бронированиями, у которых есть статус confirmed
            $usersQuery->whereHas('bookings', function ($query) {
                $query->where('status', 'confirmed');
            });
        } elseif ($filter === 'pending') {
            // Пользователи с бронированиями, у которых есть статус pending
            $usersQuery->whereHas('bookings', function ($query) {
                $query->where('status', 'pending');
            });
        } elseif ($filter === 'no_bookings') {
            // Пользователи без бронирований
            $usersQuery->doesntHave('bookings');
        }

        $users = $usersQuery->get();

        return view('admin.users.index', compact('users', 'filter'));
    }
}
