<?php

namespace App\Services;

use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductService
{
    public function create(array $data, array $images): Product
    {
        $data['slug'] = $this->uniqueSlug($data['name']);
        $product = Product::create($data);

        $this->saveImages($product, $images, []);

        if (! empty($data['variants'])) {
            $this->syncVariants($product, $data['variants']);
        }

        return $product;
    }

    public function update(Product $product, array $data, array $newImages, array $keepImageIds): void
    {
        if (isset($data['name']) && $data['name'] !== $product->name) {
            $data['slug'] = $this->uniqueSlug($data['name'], $product->id);
        }

        $product->update($data);

        $product->images()->whereNotIn('id', $keepImageIds)->each(function (ProductImage $img) {
            Storage::disk('public')->delete($img->image_path);
            $img->delete();
        });

        $this->saveImages($product, $newImages, $keepImageIds);

        if (isset($data['variants'])) {
            $this->syncVariants($product, $data['variants']);
        }
    }

    public function delete(Product $product): void
    {
        $product->delete();
    }

    /** @param UploadedFile[] $images */
    private function saveImages(Product $product, array $images, array $keepImageIds): void
    {
        $hasPrimary = $product->images()->whereRaw('is_primary IS TRUE')->whereIn('id', $keepImageIds)->exists()
            || $product->images()->whereRaw('is_primary IS TRUE')->whereNotIn('id', $keepImageIds)->doesntExist() === false;

        $isFirst = ! $product->images()->whereIn('id', $keepImageIds)->exists() && empty($keepImageIds);

        foreach ($images as $i => $file) {
            $path = $file->store('products', 'public');
            $isPrimary = $isFirst && $i === 0;
            ProductImage::create([
                'product_id' => $product->id,
                'image_path' => $path,
                'is_primary' => $isPrimary ? 'true' : 'false',
                'sort_order' => count($keepImageIds) + $i,
            ]);
        }
    }

    private function syncVariants(Product $product, array $variants): void
    {
        $product->variants()->delete();
        foreach ($variants as $v) {
            $product->variants()->create([
                'color_id' => $v['color_id'],
                'size' => $v['size'],
                'price' => $v['price'],
                'stock_quantity' => $v['stock'],
            ]);
        }
    }

    private function uniqueSlug(string $name, ?int $ignoreId = null): string
    {
        $base = Str::slug($name);
        $slug = $base;
        $i = 1;

        while (
            Product::where('slug', $slug)
                ->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))
                ->withTrashed()
                ->exists()
        ) {
            $slug = $base.'-'.$i++;
        }

        return $slug;
    }
}
