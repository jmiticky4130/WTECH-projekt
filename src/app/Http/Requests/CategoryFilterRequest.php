<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CategoryFilterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'brand' => ['sometimes', 'array'],
            'brand.*' => ['string'],
            'color' => ['sometimes', 'array'],
            'color.*' => ['string'],
            'material' => ['sometimes', 'array'],
            'material.*' => ['string'],
            'size' => ['sometimes', 'array'],
            'size.*' => ['string'],
            'min_price' => ['sometimes', 'nullable', 'numeric', 'min:0'],
            'max_price' => ['sometimes', 'nullable', 'numeric', 'min:0'],
            'sort' => ['sometimes', 'nullable', 'in:featured,price_asc,price_desc,new'],
            'page' => ['sometimes', 'integer', 'min:1'],
        ];
    }
}
