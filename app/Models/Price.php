<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Price extends Model
{
    use HasFactory;

    protected $fillable = [
        'tour_id',
        'regular_price',
        'sale_price',
    ];

    public function tour()
    {
        return $this->belongsTo(Tour::class);
    }
}
