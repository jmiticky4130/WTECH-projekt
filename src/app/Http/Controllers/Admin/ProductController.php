<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreProductRequest;
use App\Http\Requests\Admin\UpdateProductRequest;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Color;
use App\Models\Material;
use App\Models\Product;
use App\Models\Subcategory;
use App\Services\FilterDataService;
use App\Services\ProductQueryService;
use App\Services\ProductService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function __construct(
        private ProductQueryService $queryService,
        private FilterDataService $filterService,
        private ProductService $productService,
    ) {}

    public function index(Request $request): View
    {
        $filters = ['brand' => [], 'color' => [], 'material' => [], 'size' => [], 'min_price' => null, 'max_price' => null];
        $categoryId = $request->input('category') ? Category::where('name', $request->input('category'))->value('id') : null;

        $base = $this->queryService->buildFilteredQuery($filters, $categoryId, null, $request->input('q'));
        $query = (clone $base);
        $this->queryService->applyPresentation($query, 'newest');

        $products = $query->paginate(20)->withQueryString();

        return view('pages.admin.products', [
            'products' => $products,
            'categories' => Category::orderBy('name')->get(),
            'subcategories' => Subcategory::orderBy('name')->get(),
            'brands' => Brand::orderBy('name')->get(),
            'materials' => Material::orderBy('name')->get(),
            'colors' => Color::orderBy('name')->get(),
        ]);
    }

    public function store(StoreProductRequest $request): RedirectResponse
    {
        $this->productService->create(
            $request->except(['images', '_token']),
            $request->file('images', []),
        );

        return redirect()->route('admin.products')->with('success', 'Produkt bol pridaný.');
    }

    public function update(UpdateProductRequest $request, Product $product): RedirectResponse
    {
        $this->productService->update(
            $product,
            $request->except(['images', 'keep_image_ids', '_token', '_method']),
            $request->file('images', []),
            $request->input('keep_image_ids', []),
        );

        return redirect()->route('admin.products')->with('success', 'Produkt bol upravený.');
    }

    public function data(Product $product): JsonResponse
    {
        $product->load(['variants.color', 'images']);

        return response()->json([
            'description' => $product->description,
            'category_id' => $product->category_id,
            'subcategory_id' => $product->subcategory_id,
            'brand_id' => $product->brand_id,
            'material_id' => $product->material_id,
            'is_featured' => $product->is_featured,
            'images' => $product->images->map(fn ($img) => [
                'id' => $img->id,
                'url' => Storage::url($img->image_path),
                'is_primary' => $img->is_primary,
            ]),
            'variants' => $product->variants->map(fn ($v) => [
                'color_id' => $v->color_id,
                'color_name' => $v->color?->name,
                'size' => $v->size,
                'price' => $v->price,
                'stock_quantity' => $v->stock_quantity,
            ]),
        ]);
    }

    public function destroy(Product $product): RedirectResponse
    {
        $this->productService->delete($product);

        return redirect()->route('admin.products')->with('success', 'Produkt bol vymazaný.');
    }
}
