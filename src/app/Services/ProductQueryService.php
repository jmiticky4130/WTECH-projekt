<?php

namespace App\Services;

use App\Models\Product;
use App\Support\CategoryMapping;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class ProductQueryService
{
    /**
     * Build the base query with variant filtering and product-level filters applied,
     * but WITHOUT select/groupBy/orderBy (so the caller can clone for count first).
     *
     * @param  array{brand: array, color: array, material: array, size: array, min_price: mixed, max_price: mixed}  $filters
     */
    public function buildFilteredQuery(
        array $filters,
        ?int $categoryId = null,
        ?string $searchTerm = null,
    ): Builder {
        $variantSub = $this->buildVariantSubquery($filters);

        $query = Product::query()
            ->joinSub($variantSub, 'qv', 'products.id', '=', 'qv.product_id')
            ->join('brands', 'products.brand_id', '=', 'brands.id')
            ->join('materials', 'products.material_id', '=', 'materials.id');

        if ($categoryId !== null) {
            $query->where('products.category_id', $categoryId);
        }

        if (! empty($filters['brand'])) {
            $query->whereIn('brands.name', $filters['brand']);
        }

        if (! empty($filters['material'])) {
            $query->whereIn('materials.name', $filters['material']);
        }

        if ($searchTerm !== null && $searchTerm !== '') {
            $term = '%' . mb_strtolower($searchTerm) . '%';
            $query->where(function (Builder $w) use ($term) {
                $w->whereRaw('LOWER(products.name) LIKE ?', [$term])
                  ->orWhereRaw('LOWER(products.description) LIKE ?', [$term])
                  ->orWhereRaw('LOWER(brands.name) LIKE ?', [$term]);
            });
        }

        return $query;
    }

    /**
     * Count distinct products from a base query (before select/groupBy/orderBy).
     * Clones internally so the original builder is not modified.
     */
    public function getTotal(Builder $baseQuery): int
    {
        return (clone $baseQuery)->distinct()->count('products.id');
    }

    /**
     * Add presentation joins (subcategories, images, all-variants for STRING_AGG),
     * select columns, groupBy, and orderBy to the query in-place.
     */
    public function applyPresentation(Builder $query, string $sortBy): void
    {
        $query
            ->leftJoin('subcategories', 'products.subcategory_id', '=', 'subcategories.id')
            ->leftJoin('product_images', function ($join) {
                $join->on('products.id', '=', 'product_images.product_id')
                     ->whereRaw('"product_images"."is_primary" = true');
            })
            ->join('product_variants as product_variants_all', 'products.id', '=', 'product_variants_all.product_id')
            ->select(
                'products.id',
                'products.name',
                'products.slug',
                'products.is_featured',
                'brands.name as brand_name',
                'subcategories.name as subcategory_name',
                'product_images.image_path',
                'qv.variant_min_price as min_price',
                // STRING_AGG is PostgreSQL-specific — no Eloquent equivalent
                DB::raw("STRING_AGG(DISTINCT product_variants_all.size, ', ') as sizes"),
            )
            ->groupBy(
                'products.id',
                'products.name',
                'products.slug',
                'products.is_featured',
                'brands.name',
                'subcategories.name',
                'product_images.image_path',
                'qv.variant_min_price',
            );

        match ($sortBy) {
            'price_asc'  => $query->orderBy('qv.variant_min_price'),
            'price_desc' => $query->orderByDesc('qv.variant_min_price'),
            'new'        => $query->orderByDesc('products.created_at'),
            default      => $query->orderByDesc('products.is_featured')->orderByDesc('products.created_at'),
        };
    }

    /**
     * Sort the sizes string on each product result using CategoryMapping::SIZE_ORDER.
     */
    public function sortProductSizes(Collection $products): void
    {
        $sizeOrder = CategoryMapping::SIZE_ORDER;

        $products->each(function ($product) use ($sizeOrder) {
            $sizes = array_filter(explode(', ', $product->sizes ?? ''));
            usort($sizes, fn ($a, $b) => ($sizeOrder[$a] ?? 999) <=> ($sizeOrder[$b] ?? 999));
            $product->sizes = implode(', ', $sizes);
        });
    }

    private function buildVariantSubquery(array $filters): \Illuminate\Database\Query\Builder
    {
        $sub = DB::table('product_variants')
            ->join('colors', 'product_variants.color_id', '=', 'colors.id')
            ->select(
                'product_variants.product_id',
                DB::raw('MIN(product_variants.price) as variant_min_price'),
            );

        if (! empty($filters['color'])) {
            $sub->whereIn('colors.name', $filters['color']);
        }

        if (! empty($filters['size'])) {
            $sub->whereIn('product_variants.size', $filters['size']);
        }

        if ($filters['min_price'] !== null && $filters['min_price'] !== '') {
            $sub->where('product_variants.price', '>=', $filters['min_price']);
        }

        if ($filters['max_price'] !== null && $filters['max_price'] !== '') {
            $sub->where('product_variants.price', '<=', $filters['max_price']);
        }

        $sub->groupBy('product_variants.product_id');

        return $sub;
    }
}
