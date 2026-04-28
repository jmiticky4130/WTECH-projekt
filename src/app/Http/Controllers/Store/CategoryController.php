<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryFilterRequest;
use App\Models\Subcategory;
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
            $genderSlug = $p1;
            $subSlug = $p2;
        } elseif ($p2 === null) {
            $genderSlug = null;
            $subSlug = $p1;
        } else {
            abort(404);
        }

        $genderName = $genderSlug ? CategoryMapping::GENDER_NAMES[$genderSlug] : null;

        $subcategoryObj = null;
        if ($subSlug) {
            $subcategoryObj = Subcategory::where('slug', $subSlug)->first();
            if (! $subcategoryObj) {
                abort(404);
            }
        }

        $subcategoryIds = $subcategoryObj ? [$subcategoryObj->id] : null;
        $subcategoryName = $subcategoryObj?->name;
        $category = $subcategoryObj;
        $gender = $genderSlug;
        $subcategory = $subSlug;

        $brands = $this->filterData->getBrands();
        $colors = $this->filterData->getColors();
        $materials = $this->filterData->getMaterials();
        $clothingSizes = $subcategoryName === 'Topánky' ? [] : CategoryMapping::CLOTHING_SIZES;
        $shoeSizes = ($subcategoryName === 'Oblečenie' || $subcategoryName === 'Doplnky') ? [] : CategoryMapping::SHOE_EU_SIZES;
        $globalMinPrice = $this->filterData->getGlobalMinPrice();
        $globalMaxPrice = $this->filterData->getGlobalMaxPrice();

        $filterBrands = array_filter((array) $request->input('brand', []));
        $filterColors = array_filter((array) $request->input('color', []));
        $filterMaterials = array_filter((array) $request->input('material', []));
        $filterSizes = array_filter((array) $request->input('size', []));
        $sortBy = $request->input('sort', 'featured');
        $perPage = 12;
        $page = max(1, (int) $request->input('page', 1));

        $filters = [
            'brand' => $filterBrands,
            'color' => $filterColors,
            'material' => $filterMaterials,
            'size' => $filterSizes,
            'min_price' => $request->input('min_price'),
            'max_price' => $request->input('max_price'),
        ];

        $baseQuery = $this->productQuery->buildFilteredQuery(
            $filters,
            $genderName,
            $subcategoryIds,
        );
        $total = $this->productQuery->getTotal($baseQuery);
        $totalPages = max(1, (int) ceil($total / $perPage));

        $this->productQuery->applyPresentation($baseQuery, $sortBy);
        $products = $baseQuery->offset(($page - 1) * $perPage)->limit($perPage)->get();
        $this->productQuery->sortProductSizes($products);

        return view('pages.store.category', compact(
            'gender', 'subcategory', 'category',
            'brands', 'colors', 'materials', 'clothingSizes', 'shoeSizes',
            'filterBrands', 'filterColors', 'filterMaterials', 'filterSizes',
            'globalMinPrice', 'globalMaxPrice',
            'products', 'total', 'page', 'totalPages', 'perPage', 'sortBy',
        ));
    }
}
