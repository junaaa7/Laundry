<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Price extends Model
{
    use HasFactory;

    protected $fillable = [
        'category',
        'type',
        'price_per_kg',
        'minimum_price',
        'delivery_days',
        'additional_fee',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean'
    ];
}