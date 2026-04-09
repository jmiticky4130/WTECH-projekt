<?php

use App\Http\Controllers\AdminAuthController;
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
    $category     = $categoryName ? DB::table('categories')->where('name', $categoryName)->first() : null;

    // Load subcategories for the sidebar (scoped to category if known)
    $dbSubcategories = $category
        ? DB::table('subcategories')->where('category_id', $category->id)->get()
        : collect();

    // Load all categories with their subcategories for sidebar
    $allCategories = DB::table('categories')
        ->get()
        ->map(fn($cat) => [
            'id'   => $cat->id,
            'name' => $cat->name,
            'subs' => DB::table('subcategories')->where('category_id', $cat->id)->get(),
        ]);

    // Load filters: brands, colors, materials
    $brands    = DB::table('brands')->orderBy('name')->get();
    $colors    = DB::table('colors')->orderBy('name')->get();
    $materials = DB::table('materials')->orderBy('name')->get();
    $allSizes  = ['XS', 'S', 'M', 'L', 'XL', 'XXL'];
    $globalMinPrice = (int) floor(DB::table('product_variants')->min('price') ?? 0);
    $globalMaxPrice = (int) ceil(DB::table('product_variants')->max('price') ?? 1000);

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
    $query = DB::table('products')
        ->joinSub($variantSub, 'qv', 'products.id', '=', 'qv.product_id')
        ->join('brands', 'products.brand_id', '=', 'brands.id')
        ->join('materials', 'products.material_id', '=', 'materials.id')
        ->leftJoin('subcategories', 'products.subcategory_id', '=', 'subcategories.id')
        ->leftJoin('product_images', function ($join) {
            $join->on('products.id', '=', 'product_images.product_id')
                 ->whereRaw('"product_images"."is_primary" = true');
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
            // sizes shown are only the qualifying ones (matching color/size filters)
            DB::raw("STRING_AGG(DISTINCT product_variants_all.size, ', ') as sizes")
        )
        ->join('product_variants as product_variants_all', 'products.id', '=', 'product_variants_all.product_id')
        ->whereNull('products.deleted_at')
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

    $total = DB::table('products')
        ->joinSub(clone $variantSub, 'qv', 'products.id', '=', 'qv.product_id')
        ->join('brands', 'products.brand_id', '=', 'brands.id')
        ->join('materials', 'products.material_id', '=', 'materials.id')
        ->whereNull('products.deleted_at')
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
Route::view('/produkt', 'pages.store.product-detail')->name('store.product');
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
