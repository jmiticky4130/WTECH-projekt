<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ShippingMethod extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'name', 'type', 'price', 'delivery_days_from', 'delivery_days_to',
        'description', 'is_active', 'sort_order',
    ];

    protected $casts = ['is_active' => 'boolean', 'price' => 'decimal:2'];

    public function setIsActiveAttribute(mixed $value): void
    {
        $this->attributes['is_active'] = filter_var($value, FILTER_VALIDATE_BOOLEAN) ? 'true' : 'false';
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query
            ->whereRaw($this->qualifyColumn('is_active').' is true')
            ->orderBy('sort_order');
    }
}
