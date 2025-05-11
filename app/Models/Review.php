<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'rental_id',
        'user_id',
        'rental_product_id',
        'pros',
        'cons',
        'comment',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function product()
    {
        return $this->belongsTo(RentalProduct::class, 'rental_product_id');
    }

    public function rental()
    {
        return $this->belongsTo(Rental::class);
    }
}
