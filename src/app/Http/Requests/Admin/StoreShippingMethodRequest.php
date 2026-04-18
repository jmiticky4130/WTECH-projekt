<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreShippingMethodRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:50', 'unique:shipping_methods,name'],
            'type' => ['required', Rule::in(['address', 'pickup_point', 'personal_pickup'])],
            'price' => ['required', 'numeric', 'min:0'],
            'delivery_days_from' => ['required', 'integer', 'min:1'],
            'delivery_days_to' => ['required', 'integer', 'min:1', 'gte:delivery_days_from'],
            'description' => ['nullable', 'string', 'max:255'],
        ];
    }
}
