<?php

namespace App\Http\Requests\Admin;

use App\Enums\ShippingType;
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
            'type' => ['required', Rule::enum(ShippingType::class)],
            'price' => ['required', 'numeric', 'min:0'],
            'delivery_days_from' => ['required', 'integer', 'min:1'],
            'delivery_days_to' => ['required', 'integer', 'min:1', 'gte:delivery_days_from'],
            'is_active' => ['sometimes', 'boolean'],
            'payment_methods' => ['nullable', 'array'],
            'payment_methods.*' => ['exists:payment_methods,id'],
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
