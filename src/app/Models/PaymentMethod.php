<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class PaymentMethod extends Model
{
    protected $fillable = ['name', 'type', 'fee', 'requires_address', 'is_active', 'sort_order'];

    protected $casts = ['requires_address' => 'boolean', 'is_active' => 'boolean', 'fee' => 'decimal:2'];

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true)->orderBy('sort_order');
    }
}
