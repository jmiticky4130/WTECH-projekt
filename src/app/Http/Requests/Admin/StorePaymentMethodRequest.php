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
            'type' => ['required', Rule::in(['card', 'cod', 'bank_transfer', 'google_pay'])],
            'fee' => ['nullable', 'numeric', 'min:0'],
        ];
    }
}
