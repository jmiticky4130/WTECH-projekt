<?php

namespace App\Http\Requests\Admin;

use App\Models\PaymentMethod;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdatePaymentMethodRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $paymentMethod = $this->route('payment_method');

        return [
            'name' => ['required', 'string', 'max:50', Rule::unique('payment_methods', 'name')->ignore($paymentMethod?->id)],
            'type' => ['required', Rule::in(['karta', 'dobierka', 'bankový prevod'])],
            'fee' => ['nullable', 'numeric', 'min:0'],
            'requires_address' => ['sometimes', 'boolean'],
            'is_active' => ['sometimes', 'boolean'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $paymentMethod = $this->route('payment_method');

        $this->merge([
            'requires_address' => $this->boolean('requires_address', (bool) ($paymentMethod?->requires_address ?? false)),
            'is_active' => $this->boolean('is_active', (bool) ($paymentMethod?->is_active ?? true)),
        ]);
    }
}
