<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:50'],
            'description' => ['required', 'string'],
            'category' => ['required', 'string', 'in:Ženy,Muži,Deti'],
            'subcategory_id' => ['required', 'exists:subcategories,id'],
            'brand_id' => ['nullable', 'exists:brands,id'],
            'material_id' => ['nullable', 'exists:materials,id'],
            'is_featured' => ['boolean'],
            'images' => ['nullable', 'array'],
            'images.*' => ['image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'new_images' => ['nullable', 'array'],
            'new_images.*.type' => ['required_with:new_images', 'in:upload,library,external'],
            'new_images.*.value' => ['required_with:new_images', 'string', 'max:500'],
            'primary_new_index' => ['nullable', 'integer', 'min:0'],
            'variants' => ['required', 'array', 'min:1'],
            'variants.*.color_id' => ['required', 'exists:colors,id'],
            'variants.*.size' => ['required', 'string', 'max:10'],
            'variants.*.price' => ['required', 'numeric', 'min:0'],
            'variants.*.stock' => ['required', 'integer', 'min:0'],
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator): void {
            $newImages = $this->input('new_images', []);
            if (empty($newImages)) {
                $validator->errors()->add('images', 'Pridajte alebo vyberte aspon jednu fotografiu.');
            }
        });
    }

    public function messages(): array
    {
        $uploadLimit = ini_get('upload_max_filesize') ?: '2M';

        return [
            'images.*.uploaded' => "Obrazok sa nepodarilo nahrat. Serverovy limit je {$uploadLimit} na subor.",
            'images.*.max' => 'Obrazok moze mat maximalne 2 MB.',
            'images.*.mimes' => 'Povolene formaty obrazkov su: jpg, jpeg, png, webp.',
        ];
    }
}
