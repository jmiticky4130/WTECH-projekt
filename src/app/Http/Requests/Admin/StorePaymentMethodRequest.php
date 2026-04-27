<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StorePaymentMethodRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:50', 'unique:payment_methods,name'],
            'type' => ['required', Rule::in(['karta', 'dobierka', 'bankový prevod'])],
            'fee' => ['nullable', 'numeric', 'min:0'],
            'requires_address' => ['sometimes', 'boolean'],
            'is_active' => ['sometimes', 'boolean'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'requires_address' => $this->boolean('requires_address'),
            'is_active' => $this->boolean('is_active', true),
        ]);
    }
}
