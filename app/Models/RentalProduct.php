<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class RentalProduct extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'type',
        'price',
        'description',
        'images',
        'sizes_quantity',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'sizes_quantity' => 'array',
        'images' => 'array',
    ];

    /**
     * Get the rentals associated with the product.
     */
    public function rentals()
    {
        return $this->hasMany(Rental::class, 'rental_product_id');
    }

    /**
     * Get the reviews associated with the product.
     */
    public function reviews()
    {
        return $this->hasMany(Review::class, 'rental_product_id');
    }

    /**
     * Check if the product is available for the given dates, size, and quantity.
     *
     * @param Carbon $startDate
     * @param Carbon $endDate
     * @param string $size
     * @param int $quantity
     * @return bool
     */
    public function isAvailableForDates(Carbon $startDate, Carbon $endDate, string $size, int $quantity)
    {
        $totalAvailable = $this->sizes_quantity[$size] ?? 0;

        $rentals = $this->rentals()
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

        Log::info('isAvailableForDates check', [
            'product_id' => $this->id,
            'size' => $size,
            'quantity' => $quantity,
            'start_date' => $startDate->toDateString(),
            'end_date' => $endDate->toDateString(),
            'total_available' => $totalAvailable,
            'reserved_quantity' => $reservedQuantity,
            'remaining' => $remaining,
            'rentals_count' => $rentals->count(),
            'rental_ids' => $rentals->pluck('id')->toArray(),
            'rental_details' => $rentals->map(function ($rental) {
                return [
                    'id' => $rental->id,
                    'quantity' => $rental->quantity,
                    'start_date' => $rental->start_date->toDateString(),
                    'end_date' => $rental->end_date->toDateString(),
                    'status' => $rental->status
                ];
            })->toArray()
        ]);

        return $remaining >= $quantity;
    }
}
