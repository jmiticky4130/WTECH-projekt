<?php

use App\Http\Controllers\AdminAuthController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'pages.store.landing')->name('home');
Route::get('/kategoria/{p1}/{p2?}', function (string $p1, ?string $p2 = null) {
    $genders       = ['zeny', 'muzi', 'deti'];
    $subcategories = ['novinky', 'akcie', 'oblecenie', 'topanky', 'doplnky'];

    if (in_array($p1, $genders)) {
        $gender      = $p1;
        $subcategory = $p2;
        if ($subcategory && ! in_array($subcategory, $subcategories)) abort(404);
    } elseif (in_array($p1, $subcategories) && $p2 === null) {
        $gender      = null;
        $subcategory = $p1;
    } else {
        abort(404);
    }

    return view('pages.store.category', compact('gender', 'subcategory'));
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
