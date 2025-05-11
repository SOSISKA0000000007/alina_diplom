<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tour extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'image',
    ];

    public function dates()
    {
        return $this->hasMany(TourDate::class);
    }

    public function prices()
    {
        return $this->hasMany(Price::class);
    }
}
