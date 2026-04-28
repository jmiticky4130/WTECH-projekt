<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use App\Http\Requests\SearchFilterRequest;
use App\Models\Product;
use App\Services\FilterDataService;
use App\Services\ProductQueryService;
use App\Support\CategoryMapping;
use App\Support\ProductImageUrl;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SearchController extends Controller
{
    public function __construct(
        private ProductQueryService $productQuery,
        private FilterDataService $filterData,
    ) {}

    public function index(SearchFilterRequest $request)
    {
        $q = trim((string) $request->input('q', ''));
        $perPage = 12;
        $page = max(1, (int) $request->input('page', 1));
        $sortBy = $request->input('sort', 'featured');

        $brands = $this->filterData->getBrands();
        $colors = $this->filterData->getColors();
        $materials = $this->filterData->getMaterials();
        $clothingSizes = CategoryMapping::CLOTHING_SIZES;
        $shoeSizes = CategoryMapping::SHOE_EU_SIZES;
        $globalMinPrice = $this->filterData->getGlobalMinPrice();
        $globalMaxPrice = $this->filterData->getGlobalMaxPrice();

        $filterBrands = array_filter((array) $request->input('brand', []));
        $filterColors = array_filter((array) $request->input('color', []));
        $filterMaterials = array_filter((array) $request->input('material', []));
        $filterSizes = array_filter((array) $request->input('size', []));
        $minPrice = $request->input('min_price');
        $maxPrice = $request->input('max_price');

        $filters = [
            'brand' => $filterBrands,
            'color' => $filterColors,
            'material' => $filterMaterials,
            'size' => $filterSizes,
            'min_price' => $minPrice,
            'max_price' => $maxPrice,
        ];

        $baseQuery = $this->productQuery->buildFilteredQuery($filters, null, null, $q !== '' ? $q : null);
        $total = $this->productQuery->getTotal($baseQuery);
        $totalPages = max(1, (int) ceil($total / $perPage));

        $this->productQuery->applyPresentation($baseQuery, $sortBy);
        $products = $baseQuery->offset(($page - 1) * $perPage)->limit($perPage)->get();
        $this->productQuery->sortProductSizes($products);

        return view('pages.store.search', compact(
            'q', 'brands', 'colors', 'materials', 'clothingSizes', 'shoeSizes',
            'filterBrands', 'filterColors', 'filterMaterials', 'filterSizes',
            'globalMinPrice', 'globalMaxPrice',
            'products', 'total', 'page', 'totalPages', 'perPage', 'sortBy',
            'minPrice', 'maxPrice',
        ));
    }

    public function suggestions(Request $request): JsonResponse
    {
        $q = trim((string) $request->input('q', ''));

        if (mb_strlen($q) < 2) {
            return response()->json([]);
        }

        $term = '%'.mb_strtolower($q).'%';

        $products = Product::query()
            ->join('brands', 'products.brand_id', '=', 'brands.id')
            ->leftJoin('product_images', function ($join) {
                $join->on('products.id', '=', 'product_images.product_id')
                    ->whereRaw('"product_images"."is_primary" = true');
            })
            ->join('product_variants', 'products.id', '=', 'product_variants.product_id')
            ->where(function (Builder $w) use ($term) {
                $w->whereRaw('LOWER(products.name) LIKE ?', [$term])
                    ->orWhereRaw('LOWER(brands.name) LIKE ?', [$term])
                    ->orWhereRaw('LOWER(products.description) LIKE ?', [$term]);
            })
            ->select(
                'products.name',
                'products.slug',
                'product_images.image_path',
                'brands.name as brand_name',
                DB::raw('MIN(product_variants.price) as min_price'),
            )
            ->groupBy('products.id', 'products.name', 'products.slug', 'product_images.image_path', 'brands.name')
            ->orderByDesc('products.is_featured')
            ->limit(5)
            ->get();

        return response()->json($products->map(fn ($p) => [
            'name' => $p->name,
            'slug' => $p->slug,
            'brand_name' => $p->brand_name,
            'image_url' => ProductImageUrl::resolve($p->image_path),
            'price_formatted' => number_format((float) $p->min_price, 2, ',', "\u{00A0}")."\u{00A0}€",
        ]));
    }
}
