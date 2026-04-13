<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use App\Http\Requests\SearchFilterRequest;
use App\Services\FilterDataService;
use App\Services\ProductQueryService;
use App\Support\CategoryMapping;

class SearchController extends Controller
{
    public function __construct(
        private ProductQueryService $productQuery,
        private FilterDataService $filterData,
    ) {}

    public function index(SearchFilterRequest $request)
    {
        $q       = trim((string) $request->input('q', ''));
        $perPage = 12;
        $page    = max(1, (int) $request->input('page', 1));
        $sortBy  = $request->input('sort', 'featured');

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
        $minPrice        = $request->input('min_price');
        $maxPrice        = $request->input('max_price');

        $filters = [
            'brand'     => $filterBrands,
            'color'     => $filterColors,
            'material'  => $filterMaterials,
            'size'      => $filterSizes,
            'min_price' => $minPrice,
            'max_price' => $maxPrice,
        ];

        $baseQuery  = $this->productQuery->buildFilteredQuery($filters, null, $q !== '' ? $q : null);
        $total      = $this->productQuery->getTotal($baseQuery);
        $totalPages = max(1, (int) ceil($total / $perPage));

        $this->productQuery->applyPresentation($baseQuery, $sortBy);
        $products = $baseQuery->offset(($page - 1) * $perPage)->limit($perPage)->get();
        $this->productQuery->sortProductSizes($products);

        return view('pages.store.search', compact(
            'q', 'brands', 'colors', 'materials', 'allSizes',
            'filterBrands', 'filterColors', 'filterMaterials', 'filterSizes',
            'globalMinPrice', 'globalMaxPrice',
            'products', 'total', 'page', 'totalPages', 'perPage', 'sortBy',
            'minPrice', 'maxPrice',
        ));
    }
}
