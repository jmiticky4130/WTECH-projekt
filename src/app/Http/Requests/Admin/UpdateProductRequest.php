<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProductRequest extends FormRequest
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
            'category_id' => ['required', 'exists:categories,id'],
            'subcategory_id' => ['nullable', 'exists:subcategories,id'],
            'brand_id' => ['nullable', 'exists:brands,id'],
            'material_id' => ['nullable', 'exists:materials,id'],
            'is_featured' => ['boolean'],
            'images' => ['nullable', 'array'],
            'images.*' => ['image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'new_images' => ['nullable', 'array'],
            'new_images.*.type' => ['required_with:new_images', 'in:upload,library,external'],
            'new_images.*.value' => ['required_with:new_images', 'string', 'max:500'],
            'primary_image_id' => ['nullable', 'integer'],
            'primary_new_index' => ['nullable', 'integer', 'min:0'],
            'image_order' => ['nullable', 'array'],
            'image_order.*' => ['integer'],
            'keep_image_ids' => ['nullable', 'array'],
            'keep_image_ids.*' => ['integer'],
            'variants' => ['nullable', 'array'],
            'variants.*.id' => ['nullable', 'integer'],
            'variants.*.color_id' => ['required_with:variants', 'exists:colors,id'],
            'variants.*.size' => ['required_with:variants', 'string', 'max:10'],
            'variants.*.price' => ['required_with:variants', 'numeric', 'min:0'],
            'variants.*.stock' => ['required_with:variants', 'integer', 'min:0'],
        ];
    }

    public function messages(): array
    {
        $uploadLimit = ini_get('upload_max_filesize') ?: '2M';

        return [
            'images.*.uploaded' => "Obrazok sa nepodarilo nahrat. Serverovy limit je {$uploadLimit} na subor.",
            'images.*.max' => 'Obrazok moze mat maximalne 2 MB.',
            'images.*.mimes' => 'Povolene formaty obrazkov su: jpg, jpeg, png, webp.',
            'library_images.*.string' => 'Vybrany obrazok ma neplatny format.',
        ];
    }
}
