<?php

use Illuminate\Support\Facades\Route;

Route::view('/', 'pages.store.landing')->name('home');
Route::view('/kategoria', 'pages.store.category')->name('store.category');
Route::view('/produkt', 'pages.store.product-detail')->name('store.product');
Route::view('/kosik', 'pages.store.cart-step-1')->name('store.cart');
Route::view('/kosik/doprava', 'pages.store.cart-step-2')->name('store.cart.shipping');
Route::view('/kosik/udaje', 'pages.store.cart-step-3')->name('store.cart.details');

Route::prefix('admin')->name('admin.')->group(function () {
    Route::view('/login', 'pages.admin.login')->name('login');
    Route::view('/', 'pages.admin.products')->name('products');
    Route::view('/orders', 'pages.admin.orders')->name('orders');
    Route::view('/settings', 'pages.admin.settings')->name('settings');
});

Route::redirect('/dashboard', '/')->name('dashboard');

require __DIR__.'/settings.php';
