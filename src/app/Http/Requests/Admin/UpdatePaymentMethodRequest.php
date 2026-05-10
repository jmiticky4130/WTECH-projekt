<?php

namespace App\Http\Requests\Admin;

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
            'is_active' => ['sometimes', 'boolean'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $paymentMethod = $this->route('payment_method');

        $this->merge([
            'is_active' => $this->boolean('is_active', (bool) ($paymentMethod?->is_active ?? true)),
        ]);
    }
}
