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
        $product = Product::with(['brand', 'material', 'category', 'subcategory'])
            ->where('slug', $slug)
            ->firstOrFail();

        $images = $product->images()->orderBy('sort_order')->get();

        $variants = $product->variants()
            ->with('color')
            ->orderBy('color_id')
            ->orderByRaw("CASE size WHEN 'XS' THEN 0 WHEN 'S' THEN 1 WHEN 'M' THEN 2 WHEN 'L' THEN 3 WHEN 'XL' THEN 4 WHEN 'XXL' THEN 5 END")
            ->get()
            ->map(fn ($v) => (object) [
                'id'             => $v->id,
                'color_id'       => $v->color_id,
                'color_name'     => $v->color->name,
                'hex_code'       => $v->color->hex_code,
                'size'           => $v->size,
                'price'          => $v->price,
                'stock_quantity' => $v->stock_quantity,
            ]);

        $colors   = $variants->unique('color_id')->values();
        $minPrice = $variants->min('price');

        $similarSub = DB::table('product_variants')
            ->select('product_id', DB::raw('MIN(price) as variant_min_price'))
            ->groupBy('product_id');

        $similarQuery = Product::query()
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
                DB::raw("STRING_AGG(DISTINCT pv_all.size, ', ') as sizes"),
            )
            ->join('product_variants as pv_all', 'products.id', '=', 'pv_all.product_id')
            ->where('products.id', '!=', $product->id)
            ->groupBy(
                'products.id',
                'products.name',
                'products.slug',
                'brands.name',
                'product_images.image_path',
                'qv.variant_min_price',
            )
            ->limit(4);

        if ($product->subcategory_id) {
            $similarQuery->where('products.subcategory_id', $product->subcategory_id);
        } else {
            $similarQuery->where('products.category_id', $product->category_id);
        }

        $similarProducts = $similarQuery->get();

        $sizeOrder = CategoryMapping::SIZE_ORDER;
        $similarProducts->each(function ($p) use ($sizeOrder) {
            $sizes = array_filter(explode(', ', $p->sizes ?? ''));
            usort($sizes, fn ($a, $b) => ($sizeOrder[$a] ?? 999) <=> ($sizeOrder[$b] ?? 999));
            $p->sizes = implode(', ', $sizes);
        });

        $breadcrumb = [['label' => 'Domov', 'href' => url('/')]];
        if ($product->category && isset(CategoryMapping::CAT_SLUG_BY_NAME[$product->category->name])) {
            $breadcrumb[] = [
                'label' => $product->category->name,
                'href'  => url('/kategoria/' . CategoryMapping::CAT_SLUG_BY_NAME[$product->category->name]),
            ];
        }
        if ($product->subcategory) {
            $breadcrumb[] = ['label' => $product->subcategory->name];
        }
        $breadcrumb[] = ['label' => $product->name];

        $product->brand_name       = $product->brand->name ?? null;
        $product->material_name    = $product->material->name ?? null;
        $product->category_name    = $product->category->name ?? null;
        $product->subcategory_name = $product->subcategory?->name;

        return view('pages.store.product-detail', compact(
            'product', 'images', 'variants', 'colors', 'minPrice',
            'similarProducts', 'breadcrumb',
        ));
    }
}
