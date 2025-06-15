<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\RentalController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\Admin\AdminInstructorController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

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

// Маршруты аренды (общедоступные)
Route::get('/rent/filter-products', [RentalController::class, 'filter'])->name('rent.filter');
Route::get('/rent', [RentalController::class, 'index'])->name('rent.index');
Route::get('/rent/{product}', [RentalController::class, 'show'])->name('rent.show');
Route::post('/rent/{product}/check-availability', [RentalController::class, 'checkAvailability'])->name('rent.check-availability');
Route::post('/rent/{product}', [RentalController::class, 'store'])->name('rent.store');
Route::post('/rent/{product}/reviews', [RentalController::class, 'storeReview'])->name('rent.reviews.store')->middleware('auth');
Route::get('/rent/{product}/reviews', [RentalController::class, 'getReviews'])->name('rent.reviews');

// Маршруты отзывов туров
Route::post('/tours/{tour}/reviews', [BookingController::class, 'storeReview'])->name('tours.reviews.store')->middleware('auth');
Route::get('/tours/reviews', [BookingController::class, 'getReviews'])->name('tours.reviews');

// Маршрут для контактной формы
Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');

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
    Route::get('/admin/bookings', [AdminController::class, 'bookings'])->name('admin.bookings.index');
    Route::post('/admin/bookings/{booking}/confirm', [AdminController::class, 'confirmBooking'])->name('admin.bookings.confirm');
    Route::post('/admin/bookings/{booking}/reject', [AdminController::class, 'rejectBooking'])->name('admin.bookings.reject');
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

    // Маршрут для управления пользователями
    Route::get('/admin/users', [AdminUserController::class, 'index'])->name('admin.users.index');
});

// Маршруты для статичных страниц
Route::get('/about-us', function () {
    return view('about-us');
})->name('about.us');

Route::get('/tours/tour-1', function () {
    return view('tours.tour-1');
})->name('tours.tour-1');
Route::get('/tours/tour-2', function () {
    return view('tours.tour-2');
})->name('tours.tour-2');
Route::get('/tours/tour-3', function () {
    return view('tours.tour-3');
})->name('tours.tour-3');
Route::get('/tours/tour-4', function() {
    return view('tours.tour-4');
})->name('tours.tour-4');
Route::get('/tours/tour-5', function () {
    return view('tours.tour-5');
})->name('tours.tour-5');

// API маршруты
Route::get('/api/available-places/{tourDate}', [App\Http\Controllers\Api\TourDateController::class, 'getAvailablePlaces']);
