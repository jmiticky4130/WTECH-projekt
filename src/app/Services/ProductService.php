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
    public function create(array $data, array $uploadedFiles, array $newImagesMeta = []): Product
    {
        $data['slug'] = $this->uniqueSlug($data['name']);
        $primaryNewIndex = isset($data['primary_new_index']) ? (int) $data['primary_new_index'] : null;
        $variants = $data['variants'] ?? [];
        unset($data['primary_new_index'], $data['primary_image_id'], $data['image_order'], $data['variants']);

        $product = Product::create($data);

        $this->saveImages($product, $uploadedFiles, $newImagesMeta, [], $primaryNewIndex, 0, false);

        if (! empty($variants)) {
            $this->syncVariants($product, $variants);
        }

        return $product;
    }

    public function update(Product $product, array $data, array $uploadedFiles, array $keepImageIds, array $newImagesMeta = []): void
    {
        if (isset($data['name']) && $data['name'] !== $product->name) {
            $data['slug'] = $this->uniqueSlug($data['name'], $product->id);
        }

        $imageOrder = array_values(array_filter(array_map('intval', $data['image_order'] ?? [])));
        $primaryImageId = isset($data['primary_image_id']) ? (int) $data['primary_image_id'] : null;
        $primaryNewIndex = isset($data['primary_new_index']) ? (int) $data['primary_new_index'] : null;
        unset($data['primary_image_id'], $data['primary_new_index'], $data['image_order']);

        $product->update($data);

        $product->images()->whereNotIn('id', $keepImageIds)->each(function (ProductImage $img) {
            if ($this->deletableFromPublicDisk($img->image_path)) {
                Storage::disk('public')->delete($img->image_path);
            }
            $img->delete();
        });

        foreach ($imageOrder as $order => $id) {
            $product->images()->where('id', $id)->update(['sort_order' => $order]);
        }

        if ($primaryImageId !== null) {
            $product->images()->update(['is_primary' => 'false']);
            $product->images()->where('id', $primaryImageId)->update(['is_primary' => 'true']);
        }

        $keptCount = count($imageOrder) ?: count($keepImageIds);
        $hasPrimaryAfterKept = $primaryImageId !== null
            || $product->images()->where('is_primary', 'true')->exists();

        $this->saveImages($product, $uploadedFiles, $newImagesMeta, $keepImageIds, $primaryNewIndex, $keptCount, $hasPrimaryAfterKept);

        $this->syncVariants($product, $data['variants'] ?? []);
    }

    public function delete(Product $product): void
    {
        $product->delete();
    }

    private function saveImages(
        Product $product,
        array $uploadedFiles,
        array $newImagesMeta,
        array $keepImageIds,
        ?int $primaryNewIndex,
        int $sortOrderOffset,
        bool $hasPrimary,
    ): void {
        $existingPathSet = $product->images()
            ->pluck('image_path')
            ->map(fn (?string $path) => $this->normalizeImagePath($path))
            ->filter()
            ->flip();

        // Resolve each meta entry to a path in tray order
        $paths = [];
        foreach ($newImagesMeta as $entry) {
            $type  = $entry['type'] ?? '';
            $value = $entry['value'] ?? '';

            if ($type === 'upload') {
                $fileIndex = (int) $value;
                if (isset($uploadedFiles[$fileIndex])) {
                    $paths[] = $uploadedFiles[$fileIndex]->store('products', 'public');
                }
            } elseif ($type === 'library') {
                $paths[] = ltrim(str_replace('\\', '/', (string) $value), '/');
            } elseif ($type === 'external') {
                if (str_starts_with((string) $value, 'http://') || str_starts_with((string) $value, 'https://')) {
                    $paths[] = (string) $value;
                }
            }
        }

        // deduplicate while preserving tray order, skip already-stored paths
        $newPaths = [];
        foreach ($paths as $path) {
            $normalized = $this->normalizeImagePath($path);
            if ($normalized !== '' && ! $existingPathSet->has($normalized)) {
                $newPaths[] = $normalized;
                $existingPathSet->put($normalized, true);
            }
        }

        foreach ($newPaths as $i => $normalizedPath) {
            $isPrimary = $primaryNewIndex !== null
                ? ($i === $primaryNewIndex)
                : (! $hasPrimary && $i === 0);

            if ($isPrimary) {
                $hasPrimary = true;
            }

            ProductImage::create([
                'product_id' => $product->id,
                'image_path' => $normalizedPath,
                'is_primary' => $isPrimary ? 'true' : 'false',
                'sort_order' => $sortOrderOffset + $i,
            ]);
        }
    }

    private function deletableFromPublicDisk(?string $path): bool
    {
        if (! $path) {
            return false;
        }

        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
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
