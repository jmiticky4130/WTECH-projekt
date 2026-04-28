<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Support\CategoryMapping;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    public function show(string $slug)
    {
        $product = Product::with(['brand', 'material', 'subcategory'])
            ->where('slug', $slug)
            ->firstOrFail();

        $images = $product->images()->orderBy('sort_order')->get();

        $isShoe = in_array($product->subcategory?->name, CategoryMapping::SHOE_SUBCATEGORY_NAMES);

        $variants = $product->variants()
            ->with('color')
            ->orderBy('color_id')
            ->when($isShoe,
                fn ($q) => $q->orderByRaw('size::integer'),
                fn ($q) => $q->orderByRaw("CASE size WHEN 'XS' THEN 0 WHEN 'S' THEN 1 WHEN 'M' THEN 2 WHEN 'L' THEN 3 WHEN 'XL' THEN 4 WHEN 'XXL' THEN 5 END"),
            )
            ->get()
            ->map(fn ($v) => (object) [
                'id' => $v->id,
                'color_id' => $v->color_id,
                'color_name' => $v->color->name,
                'hex_code' => $v->color->hex_code,
                'size' => $v->size,
                'price' => $v->price,
                'stock_quantity' => $v->stock_quantity,
            ]);

        $colors = $variants->unique('color_id')->values();
        $minPrice = $variants->min('price');

        $similarSub = DB::table('product_variants')
            ->select('product_id', DB::raw('MIN(price) as variant_min_price'))
            ->groupBy('product_id');

        $similarProducts = Product::query()
            ->joinSub($similarSub, 'qv', 'products.id', '=', 'qv.product_id')
            ->join('brands', 'products.brand_id', '=', 'brands.id')
            ->leftJoin('product_images', function ($join) {
                $join->on('products.id', '=', 'product_images.product_id')
                    ->whereRaw('"product_images"."is_primary" = true');
            })
            ->select(
                'products.id',
                'products.name',
                'products.slug',
                'brands.name as brand_name',
                'product_images.image_path',
                'qv.variant_min_price as min_price',
                DB::raw("(SELECT STRING_AGG(DISTINCT pv_s.size, ', ') FROM product_variants pv_s WHERE pv_s.product_id = products.id) as sizes"),
            )
            ->where('products.id', '!=', $product->id)
            ->where('products.subcategory_id', $product->subcategory_id)
            ->where('products.category', $product->category)
            ->groupBy(
                'products.id',
                'products.name',
                'products.slug',
                'brands.name',
                'product_images.image_path',
                'qv.variant_min_price',
            )
            ->limit(4)
            ->get();

        $clothingOrder = CategoryMapping::SIZE_ORDER;
        $similarProducts->each(function ($p) use ($clothingOrder) {
            $sizes = array_filter(explode(', ', $p->sizes ?? ''));
            usort($sizes, function ($a, $b) use ($clothingOrder) {
                $aNum = is_numeric($a);
                $bNum = is_numeric($b);
                if ($aNum && $bNum) {
                    return (int) $a <=> (int) $b;
                }
                if (! $aNum && ! $bNum) {
                    return ($clothingOrder[$a] ?? 999) <=> ($clothingOrder[$b] ?? 999);
                }

                return $aNum ? 1 : -1;
            });
            $p->sizes = implode(', ', $sizes);
        });

        $genderSlug = CategoryMapping::GENDER_SLUG_BY_NAME[$product->category] ?? null;

        $breadcrumb = [['label' => 'Domov', 'href' => url('/')]];
        if ($genderSlug) {
            $breadcrumb[] = [
                'label' => $product->category,
                'href' => url('/kategoria/'.$genderSlug),
            ];
        }
        if ($product->subcategory) {
            $subHref = $genderSlug
                ? url('/kategoria/'.$genderSlug.'/'.$product->subcategory->slug)
                : url('/kategoria/'.$product->subcategory->slug);

            $breadcrumb[] = [
                'label' => $product->subcategory->name,
                'href' => $subHref,
            ];
        }
        $breadcrumb[] = ['label' => $product->name];

        $product->brand_name = $product->brand->name ?? null;
        $product->material_name = $product->material->name ?? null;
        $product->category_name = $product->category;
        $product->subcategory_name = $product->subcategory?->name;

        return view('pages.store.product-detail', compact(
            'product', 'images', 'variants', 'colors', 'minPrice',
            'similarProducts', 'breadcrumb', 'isShoe',
        ));
    }
}
