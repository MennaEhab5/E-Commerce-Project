<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\WishlistController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\CheckoutController;


Route::get('/', function () {
    return redirect()->route('register');
});

// Home
Route::get('/home', function () {
    return view('home');
})->name('home')->middleware('auth');

// Products List
Route::get('/products', [ProductController::class, 'index'])
    ->name('products.index')
    ->middleware('auth');

// Product Details
Route::get('/products/{product}', [ProductController::class, 'show'])
    ->name('products.show')
    ->middleware('auth');

// Categories
Route::get('/categories', function () {
    return view('categories');
})->name('categories.index')->middleware('auth');

// Brands
Route::get('/brands', function () {
    return view('brands');
})->name('brands.index')->middleware('auth');

// Cart Routes
Route::middleware('auth')->group(function() {
    Route::get('/cart', [CartController::class,'index'])->name('cart.index');
    Route::post('/cart/add/{product}', [CartController::class,'add'])->name('cart.add');
    Route::post('/cart/remove/{product}', [CartController::class,'remove'])->name('cart.remove');
    Route::post('/cart/update/{product}', [CartController::class,'update'])->name('cart.update');
});

// Wishlist Routes
Route::middleware('auth')->group(function() {
    Route::get('/wishlist', [WishlistController::class,'index'])->name('wishlist.index');
    Route::post('/wishlist/add/{product}', [WishlistController::class,'add'])->name('wishlist.add');
    Route::post('/wishlist/remove/{product}', [WishlistController::class,'remove'])->name('wishlist.remove');
});

// Orders
Route::middleware('auth')->group(function() {
    Route::get('/orders', [OrderController::class,'index'])->name('orders.index');
});

// Checkout (Cash Only)
Route::middleware('auth')->group(function() {
    Route::get('/checkout', [CheckoutController::class,'index'])->name('checkout.index');
    Route::post('/checkout/cash', [CheckoutController::class,'cash'])->name('checkout.cash');
});

// Contact Form
Route::post('/contact/send', [ContactController::class, 'send'])
    ->name('contact.send');

// Auth routes
require __DIR__ . '/auth.php';
