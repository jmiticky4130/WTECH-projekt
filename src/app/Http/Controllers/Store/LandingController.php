<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Product;
use App\Support\CategoryMapping;
use Illuminate\Support\Facades\DB;

class LandingController extends Controller
{
    public function index()
    {
        $priceSubQuery = fn () => DB::table('product_variants')
            ->select('product_id', DB::raw('MIN(price) as variant_min_price'))
            ->groupBy('product_id');

        $allSizesSub = DB::raw("(SELECT STRING_AGG(DISTINCT pv_s.size, ', ') FROM product_variants pv_s WHERE pv_s.product_id = products.id) as sizes");

        $featuredProducts = Product::query()
            ->joinSub($priceSubQuery(), 'qv', 'products.id', '=', 'qv.product_id')
            ->join('brands', 'products.brand_id', '=', 'brands.id')
            ->leftJoin('product_images', function ($join) {
                $join->on('products.id', '=', 'product_images.product_id')
                     ->whereRaw('"product_images"."is_primary" = true');
            })
            ->whereRaw('"products"."is_featured" IS TRUE')
            ->whereNull('products.deleted_at')
            ->select(
                'products.id',
                'products.name',
                'products.slug',
                'brands.name as brand_name',
                'product_images.image_path',
                'qv.variant_min_price as min_price',
                $allSizesSub,
            )
            ->groupBy('products.id', 'products.name', 'products.slug', 'brands.name', 'product_images.image_path', 'qv.variant_min_price')
            ->limit(4)
            ->get();

        $newestProducts = Product::query()
            ->joinSub($priceSubQuery(), 'qv', 'products.id', '=', 'qv.product_id')
            ->join('brands', 'products.brand_id', '=', 'brands.id')
            ->leftJoin('product_images', function ($join) {
                $join->on('products.id', '=', 'product_images.product_id')
                     ->whereRaw('"product_images"."is_primary" = true');
            })
            ->whereNull('products.deleted_at')
            ->select(
                'products.id',
                'products.name',
                'products.slug',
                'brands.name as brand_name',
                'product_images.image_path',
                'qv.variant_min_price as min_price',
                $allSizesSub,
            )
            ->groupBy('products.id', 'products.name', 'products.slug', 'brands.name', 'product_images.image_path', 'qv.variant_min_price')
            ->orderBy('products.created_at', 'desc')
            ->limit(4)
            ->get();

        $brands = Brand::orderBy('name')->get();

        $clothingOrder = CategoryMapping::SIZE_ORDER;
        foreach ([$featuredProducts, $newestProducts] as $products) {
            $products->each(function ($p) use ($clothingOrder) {
                $sizes = array_filter(explode(', ', $p->sizes ?? ''));
                usort($sizes, function ($a, $b) use ($clothingOrder) {
                    $aNum = is_numeric($a);
                    $bNum = is_numeric($b);
                    if ($aNum && $bNum) return (int) $a <=> (int) $b;
                    if (! $aNum && ! $bNum) return ($clothingOrder[$a] ?? 999) <=> ($clothingOrder[$b] ?? 999);

                    return $aNum ? 1 : -1;
                });
                $p->sizes = implode(', ', $sizes);
            });
        }

        return view('pages.store.landing', compact('featuredProducts', 'newestProducts', 'brands'));
    }
}
