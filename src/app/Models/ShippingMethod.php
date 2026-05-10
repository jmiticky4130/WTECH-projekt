<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class ShippingMethod extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'name', 'type', 'price', 'delivery_days_from', 'delivery_days_to',
    ];

    protected $casts = ['price' => 'decimal:2'];

    public function paymentMethods(): BelongsToMany
    {
        return $this->belongsToMany(PaymentMethod::class);
    }
}
