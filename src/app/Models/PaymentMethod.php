<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class PaymentMethod extends Model
{
    public $timestamps = false;
    
    protected $fillable = ['name', 'fee', 'requires_address'];

    protected $casts = [
        'requires_address' => 'boolean', 
        'fee' => 'decimal:2'
    ];

    public function setRequiresAddressAttribute(mixed $value): void
    {
        $this->attributes['requires_address'] = filter_var($value, FILTER_VALIDATE_BOOLEAN) ? 'true' : 'false';
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query;
    }

    public function shippingMethods(): BelongsToMany
    {
        return $this->belongsToMany(ShippingMethod::class);
    }
}
