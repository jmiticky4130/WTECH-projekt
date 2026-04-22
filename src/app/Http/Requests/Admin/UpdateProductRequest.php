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
            'library_images' => ['nullable', 'array'],
            'library_images.*' => [
                'string',
                function (string $attribute, mixed $value, \Closure $fail): void {
                    $normalized = ltrim(str_replace('\\', '/', (string) $value), '/');

                    if (! str_starts_with($normalized, 'images/products/')) {
                        $fail('Vybrany obrazok nie je povoleny.');

                        return;
                    }

                    if (! is_file(public_path($normalized))) {
                        $fail('Vybrany obrazok neexistuje.');
                    }
                },
            ],
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
