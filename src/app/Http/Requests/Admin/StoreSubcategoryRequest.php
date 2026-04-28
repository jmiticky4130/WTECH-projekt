<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class StoreSubcategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $subcategoryId = $this->route('subcategory')?->id;

        return [
            'name' => [
                'required',
                'string',
                'max:30',
                Rule::unique('subcategories', 'name')->ignore($subcategoryId),
            ],
            'sort_order' => ['nullable', 'integer', 'min:0'],
        ];
    }

    protected function passedValidation(): void
    {
        $this->merge([
            'slug' => Str::slug($this->input('name')),
        ]);
    }

    public function validated($key = null, $default = null)
    {
        $data = parent::validated();
        $data['slug'] = Str::slug($data['name']);

        if ($key !== null) {
            return data_get($data, $key, $default);
        }

        return $data;
    }
}
