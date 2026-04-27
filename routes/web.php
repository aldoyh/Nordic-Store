<?php

use App\Http\Controllers\MarketplaceController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\InstagramController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

Route::get('/', [MarketplaceController::class, 'index'])->name('marketplace.index');

Route::get('/shop/{shop}', [ShopController::class, 'show'])->name('shop.show');
Route::post('/shop/{shop}/cart/add/{product}', [CartController::class, 'add'])->name('cart.add');
Route::get('/shop/{shop}/cart', [CartController::class, 'show'])->name('cart.show');
Route::patch('/shop/{shop}/cart/{item}', [CartController::class, 'update'])->name('cart.update');
Route::delete('/shop/{shop}/cart/{item}', [CartController::class, 'remove'])->name('cart.remove');
Route::post('/shop/{shop}/cart/clear', [CartController::class, 'clear'])->name('cart.clear');

Route::get('/shop/{shop}/checkout/shipping', [CheckoutController::class, 'shipping'])->name('checkout.shipping');
Route::post('/shop/{shop}/checkout/shipping', [CheckoutController::class, 'storeShipping'])->name('checkout.shipping.store');
Route::get('/shop/{shop}/checkout/payment', [CheckoutController::class, 'payment'])->name('checkout.payment');
Route::post('/shop/{shop}/checkout/payment', [CheckoutController::class, 'storePayment'])->name('checkout.payment.store');
Route::get('/shop/{shop}/checkout/confirmation/{order}', [CheckoutController::class, 'confirmation'])->name('checkout.confirmation');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/instagram/create', [InstagramController::class, 'create'])->name('instagram.create');
    Route::post('/instagram', [InstagramController::class, 'store'])->name('instagram.store');
});

require __DIR__.'/auth.php';
