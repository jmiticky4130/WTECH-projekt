<?php

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
Route::view('/kosik/doprava', 'pages.store.cart-step-2')->name('store.cart.shipping');
Route::view('/kosik/udaje', 'pages.store.cart-step-3')->name('store.cart.details');

Route::get('/cart/data', [CartController::class, 'data'])->name('cart.data');
Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
Route::patch('/cart/item/{cartItem}', [CartController::class, 'update'])->name('cart.update');
Route::delete('/cart/item/{cartItem}', [CartController::class, 'remove'])->name('cart.remove');
Route::get('/cart/count', [CartController::class, 'count'])->name('cart.count');

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
