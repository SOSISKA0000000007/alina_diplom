<?php

namespace App\Http\Controllers;

use App\Models\RentalProduct;
use App\Models\Rental;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class RentalController extends Controller
{
    private $validTypes = [
        'Аксессуары',
        'Боты и носки',
        'Гидрокостюмы',
        'Компенсаторы',
        'Ласты для дайвинга',
        'Маски',
        'Перчатки',
        'Фонари',
        'Утеплители',
        'Экстрим-камеры',
        'Комплекты',
        'Трубки и аксессуары',
    ];

    public function index(Request $request)
    {
        $query = RentalProduct::query();
        $selectedType = $request->query('type');
        $sort = $request->query('sort');

        if ($selectedType && in_array($selectedType, $this->validTypes)) {
            $query->where('type', $selectedType);
        }

        if ($sort) {
            [$sortBy, $sortOrder] = explode('_', $sort);
            if (in_array($sortBy, ['name', 'price']) && in_array($sortOrder, ['asc', 'desc'])) {
                $query->orderBy($sortBy, $sortOrder);
            }
        }

        $products = $query->get();
        $types = $this->validTypes;

        return view('rental.index', compact('products', 'types', 'selectedType'));
    }

    public function filter(Request $request)
    {
        try {
            $selectedType = $request->query('type');
            $sort = $request->query('sort');

            Log::info('Filter request received', [
                'type' => $selectedType,
                'sort' => $sort,
                'ip' => $request->ip(),
                'url' => $request->fullUrl(),
            ]);

            $query = RentalProduct::query();
            if ($selectedType && in_array($selectedType, $this->validTypes)) {
                $query->where('type', $selectedType);
            }

            if ($sort) {
                [$sortBy, $sortOrder] = explode('_', $sort);
                if (in_array($sortBy, ['name', 'price']) && in_array($sortOrder, ['asc', 'desc'])) {
                    $query->orderBy($sortBy, $sortOrder);
                } else {
                    Log::warning('Invalid sort parameter', ['sort' => $sort]);
                }
            }

            $products = $query->get();

            if (!view()->exists('rental.partials.products')) {
                Log::error('View rental.partials.products does not exist');
                return response()->json([
                    'message' => 'Шаблон для товаров не найден',
                ], 500);
            }

            $html = view('rental.partials.products', compact('products'))->render();

            Log::info('Filter response prepared', [
                'product_count' => $products->count(),
                'type' => $selectedType,
                'sort' => $sort,
            ]);

            return response()->json([
                'html' => $html,
                'selectedType' => $selectedType ?: null,
            ]);
        } catch (\Exception $e) {
            Log::error('Error in filter method', [
                'error' => $e->getMessage(),
                'type' => $selectedType,
                'sort' => $sort,
                'stack' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'message' => 'Ошибка при фильтрации товаров: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function show(RentalProduct $product)
    {
        $canLeaveReview = false;
        if (Auth::check()) {
            $canLeaveReview = Rental::where('user_id', Auth::id())
                ->where('rental_product_id', $product->id)
                ->where('status', 'confirmed')
                ->where('end_date', '<', Carbon::today())
                ->whereDoesntHave('review')
                ->exists();
        }

        return view('rental.show', compact('product', 'canLeaveReview'));
    }

    public function checkAvailability(Request $request, RentalProduct $product)
    {
        if (!$request->isMethod('post')) {
            Log::warning('Invalid method for checkAvailability', [
                'method' => $request->method(),
                'ip' => $request->ip(),
                'url' => $request->fullUrl()
            ]);
            return response()->json([
                'error' => 'Method not allowed. Use POST.'
            ], 405);
        }

        $validator = Validator::make($request->all(), [
            'size' => 'required|string|in:' . implode(',', array_keys($product->sizes_quantity)),
            'quantity' => 'required|integer|min:1',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after_or_equal:start_date'
        ]);

        if ($validator->fails()) {
            Log::warning('Validation failed in checkAvailability', [
                'errors' => $validator->errors()->toArray(),
                'input' => $request->all()
            ]);
            return response()->json([
                'available' => false,
                'remaining' => 0,
                'errors' => $validator->errors()->toArray(),
                'message' => 'Validation failed'
            ], 422);
        }

        $size = $request->size;
        $quantity = (int) $request->quantity;
        $startDate = Carbon::parse($request->start_date);
        $endDate = Carbon::parse($request->end_date);

        Log::debug('checkAvailability input', [
            'product_id' => $product->id,
            'size' => $size,
            'quantity' => $quantity,
            'start_date' => $startDate->toDateString(),
            'end_date' => $endDate->toDateString(),
            'ip' => $request->ip()
        ]);

        $totalAvailable = $product->sizes_quantity[$size] ?? 0;

        $rentals = Rental::where('rental_product_id', $product->id)
            ->where('size', $size)
            ->whereIn('status', ['pending', 'confirmed'])
            ->where(function ($query) use ($startDate, $endDate) {
                $query->whereBetween('start_date', [$startDate, $endDate])
                    ->orWhereBetween('end_date', [$startDate, $endDate])
                    ->orWhere(function ($q) use ($startDate, $endDate) {
                        $q->where('start_date', '<=', $startDate)
                            ->where('end_date', '>=', $endDate);
                    });
            })
            ->get();

        $reservedQuantity = $rentals->sum('quantity');
        $remaining = $totalAvailable - $reservedQuantity;
        $available = $remaining >= $quantity;

        Log::info('checkAvailability result', [
            'product_id' => $product->id,
            'size' => $size,
            'quantity' => $quantity,
            'start_date' => $startDate->toDateString(),
            'end_date' => $endDate->toDateString(),
            'total_available' => $totalAvailable,
            'reserved_quantity' => $reservedQuantity,
            'remaining' => $remaining,
            'available' => $available
        ]);

        return response()->json([
            'available' => $available,
            'remaining' => $remaining,
            'total_available' => $totalAvailable,
            'reserved_quantity' => $reservedQuantity
        ]);
    }

    public function store(Request $request, RentalProduct $product)
    {
        $request->validate([
            'size' => 'required|string|in:' . implode(',', array_keys($product->sizes_quantity)),
            'quantity' => 'required|integer|min:1',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        $size = $request->size;
        $quantity = (int) $request->quantity;

        Log::debug('Raw request quantity', [
            'raw_quantity' => $request->quantity,
            'parsed_quantity' => $quantity,
            'quantity_type' => gettype($request->quantity),
        ]);

        $startDate = Carbon::parse($request->start_date);
        $endDate = Carbon::parse($request->end_date);

        Log::debug('store input', [
            'product_id' => $product->id,
            'size' => $size,
            'quantity' => $quantity,
            'quantity_type' => gettype($request->quantity),
            'start_date' => $startDate->toDateString(),
            'end_date' => $endDate->toDateString(),
            'ip' => $request->ip()
        ]);

        return DB::transaction(function () use ($product, $size, $quantity, $startDate, $endDate) {
            $product = RentalProduct::where('id', $product->id)->lockForUpdate()->first();

            $totalAvailable = $product->sizes_quantity[$size] ?? 0;
            $rentals = Rental::where('rental_product_id', $product->id)
                ->where('size', $size)
                ->whereIn('status', ['pending', 'confirmed'])
                ->where(function ($query) use ($startDate, $endDate) {
                    $query->whereBetween('start_date', [$startDate, $endDate])
                        ->orWhereBetween('end_date', [$startDate, $endDate])
                        ->orWhere(function ($q) use ($startDate, $endDate) {
                            $q->where('start_date', '<=', $startDate)
                                ->where('end_date', '>=', $endDate);
                        });
                })
                ->lockForUpdate()
                ->get();

            $reservedQuantity = $rentals->sum('quantity');
            $remaining = $totalAvailable - $reservedQuantity;

            Log::info('store availability check', [
                'product_id' => $product->id,
                'size' => $size,
                'quantity' => $quantity,
                'start_date' => $startDate->toDateString(),
                'end_date' => $endDate->toDateString(),
                'total_available' => $totalAvailable,
                'reserved_quantity' => $reservedQuantity,
                'remaining' => $remaining,
                'rentals_count' => $rentals->count(),
                'rental_ids' => $rentals->pluck('id')->toArray()
            ]);

            if ($remaining < $quantity) {
                Log::warning('Insufficient quantity in store', [
                    'product_id' => $product->id,
                    'size' => $size,
                    'requested_quantity' => $quantity,
                    'remaining' => $remaining
                ]);
                return back()->with('error', 'Недостаточно товара для аренды')->withInput();
            }

            $days = $endDate->diffInDays($startDate) + 1;
            $totalPrice = $product->price * $days * $quantity;

            $rental = Rental::create([
                'user_id' => Auth::id(),
                'rental_product_id' => $product->id,
                'size' => $size,
                'quantity' => $quantity,
                'start_date' => $startDate,
                'end_date' => $endDate,
                'total_price' => $totalPrice,
                'status' => 'pending'
            ]);

            Log::info('Rental created', [
                'rental_id' => $rental->id,
                'product_id' => $product->id,
                'size' => $size,
                'quantity' => $rental->quantity,
                'start_date' => $startDate->toDateString(),
                'end_date' => $endDate->toDateString()
            ]);

            return redirect()->route('profile.rent')
                ->with('success', 'Товар успешно арендован');
        }, 5);
    }

    public function confirm(Rental $rental)
    {
        if ($rental->status === 'pending') {
            $rental->update(['status' => 'confirmed']);
            Log::info('Rental confirmed', ['rental_id' => $rental->id]);
            return back()->with('success', 'Аренда успешно подтверждена');
        }

        Log::warning('Cannot confirm rental', ['rental_id' => $rental->id, 'status' => $rental->status]);
        return back()->with('error', 'Невозможно подтвердить эту аренду');
    }

    public function reject(Rental $rental)
    {
        if ($rental->status === 'pending') {
            $rental->update(['status' => 'rejected']);
            Log::info('Rental rejected', ['rental_id' => $rental->id]);
            return back()->with('success', 'Аренда отклонена');
        }

        Log::warning('Cannot reject rental', ['rental_id' => $rental->id, 'status' => $rental->status]);
        return back()->with('error', 'Невозможно отклонить эту аренду');
    }

    public function storeReview(Request $request, RentalProduct $product)
    {
        $request->validate([
            'pros' => 'required|string|max:500',
            'cons' => 'required|string|max:500',
            'comment' => 'required|string|max:1000',
            'rental_id' => 'required|exists:rentals,id',
        ]);

        $rental = Rental::findOrFail($request->rental_id);

        if ($rental->user_id !== Auth::id() || $rental->rental_product_id !== $product->id || $rental->status !== 'confirmed' || $rental->end_date >= Carbon::today()) {
            Log::warning('Unauthorized review attempt', [
                'user_id' => Auth::id(),
                'rental_id' => $rental->id,
                'product_id' => $product->id,
                'status' => $rental->status,
                'end_date' => $rental->end_date->toDateString()
            ]);
            return response()->json(['error' => 'Вы не можете оставить отзыв для этого товара'], 403);
        }

        if ($rental->review()->exists()) {
            Log::warning('Review already exists for rental', ['rental_id' => $rental->id]);
            return response()->json(['error' => 'Вы уже оставили отзыв для этой аренды'], 422);
        }

        $review = Review::create([
            'rental_id' => $rental->id,
            'user_id' => Auth::id(),
            'rental_product_id' => $product->id,
            'pros' => $request->pros,
            'cons' => $request->cons,
            'comment' => $request->comment,
        ]);

        Log::info('Review created', [
            'review_id' => $review->id,
            'rental_id' => $rental->id,
            'product_id' => $product->id,
            'user_id' => Auth::id()
        ]);

        return response()->json(['success' => true, 'message' => 'Отзыв успешно добавлен']);
    }

    public function getReviews(Request $request, RentalProduct $product)
    {
        $page = $request->query('page', 1);
        $perPage = 10;

        $reviews = Review::where('rental_product_id', $product->id)
            ->with('user')
            ->latest()
            ->paginate($perPage, ['*'], 'page', $page);

        $html = view('rental.partials.reviews', compact('reviews'))->render();

        return response()->json([
            'html' => $html,
            'hasMore' => $reviews->hasMorePages(),
            'nextPage' => $reviews->currentPage() + 1
        ]);
    }
}
