<?php

namespace App\Services;

use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductService
{
    public function create(array $data, array $images, array $libraryImages = []): Product
    {
        $data['slug'] = $this->uniqueSlug($data['name']);
        $product = Product::create($data);

        $this->saveImages($product, $images, $libraryImages, []);

        if (! empty($data['variants'])) {
            $this->syncVariants($product, $data['variants']);
        }

        return $product;
    }

    public function update(Product $product, array $data, array $newImages, array $keepImageIds, array $libraryImages = []): void
    {
        if (isset($data['name']) && $data['name'] !== $product->name) {
            $data['slug'] = $this->uniqueSlug($data['name'], $product->id);
        }

        $product->update($data);

        $product->images()->whereNotIn('id', $keepImageIds)->each(function (ProductImage $img) {
            if ($this->deletableFromPublicDisk($img->image_path)) {
                Storage::disk('public')->delete($img->image_path);
            }
            $img->delete();
        });

        $this->saveImages($product, $newImages, $libraryImages, $keepImageIds);

        $this->syncVariants($product, $data['variants'] ?? []);
    }

    public function delete(Product $product): void
    {
        $product->delete();
    }

    /** @param UploadedFile[] $images */
    private function saveImages(Product $product, array $images, array $libraryImages, array $keepImageIds): void
    {
        $keptImages = $product->images()->whereIn('id', $keepImageIds)->get();
        $hasPrimary = $keptImages->contains(fn (ProductImage $image) => (bool) $image->is_primary);
        $sortOrderOffset = $keptImages->count();
        $existingPathSet = $product->images()
            ->pluck('image_path')
            ->map(fn (?string $path) => $this->normalizeImagePath($path))
            ->filter()
            ->flip();

        $paths = [];

        foreach ($images as $file) {
            $paths[] = $file->store('products', 'public');
        }

        foreach (array_values(array_unique($libraryImages)) as $libraryPath) {
            $paths[] = ltrim(str_replace('\\', '/', (string) $libraryPath), '/');
        }

        foreach ($paths as $i => $path) {
            $normalizedPath = $this->normalizeImagePath($path);
            if ($normalizedPath === '' || $existingPathSet->has($normalizedPath)) {
                continue;
            }

            $isPrimary = ! $hasPrimary && $i === 0;
            ProductImage::create([
                'product_id' => $product->id,
                'image_path' => $normalizedPath,
                'is_primary' => $isPrimary ? 'true' : 'false',
                'sort_order' => $sortOrderOffset + $i,
            ]);

            $existingPathSet->put($normalizedPath, true);
        }
    }

    private function deletableFromPublicDisk(?string $path): bool
    {
        if (! $path) {
            return false;
        }

        $normalized = $this->normalizeImagePath($path);

        return ! str_starts_with($normalized, 'images/')
            && ! str_starts_with($normalized, 'storage/');
    }

    private function normalizeImagePath(?string $path): string
    {
        if (! $path) {
            return '';
        }

        return ltrim(str_replace('\\', '/', $path), '/');
    }

    private function syncVariants(Product $product, array $variants): void
    {
        $existingVariants = $product->variants()->get()->keyBy('id');
        $submittedVariantIds = collect();

        foreach ($variants as $v) {
            $payload = [
                'color_id' => $v['color_id'],
                'size' => $v['size'],
                'price' => $v['price'],
                'stock_quantity' => $v['stock'],
            ];

            $variantId = isset($v['id']) && is_numeric($v['id']) ? (int) $v['id'] : null;

            if ($variantId !== null && $existingVariants->has($variantId)) {
                $existingVariants->get($variantId)->update($payload);
                $submittedVariantIds->push($variantId);

                continue;
            }

            $createdVariant = $product->variants()->create($payload);
            $submittedVariantIds->push($createdVariant->id);
        }

        $variantIdsToRemove = $existingVariants->keys()->diff($submittedVariantIds)->values();

        if ($variantIdsToRemove->isEmpty()) {
            return;
        }

        DB::table('cart_items')->whereIn('variant_id', $variantIdsToRemove)->delete();

        $orderReferencedVariantIds = DB::table('order_items')
            ->whereIn('variant_id', $variantIdsToRemove)
            ->pluck('variant_id');

        $deletableVariantIds = $variantIdsToRemove->diff($orderReferencedVariantIds)->values();

        if ($deletableVariantIds->isNotEmpty()) {
            $product->variants()->whereIn('id', $deletableVariantIds)->delete();
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
