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
use App\Models\ProductImage;
use App\Models\Subcategory;
use App\Support\ProductImageUrl;
use App\Services\FilterDataService;
use App\Services\ProductQueryService;
use App\Services\ProductService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
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

        $base = $this->queryService->buildFilteredQuery($filters, $categoryId, null, $request->input('q'), true);
        $query = (clone $base);
        $this->queryService->applyPresentation($query, 'newest');

        $products = $query->paginate(20)->withQueryString();
        $products->getCollection()->transform(function ($product) {
            $product->image_url = $this->resolveProductImageUrl($product->image_path ?? null);

            return $product;
        });

        return view('pages.admin.products', [
            'products' => $products,
            'categories' => Category::orderBy('name')->get(),
            'subcategories' => Subcategory::orderBy('name')->get(),
            'brands' => Brand::orderBy('name')->get(),
            'materials' => Material::orderBy('name')->get(),
            'colors' => Color::orderBy('name')->get(),
            'libraryImages' => $this->libraryImages(),
        ]);
    }

    public function store(StoreProductRequest $request): RedirectResponse
    {
        $this->productService->create(
            $request->except(['images', 'new_images', '_token']),
            $request->file('images', []),
            $request->input('new_images', []),
        );

        return redirect()->route('admin.products')->with('success', 'Produkt bol pridaný.');
    }

    public function update(UpdateProductRequest $request, Product $product): RedirectResponse
    {
        $this->productService->update(
            $product,
            $request->except(['images', 'new_images', 'keep_image_ids', '_token', '_method']),
            $request->file('images', []),
            $request->input('keep_image_ids', []),
            $request->input('new_images', []),
        );

        return redirect()->route('admin.products')->with('success', 'Produkt bol upravený.');
    }

    public function data(Product $product): JsonResponse
    {
        $product->load(['variants.color', 'images' => fn ($q) => $q->orderBy('sort_order')]);

        return response()->json([
            'description' => $product->description,
            'category_id' => $product->category_id,
            'subcategory_id' => $product->subcategory_id,
            'brand_id' => $product->brand_id,
            'material_id' => $product->material_id,
            'is_featured' => $product->is_featured,
            'images' => $product->images->map(fn ($img) => [
                'id' => $img->id,
                'path' => $img->image_path,
                'url' => $this->resolveProductImageUrl($img->image_path),
                'is_primary' => $img->is_primary,
            ]),
            'variants' => $product->variants->map(fn ($v) => [
                'id' => $v->id,
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

    public function setImagePrimary(Product $product, ProductImage $image): JsonResponse
    {
        if ($image->product_id !== $product->id) {
            return response()->json(['error' => 'Not found'], 404);
        }

        $product->images()->update(['is_primary' => 'false']);
        $image->update(['is_primary' => 'true']);

        return response()->json(['ok' => true]);
    }

    public function reorderImages(Request $request, Product $product): JsonResponse
    {
        $ids = $request->input('ids', []);

        if (! is_array($ids)) {
            return response()->json(['error' => 'Invalid input'], 422);
        }

        $productImageIds = $product->images()->pluck('id')->flip();

        foreach ($ids as $order => $id) {
            if ($productImageIds->has((int) $id)) {
                ProductImage::where('id', $id)->update(['sort_order' => $order]);
            }
        }

        return response()->json(['ok' => true]);
    }

    private function resolveProductImageUrl(?string $path): ?string
    {
        return ProductImageUrl::resolve($path);
    }

    private function libraryImages(): array
    {
        $directory = public_path('images/products');

        if (! File::isDirectory($directory)) {
            return [];
        }

        $allowedExtensions = ['jpg', 'jpeg', 'png', 'webp', 'gif'];

        return collect(File::files($directory))
            ->filter(fn ($file) => in_array(strtolower($file->getExtension()), $allowedExtensions, true))
            ->sortBy(fn ($file) => strtolower($file->getFilename()))
            ->map(function ($file): array {
                $path = 'images/products/' . $file->getFilename();

                return [
                    'path' => $path,
                    'url' => ProductImageUrl::resolve($path),
                    'name' => $file->getFilename(),
                ];
            })
            ->values()
            ->all();
    }
}
