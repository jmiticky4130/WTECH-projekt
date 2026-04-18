<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class ShippingMethod extends Model
{
    protected $fillable = [
        'name', 'type', 'price', 'delivery_days_from', 'delivery_days_to',
        'description', 'is_active', 'sort_order',
    ];

    protected $casts = ['is_active' => 'boolean', 'price' => 'decimal:2'];

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true)->orderBy('sort_order');
    }
}
