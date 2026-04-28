<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateShippingMethodRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $shippingMethod = $this->route('shipping_method');

        return [
            'name' => ['required', 'string', 'max:50', Rule::unique('shipping_methods', 'name')->ignore($shippingMethod?->id)],
            'type' => ['required', Rule::in(['address', 'pickup_point', 'personal_pickup'])],
            'price' => ['required', 'numeric', 'min:0'],
            'delivery_days_from' => ['required', 'integer', 'min:1'],
            'delivery_days_to' => ['required', 'integer', 'min:1', 'gte:delivery_days_from'],
            'description' => ['nullable', 'string', 'max:255'],
            'is_active' => ['sometimes', 'boolean'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $shippingMethod = $this->route('shipping_method');

        $this->merge([
            'is_active' => $this->boolean('is_active', (bool) ($shippingMethod?->is_active ?? true)),
        ]);
    }
}
