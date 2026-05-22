<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TargetLaundry extends Model
{
    use HasFactory;

    protected $fillable = [
        'month',
        'year',
        'target_amount',
        'achieved_amount'
    ];

    public function getProgressAttribute()
    {
        if ($this->target_amount <= 0) return 0;
        return ($this->achieved_amount / $this->target_amount) * 100;
    }

    public function getRemainingAttribute()
    {
        return max(0, $this->target_amount - $this->achieved_amount);
    }
}