<?php

use App\Http\Controllers\AdminAuthController;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Color;
use App\Models\Material;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

Route::view('/', 'pages.store.landing')->name('home');
Route::get('/kategoria/{p1}/{p2?}', function (string $p1, ?string $p2 = null) {
    $genders    = ['zeny', 'muzi', 'deti'];
    $catSlugs   = ['oblecenie', 'topanky', 'doplnky'];
    $catNames   = ['oblecenie' => 'Oblečenie', 'topanky' => 'Topánky', 'doplnky' => 'Doplnky'];
    $subSlugs   = ['novinky', 'oblecenie', 'topanky', 'doplnky', 'akcie'];

    if (in_array($p1, $genders)) {
        $gender      = $p1;
        $subcategory = $p2;
        if ($subcategory && ! in_array($subcategory, array_merge($catSlugs, $subSlugs))) abort(404);
    } elseif ((in_array($p1, $catSlugs) || in_array($p1, $subSlugs)) && $p2 === null) {
        $gender      = null;
        $subcategory = $p1;
    } else {
        abort(404);
    }

    // Resolve category from slug
    $categoryName = $catNames[$subcategory] ?? $catNames[$p1] ?? null;
    $category     = $categoryName ? Category::where('name', $categoryName)->first() : null;

    // Load subcategories for the sidebar (scoped to category if known)
    $dbSubcategories = $category
        ? $category->subcategories
        : collect();

    // Load all categories with their subcategories for sidebar
    $allCategories = Category::with('subcategories')->get()->map(fn($cat) => [
        'id'   => $cat->id,
        'name' => $cat->name,
        'subs' => $cat->subcategories,
    ]);

    // Load filters: brands, colors, materials
    $brands    = Brand::orderBy('name')->get();
    $colors    = Color::orderBy('name')->get();
    $materials = Material::orderBy('name')->get();
    $allSizes  = ['XS', 'S', 'M', 'L', 'XL', 'XXL'];
    $globalMinPrice = (int) floor(ProductVariant::min('price') ?? 0);
    $globalMaxPrice = (int) ceil(ProductVariant::max('price') ?? 1000);

    // Active filters (all arrays)
    $filterBrands    = array_filter((array) request('brand', []));
    $filterColors    = array_filter((array) request('color', []));
    $filterMaterials = array_filter((array) request('material', []));
    $filterSizes     = array_filter((array) request('size', []));

    // Query products
    $perPage  = 12;
    $page     = max(1, (int) request('page', 1));
    $sortBy   = request('sort', 'featured');
    $minPrice = request('min_price', null);
    $maxPrice = request('max_price', null);

    // Subquery: qualifying variant ids — color, size, price must all match on the SAME variant row
    $variantSub = DB::table('product_variants')
        ->join('colors', 'product_variants.color_id', '=', 'colors.id')
        ->select('product_variants.product_id', DB::raw('MIN(product_variants.price) as variant_min_price'));
    if ($filterColors) {
        $variantSub->whereIn('colors.name', $filterColors);
    }
    if ($filterSizes) {
        $variantSub->whereIn('product_variants.size', $filterSizes);
    }
    if ($minPrice !== null && $minPrice !== '') {
        $variantSub->where('product_variants.price', '>=', $minPrice);
    }
    if ($maxPrice !== null && $maxPrice !== '') {
        $variantSub->where('product_variants.price', '<=', $maxPrice);
    }
    $variantSub->groupBy('product_variants.product_id');

    // Main query: join qualifying variants, filter by product-level attrs (brand, material, category)
    $query = Product::query()
        ->joinSub($variantSub, 'qv', 'products.id', '=', 'qv.product_id')
        ->join('brands', 'products.brand_id', '=', 'brands.id')
        ->join('materials', 'products.material_id', '=', 'materials.id')
        ->leftJoin('subcategories', 'products.subcategory_id', '=', 'subcategories.id')
        ->leftJoin('product_images', function ($join) {
            $join->on('products.id', '=', 'product_images.product_id')
                 ->where('product_images.is_primary', true);
        })
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
            DB::raw("STRING_AGG(DISTINCT product_variants_all.size, ', ') as sizes")
        )
        ->join('product_variants as product_variants_all', 'products.id', '=', 'product_variants_all.product_id')
        ->groupBy('products.id', 'products.name', 'products.slug', 'products.is_featured', 'brands.name', 'subcategories.name', 'product_images.image_path', 'qv.variant_min_price');

    if ($category) {
        $query->where('products.category_id', $category->id);
    }
    if ($filterBrands) {
        $query->whereIn('brands.name', $filterBrands);
    }
    if ($filterMaterials) {
        $query->whereIn('materials.name', $filterMaterials);
    }

    match ($sortBy) {
        'price_asc'  => $query->orderBy('qv.variant_min_price'),
        'price_desc' => $query->orderByDesc('qv.variant_min_price'),
        'new'        => $query->orderByDesc('products.created_at'),
        default      => $query->orderByDesc('products.is_featured')->orderByDesc('products.created_at'),
    };

    $total = Product::query()
        ->joinSub(clone $variantSub, 'qv', 'products.id', '=', 'qv.product_id')
        ->join('brands', 'products.brand_id', '=', 'brands.id')
        ->join('materials', 'products.material_id', '=', 'materials.id')
        ->when($category, fn ($q) => $q->where('products.category_id', $category->id))
        ->when($filterBrands, fn ($q) => $q->whereIn('brands.name', $filterBrands))
        ->when($filterMaterials, fn ($q) => $q->whereIn('materials.name', $filterMaterials))
        ->distinct()
        ->count('products.id');

    $totalPages = max(1, (int) ceil($total / $perPage));
    $products   = $query->offset(($page - 1) * $perPage)->limit($perPage)->get();

    // Sort sizes in each product
    $sizeOrder = ['XS' => 0, 'S' => 1, 'M' => 2, 'L' => 3, 'XL' => 4, 'XXL' => 5];
    $products->each(function ($product) use ($sizeOrder) {
        $sizes = explode(', ', $product->sizes);
        usort($sizes, fn ($a, $b) => ($sizeOrder[$a] ?? 999) <=> ($sizeOrder[$b] ?? 999));
        $product->sizes = implode(', ', $sizes);
    });

    return view('pages.store.category', compact(
        'gender', 'subcategory', 'category',
        'allCategories', 'dbSubcategories',
        'brands', 'colors', 'materials', 'allSizes',
        'filterBrands', 'filterColors', 'filterMaterials', 'filterSizes',
        'globalMinPrice', 'globalMaxPrice',
        'products', 'total', 'page', 'totalPages', 'perPage', 'sortBy'
    ));
})->name('store.category');

Route::get('/hladat', function () {
    $q       = trim((string) request('q', ''));
    $perPage = 12;
    $page    = max(1, (int) request('page', 1));
    $sortBy  = request('sort', 'featured');

    $brands    = Brand::orderBy('name')->get();
    $colors    = Color::orderBy('name')->get();
    $materials = Material::orderBy('name')->get();
    $allSizes  = ['XS', 'S', 'M', 'L', 'XL', 'XXL'];
    $globalMinPrice = (int) floor(ProductVariant::min('price') ?? 0);
    $globalMaxPrice = (int) ceil(ProductVariant::max('price') ?? 1000);

    $filterBrands    = array_filter((array) request('brand', []));
    $filterColors    = array_filter((array) request('color', []));
    $filterMaterials = array_filter((array) request('material', []));
    $filterSizes     = array_filter((array) request('size', []));
    $minPrice = request('min_price', null);
    $maxPrice = request('max_price', null);

    $variantSub = DB::table('product_variants')
        ->join('colors', 'product_variants.color_id', '=', 'colors.id')
        ->select('product_variants.product_id', DB::raw('MIN(product_variants.price) as variant_min_price'));
    if ($filterColors) $variantSub->whereIn('colors.name', $filterColors);
    if ($filterSizes)  $variantSub->whereIn('product_variants.size', $filterSizes);
    if ($minPrice !== null && $minPrice !== '') $variantSub->where('product_variants.price', '>=', $minPrice);
    if ($maxPrice !== null && $maxPrice !== '') $variantSub->where('product_variants.price', '<=', $maxPrice);
    $variantSub->groupBy('product_variants.product_id');

    $query = Product::query()
        ->joinSub($variantSub, 'qv', 'products.id', '=', 'qv.product_id')
        ->join('brands', 'products.brand_id', '=', 'brands.id')
        ->join('materials', 'products.material_id', '=', 'materials.id')
        ->leftJoin('subcategories', 'products.subcategory_id', '=', 'subcategories.id')
        ->leftJoin('product_images', function ($join) {
            $join->on('products.id', '=', 'product_images.product_id')
                 ->where('product_images.is_primary', true);
        })
        ->select(
            'products.id', 'products.name', 'products.slug', 'products.is_featured',
            'brands.name as brand_name', 'subcategories.name as subcategory_name',
            'product_images.image_path', 'qv.variant_min_price as min_price',
            DB::raw("STRING_AGG(DISTINCT product_variants_all.size, ', ') as sizes")
        )
        ->join('product_variants as product_variants_all', 'products.id', '=', 'product_variants_all.product_id')
        ->groupBy('products.id', 'products.name', 'products.slug', 'products.is_featured', 'brands.name', 'subcategories.name', 'product_images.image_path', 'qv.variant_min_price');

    if ($q !== '') {
        $query->where(function ($w) use ($q) {
            $w->whereRaw('LOWER(products.name) LIKE ?', ['%' . mb_strtolower($q) . '%'])
              ->orWhereRaw('LOWER(products.description) LIKE ?', ['%' . mb_strtolower($q) . '%'])
              ->orWhereRaw('LOWER(brands.name) LIKE ?', ['%' . mb_strtolower($q) . '%']);
        });
    }

    if ($filterBrands)    $query->whereIn('brands.name', $filterBrands);
    if ($filterMaterials) $query->whereIn('materials.name', $filterMaterials);

    match ($sortBy) {
        'price_asc'  => $query->orderBy('qv.variant_min_price'),
        'price_desc' => $query->orderByDesc('qv.variant_min_price'),
        'new'        => $query->orderByDesc('products.created_at'),
        default      => $query->orderByDesc('products.is_featured')->orderByDesc('products.created_at'),
    };

    $total = Product::query()
        ->joinSub(clone $variantSub, 'qv', 'products.id', '=', 'qv.product_id')
        ->join('brands', 'products.brand_id', '=', 'brands.id')
        ->join('materials', 'products.material_id', '=', 'materials.id')
        ->when($q !== '', fn ($w) => $w->where(function ($ww) use ($q) {
            $ww->whereRaw('LOWER(products.name) LIKE ?', ['%' . mb_strtolower($q) . '%'])
               ->orWhereRaw('LOWER(products.description) LIKE ?', ['%' . mb_strtolower($q) . '%'])
               ->orWhereRaw('LOWER(brands.name) LIKE ?', ['%' . mb_strtolower($q) . '%']);
        }))
        ->when($filterBrands, fn ($w) => $w->whereIn('brands.name', $filterBrands))
        ->when($filterMaterials, fn ($w) => $w->whereIn('materials.name', $filterMaterials))
        ->distinct()
        ->count('products.id');

    $totalPages = max(1, (int) ceil($total / $perPage));
    $products   = $query->offset(($page - 1) * $perPage)->limit($perPage)->get();

    $sizeOrder = ['XS' => 0, 'S' => 1, 'M' => 2, 'L' => 3, 'XL' => 4, 'XXL' => 5];
    $products->each(function ($product) use ($sizeOrder) {
        $sizes = explode(', ', $product->sizes);
        usort($sizes, fn ($a, $b) => ($sizeOrder[$a] ?? 999) <=> ($sizeOrder[$b] ?? 999));
        $product->sizes = implode(', ', $sizes);
    });

    return view('pages.store.search', compact(
        'q', 'brands', 'colors', 'materials', 'allSizes',
        'filterBrands', 'filterColors', 'filterMaterials', 'filterSizes',
        'globalMinPrice', 'globalMaxPrice',
        'products', 'total', 'page', 'totalPages', 'perPage', 'sortBy',
        'minPrice', 'maxPrice'
    ));
})->name('store.search');

Route::get('/produkt/{slug}', function (string $slug) {
    $product = Product::with(['brand', 'material', 'category', 'subcategory'])
        ->where('slug', $slug)
        ->firstOrFail();

    // All images sorted
    $images = $product->images()->orderBy('sort_order')->get();

    // All variants with color info, ordered by color then size
    $variants = $product->variants()
        ->with('color')
        ->orderBy('color_id')
        ->orderByRaw("CASE size WHEN 'XS' THEN 0 WHEN 'S' THEN 1 WHEN 'M' THEN 2 WHEN 'L' THEN 3 WHEN 'XL' THEN 4 WHEN 'XXL' THEN 5 END")
        ->get()
        ->map(fn($v) => (object) [
            'id'             => $v->id,
            'color_id'       => $v->color_id,
            'color_name'     => $v->color->name,
            'hex_code'       => $v->color->hex_code,
            'size'           => $v->size,
            'price'          => $v->price,
            'stock_quantity' => $v->stock_quantity,
        ]);

    // Unique colors (preserve order)
    $colors = $variants->unique('color_id')->values();

    // Min price across all variants
    $minPrice = $variants->min('price');

    // Similar products: same subcategory (or category), exclude self, limit 4
    $similarSub = DB::table('product_variants')
        ->select('product_id', DB::raw('MIN(price) as variant_min_price'))
        ->groupBy('product_id');

    $similarQuery = Product::query()
        ->joinSub($similarSub, 'qv', 'products.id', '=', 'qv.product_id')
        ->join('brands', 'products.brand_id', '=', 'brands.id')
        ->leftJoin('product_images', function ($join) {
            $join->on('products.id', '=', 'product_images.product_id')
                 ->where('product_images.is_primary', true);
        })
        ->select(
            'products.id', 'products.name', 'products.slug',
            'brands.name as brand_name',
            'product_images.image_path',
            'qv.variant_min_price as min_price',
            DB::raw("STRING_AGG(DISTINCT pv_all.size, ', ') as sizes")
        )
        ->join('product_variants as pv_all', 'products.id', '=', 'pv_all.product_id')
        ->where('products.id', '!=', $product->id)
        ->groupBy('products.id', 'products.name', 'products.slug', 'brands.name', 'product_images.image_path', 'qv.variant_min_price')
        ->limit(4);

    if ($product->subcategory_id) {
        $similarQuery->where('products.subcategory_id', $product->subcategory_id);
    } else {
        $similarQuery->where('products.category_id', $product->category_id);
    }

    $similarProducts = $similarQuery->get();

    $sizeOrder = ['XS' => 0, 'S' => 1, 'M' => 2, 'L' => 3, 'XL' => 4, 'XXL' => 5];
    $similarProducts->each(function ($p) use ($sizeOrder) {
        $sizes = array_filter(explode(', ', $p->sizes ?? ''));
        usort($sizes, fn ($a, $b) => ($sizeOrder[$a] ?? 999) <=> ($sizeOrder[$b] ?? 999));
        $p->sizes = implode(', ', $sizes);
    });

    // Breadcrumb
    $catSlugs = ['Oblečenie' => 'oblecenie', 'Topánky' => 'topanky', 'Doplnky' => 'doplnky'];
    $breadcrumb = [['label' => 'Domov', 'href' => url('/')]];
    if ($product->category && isset($catSlugs[$product->category->name])) {
        $breadcrumb[] = ['label' => $product->category->name, 'href' => url('/kategoria/' . $catSlugs[$product->category->name])];
    }
    if ($product->subcategory) {
        $breadcrumb[] = ['label' => $product->subcategory->name];
    }
    $breadcrumb[] = ['label' => $product->name];

    // Flatten product attributes for the view (blade templates reference ->brand_name, ->material_name etc.)
    $product->brand_name      = $product->brand->name ?? null;
    $product->material_name   = $product->material->name ?? null;
    $product->category_name   = $product->category->name ?? null;
    $product->subcategory_name = $product->subcategory?->name;

    return view('pages.store.product-detail', compact(
        'product', 'images', 'variants', 'colors', 'minPrice',
        'similarProducts', 'breadcrumb'
    ));
})->name('store.product');

Route::view('/kosik', 'pages.store.cart-step-1')->name('store.cart');
Route::view('/kosik/doprava', 'pages.store.cart-step-2')->name('store.cart.shipping');
Route::view('/kosik/udaje', 'pages.store.cart-step-3')->name('store.cart.details');

Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/login', [AdminAuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AdminAuthController::class, 'login'])->name('login.store');
    Route::post('/logout', [AdminAuthController::class, 'logout'])->name('logout');

    Route::middleware('admin')->group(function () {
        Route::view('/', 'pages.admin.products')->name('products');
        Route::view('/orders', 'pages.admin.orders')->name('orders');
        Route::view('/settings', 'pages.admin.settings')->name('settings');
    });
});

Route::redirect('/dashboard', '/')->name('dashboard');

require __DIR__.'/settings.php';
