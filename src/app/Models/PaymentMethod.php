<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class PaymentMethod extends Model
{
    public $timestamps = false;

    protected $fillable = ['name', 'fee'];

    protected $casts = [
        'fee' => 'decimal:2',
    ];

    public function scopeActive(Builder $query): Builder
    {
        return $query;
    }

    public function shippingMethods(): BelongsToMany
    {
        return $this->belongsToMany(ShippingMethod::class);
    }
}
