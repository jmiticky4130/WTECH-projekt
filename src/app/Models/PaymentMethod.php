<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class PaymentMethod extends Model
{
    protected $fillable = ['name', 'type', 'fee', 'requires_address', 'is_active', 'sort_order'];

    protected $casts = ['requires_address' => 'boolean', 'is_active' => 'boolean', 'fee' => 'decimal:2'];

    public function setRequiresAddressAttribute(mixed $value): void
    {
        $this->attributes['requires_address'] = filter_var($value, FILTER_VALIDATE_BOOLEAN) ? 'true' : 'false';
    }

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
