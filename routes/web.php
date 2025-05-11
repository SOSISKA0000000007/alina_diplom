<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\RentalController;
use App\Http\Controllers\Admin\AdminInstructorController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', [HomeController::class, 'index'])->name('welcome');
Route::get('/rent', [RentalController::class, 'index'])->name('rent.index');
Route::get('/rent/{product}', [RentalController::class, 'show'])->name('rent.show');
Route::post('/rent/{product}', [RentalController::class, 'store'])->name('rent.store');
Route::post('/rent/{product}/check-availability', [RentalController::class, 'checkAvailability'])->name('rent.checkAvailability');
Route::post('/rent/{product}/review', [RentalController::class, 'storeReview'])->name('rent.review.store')->middleware('auth');
Route::get('/rent/{product}/reviews', [RentalController::class, 'getReviews'])->name('rent.reviews');

// Маршруты авторизации
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Маршруты профиля
Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::get('/profile/bookings', [ProfileController::class, 'bookings'])->name('profile.bookings');
    Route::get('/profile/rent', [ProfileController::class, 'rent'])->name('profile.rent');
    Route::delete('/booking/{booking}', [BookingController::class, 'cancel'])->name('booking.cancel');
    Route::post('/booking', [BookingController::class, 'store'])->name('booking.store');
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::resource('rental-products', \App\Http\Controllers\Admin\RentalProductController::class);
    });
});

// Маршруты админ-панели
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/admin', [AdminController::class, 'index'])->name('admin.index');
    Route::get('/admin/rentals', [AdminController::class, 'rentals'])->name('admin.rentals.index');
    Route::get('/admin/tours/create', [AdminController::class, 'createTour'])->name('admin.tours.create');
    Route::post('/admin/tours', [AdminController::class, 'storeTour'])->name('admin.tours.store');
    Route::post('/admin/tours/{tour}/dates', [AdminController::class, 'storeTourDate'])->name('admin.tours.dates.store');
    Route::delete('/admin/tours/{tour}/dates/{date}', [AdminController::class, 'destroyTourDate'])->name('admin.tours.dates.destroy');
    Route::post('/admin/tours/{tour}/prices', [AdminController::class, 'storeTourPrice'])->name('admin.tours.prices.store');
    Route::put('/admin/tours/{tour}/prices/{price}', [AdminController::class, 'updateTourPrice'])->name('admin.tours.prices.update');
    Route::delete('/admin/tours/{tour}/prices/{price}', [AdminController::class, 'destroyTourPrice'])->name('admin.tours.prices.destroy');

    // Маршруты для управления арендой
    Route::post('/admin/rentals/{rental}/confirm', [RentalController::class, 'confirm'])->name('admin.rentals.confirm');
    Route::post('/admin/rentals/{rental}/reject', [RentalController::class, 'reject'])->name('admin.rentals.reject');

    // Маршруты для инструкторов
    Route::get('/admin/instructors/create', [AdminInstructorController::class, 'create'])->name('admin.instructors.create');
    Route::post('/admin/instructors', [AdminInstructorController::class, 'store'])->name('admin.instructors.store');
});

Route::get('/about-us', function () {
    return view('about-us');
})->name('about.us');

// API маршруты
Route::get('/api/available-places/{dateId}', [App\Http\Controllers\Api\TourDateController::class, 'getAvailablePlaces']);
