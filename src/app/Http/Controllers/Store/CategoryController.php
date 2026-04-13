<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryFilterRequest;
use App\Models\Category;
use App\Services\FilterDataService;
use App\Services\ProductQueryService;
use App\Support\CategoryMapping;

class CategoryController extends Controller
{
    public function __construct(
        private ProductQueryService $productQuery,
        private FilterDataService $filterData,
    ) {}

    public function index(CategoryFilterRequest $request, string $p1, ?string $p2 = null)
    {
        if (in_array($p1, CategoryMapping::GENDERS)) {
            $gender      = $p1;
            $subcategory = $p2;
            if ($subcategory && ! in_array($subcategory, array_merge(CategoryMapping::CAT_SLUGS, CategoryMapping::SUB_SLUGS))) {
                abort(404);
            }
        } elseif ((in_array($p1, CategoryMapping::CAT_SLUGS) || in_array($p1, CategoryMapping::SUB_SLUGS)) && $p2 === null) {
            $gender      = null;
            $subcategory = $p1;
        } else {
            abort(404);
        }

        $categoryName    = CategoryMapping::CAT_NAMES[$subcategory] ?? CategoryMapping::CAT_NAMES[$p1] ?? null;
        $category        = $categoryName ? Category::where('name', $categoryName)->first() : null;
        $dbSubcategories = $category ? $category->subcategories : collect();
        $allCategories   = Category::with('subcategories')->get()->map(fn ($cat) => [
            'id'   => $cat->id,
            'name' => $cat->name,
            'subs' => $cat->subcategories,
        ]);

        $brands         = $this->filterData->getBrands();
        $colors         = $this->filterData->getColors();
        $materials      = $this->filterData->getMaterials();
        $allSizes       = CategoryMapping::ALL_SIZES;
        $globalMinPrice = $this->filterData->getGlobalMinPrice();
        $globalMaxPrice = $this->filterData->getGlobalMaxPrice();

        $filterBrands    = array_filter((array) $request->input('brand', []));
        $filterColors    = array_filter((array) $request->input('color', []));
        $filterMaterials = array_filter((array) $request->input('material', []));
        $filterSizes     = array_filter((array) $request->input('size', []));
        $sortBy          = $request->input('sort', 'featured');
        $perPage         = 12;
        $page            = max(1, (int) $request->input('page', 1));

        $filters = [
            'brand'     => $filterBrands,
            'color'     => $filterColors,
            'material'  => $filterMaterials,
            'size'      => $filterSizes,
            'min_price' => $request->input('min_price'),
            'max_price' => $request->input('max_price'),
        ];

        $baseQuery  = $this->productQuery->buildFilteredQuery($filters, $category?->id);
        $total      = $this->productQuery->getTotal($baseQuery);
        $totalPages = max(1, (int) ceil($total / $perPage));

        $this->productQuery->applyPresentation($baseQuery, $sortBy);
        $products = $baseQuery->offset(($page - 1) * $perPage)->limit($perPage)->get();
        $this->productQuery->sortProductSizes($products);

        return view('pages.store.category', compact(
            'gender', 'subcategory', 'category',
            'allCategories', 'dbSubcategories',
            'brands', 'colors', 'materials', 'allSizes',
            'filterBrands', 'filterColors', 'filterMaterials', 'filterSizes',
            'globalMinPrice', 'globalMaxPrice',
            'products', 'total', 'page', 'totalPages', 'perPage', 'sortBy',
        ));
    }
}
