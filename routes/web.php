<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\RentalController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Admin\AdminInstructorController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Admin\RentalProductController;
use App\Http\Controllers\Api\TourDateController;
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

// Общедоступные маршруты
Route::get('/', [HomeController::class, 'index'])->name('welcome');

// Аренда (общедоступные)
Route::group(['prefix' => 'rent', 'as' => 'rent.'], function () {
    Route::get('/', [RentalController::class, 'index'])->name('index');
    Route::get('/filter-products', [RentalController::class, 'filter'])->name('filter');
    Route::get('/{product}', [RentalController::class, 'show'])->name('show');
    Route::post('/{product}/check-availability', [RentalController::class, 'checkAvailability'])->name('check-availability');
    Route::post('/{product}', [RentalController::class, 'store'])->name('store');
    Route::post('/{product}/reviews', [RentalController::class, 'storeReview'])->name('reviews.store')->middleware('auth');
    Route::get('/{product}/reviews', [RentalController::class, 'getReviews'])->name('reviews');
});

// Отзывы туров
Route::group(['prefix' => 'tours', 'as' => 'tours.'], function () {
    Route::post('/{tour}/reviews', [BookingController::class, 'storeReview'])->name('reviews.store')->middleware('auth');
    Route::get('/reviews', [BookingController::class, 'getReviews'])->name('reviews');
});

// Контактная форма
Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');

// Статичные страницы
Route::get('/about-us', fn() => view('about-us'))->name('about.us');
Route::get('/tours/tour-1', fn() => view('tours.tour-1'))->name('tours.tour-1');
Route::get('/tours/tour-2', fn() => view('tours.tour-2'))->name('tours.tour-2');
Route::get('/tours/tour-3', fn() => view('tours.tour-3'))->name('tours.tour-3');
Route::get('/tours/tour-4', fn() => view('tours.tour-4'))->name('tours.tour-4');
Route::get('/tours/tour-5', fn() => view('tours.tour-5'))->name('tours.tour-5');

// Авторизация
Route::group(['middleware' => 'guest'], function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Маршруты для авторизованных пользователей
Route::middleware('auth')->group(function () {
    // Профиль
    Route::group(['prefix' => 'profile', 'as' => 'profile.'], function () {
        Route::get('/', [ProfileController::class, 'index'])->name('index');
        Route::get('/bookings', [ProfileController::class, 'bookings'])->name('bookings');
        Route::get('/rent', [ProfileController::class, 'rent'])->name('rent');
        Route::put('/', [ProfileController::class, 'update'])->name('update');
    });

    // Бронирования
    Route::post('/booking', [BookingController::class, 'store'])->name('booking.store');
    Route::delete('/booking/{booking}', [BookingController::class, 'cancel'])->name('booking.cancel');

    // Управление продуктами аренды (для админов, но в группе auth)
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::resource('rental-products', RentalProductController::class);
    });
});

// Маршруты админ-панели
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminController::class, 'index'])->name('index');
    Route::get('/rentals', [AdminController::class, 'rentals'])->name('rentals.index');
    Route::get('/bookings', [AdminController::class, 'bookings'])->name('bookings.index');
    Route::post('/bookings/{booking}/confirm', [AdminController::class, 'confirmBooking'])->name('bookings.confirm');
    Route::post('/bookings/{booking}/reject', [AdminController::class, 'rejectBooking'])->name('bookings.reject');

    // Управление турами
    Route::group(['prefix' => 'tours', 'as' => 'tours.'], function () {
        Route::get('/create', [AdminController::class, 'createTour'])->name('create');
        Route::post('/', [AdminController::class, 'storeTour'])->name('store');

        // Даты туров
        Route::post('/{tour}/dates', [AdminController::class, 'storeTourDate'])->name('dates.store');
        Route::delete('/{tour}/dates/{date}', [AdminController::class, 'destroyTourDate'])->name('dates.destroy');

        // Цены туров
        Route::post('/{tour}/prices', [AdminController::class, 'storeTourPrice'])->name('prices.store');
        Route::put('/{tour}/prices/{price}', [AdminController::class, 'updateTourPrice'])->name('prices.update');
        Route::delete('/{tour}/prices/{price}', [AdminController::class, 'destroyTourPrice'])->name('prices.destroy');
    });

    // Управление арендой
    Route::post('/rentals/{rental}/confirm', [RentalController::class, 'confirm'])->name('rentals.confirm');
    Route::post('/rentals/{rental}/reject', [RentalController::class, 'reject'])->name('rentals.reject');

    // Инструкторы
    Route::get('/instructors/create', [AdminInstructorController::class, 'create'])->name('instructors.create');
    Route::post('/instructors', [AdminInstructorController::class, 'store'])->name('instructors.store');

    // Пользователи
    Route::get('/users', [AdminUserController::class, 'index'])->name('users.index');
});

// API маршруты
Route::prefix('api')->group(function () {
    Route::get('/available-places/{tourDate}', [TourDateController::class, 'getAvailablePlaces'])->name('api.available-places');
});
