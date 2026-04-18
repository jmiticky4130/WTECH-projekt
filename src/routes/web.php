<?php

use App\Http\Controllers\Admin;
use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\Store\CartController;
use App\Http\Controllers\Store\CategoryController;
use App\Http\Controllers\Store\LandingController;
use App\Http\Controllers\Store\ProductController;
use App\Http\Controllers\Store\SearchController;
use Illuminate\Support\Facades\Route;

Route::get('/', [LandingController::class, 'index'])->name('home');
Route::get('/kategoria/{p1}/{p2?}', [CategoryController::class, 'index'])->name('store.category');
Route::get('/hladat', [SearchController::class, 'index'])->name('store.search');
Route::get('/search-suggestions', [SearchController::class, 'suggestions'])->name('store.search.suggestions');
Route::get('/produkt/{slug}', [ProductController::class, 'show'])->name('store.product');

Route::view('/kosik', 'pages.store.cart-step-1')->name('store.cart');
Route::get('/kosik/doprava', [CartController::class, 'shipping'])->name('store.cart.shipping');
Route::get('/kosik/udaje', [CartController::class, 'details'])->name('store.cart.details');
Route::get('/kosik/hotovo/{order}', [CartController::class, 'thanks'])->name('store.cart.thanks');

Route::get('/cart/data', [CartController::class, 'data'])->name('cart.data');
Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
Route::patch('/cart/item/{cartItem}', [CartController::class, 'update'])->name('cart.update');
Route::delete('/cart/item/{cartItem}', [CartController::class, 'remove'])->name('cart.remove');
Route::get('/cart/count', [CartController::class, 'count'])->name('cart.count');
Route::post('/cart/order', [CartController::class, 'place'])->name('store.cart.place');

Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/login', [AdminAuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AdminAuthController::class, 'login'])->name('login.store');
    Route::post('/logout', [AdminAuthController::class, 'logout'])->name('logout');

    Route::middleware('admin')->group(function () {
        Route::get('/', [Admin\ProductController::class, 'index'])->name('products');
        Route::post('/products', [Admin\ProductController::class, 'store'])->name('products.store');
        Route::get('/products/{product}/data', [Admin\ProductController::class, 'data'])->name('products.data');
        Route::put('/products/{product}', [Admin\ProductController::class, 'update'])->name('products.update');
        Route::delete('/products/{product}', [Admin\ProductController::class, 'destroy'])->name('products.destroy');

        Route::get('/orders', [Admin\OrderController::class, 'index'])->name('orders');
        Route::get('/orders/{order}', [Admin\OrderController::class, 'show'])->name('orders.show');
        Route::patch('/orders/{order}', [Admin\OrderController::class, 'update'])->name('orders.update');
        Route::delete('/orders/{order}', [Admin\OrderController::class, 'destroy'])->name('orders.destroy');

        Route::get('/settings', [Admin\SettingsController::class, 'index'])->name('settings');
        Route::resource('settings/categories', Admin\CategoryController::class)->only(['store', 'update', 'destroy'])->names(['store' => 'categories.store', 'update' => 'categories.update', 'destroy' => 'categories.destroy']);
        Route::resource('settings/subcategories', Admin\SubcategoryController::class)->only(['store', 'update', 'destroy'])->names(['store' => 'subcategories.store', 'update' => 'subcategories.update', 'destroy' => 'subcategories.destroy']);
        Route::resource('settings/brands', Admin\BrandController::class)->only(['store', 'update', 'destroy'])->names(['store' => 'brands.store', 'update' => 'brands.update', 'destroy' => 'brands.destroy']);
        Route::resource('settings/colors', Admin\ColorController::class)->only(['store', 'update', 'destroy'])->names(['store' => 'colors.store', 'update' => 'colors.update', 'destroy' => 'colors.destroy']);
        Route::resource('settings/materials', Admin\MaterialController::class)->only(['store', 'update', 'destroy'])->names(['store' => 'materials.store', 'update' => 'materials.update', 'destroy' => 'materials.destroy']);
        Route::resource('settings/shipping-methods', Admin\ShippingMethodController::class)->only(['store', 'update', 'destroy'])->names(['store' => 'shipping-methods.store', 'update' => 'shipping-methods.update', 'destroy' => 'shipping-methods.destroy']);
        Route::resource('settings/payment-methods', Admin\PaymentMethodController::class)->only(['store', 'update', 'destroy'])->names(['store' => 'payment-methods.store', 'update' => 'payment-methods.update', 'destroy' => 'payment-methods.destroy']);
    });
});

Route::redirect('/dashboard', '/')->name('dashboard');

require __DIR__.'/settings.php';
