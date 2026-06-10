<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\WishlistController;
use Illuminate\Support\Facades\Route;

// Home
Route::get('/', [ProductController::class, 'home'])->name('home');

// Products & Categories
Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/{product:slug}', [ProductController::class, 'show'])->name('products.show');
Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
Route::get('/categories/{category:slug}', [CategoryController::class, 'show'])->name('categories.show');

// Authentication routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

Route::get('/admin/login', [AuthController::class, 'showAdminLogin'])->name('admin.login');
Route::post('/admin/login', [AuthController::class, 'adminLogin']);

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Cart routes (public)
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
Route::put('/cart/{product}', [CartController::class, 'update'])->name('cart.update');
Route::delete('/cart/{product}', [CartController::class, 'remove'])->name('cart.remove');

// Checkout & Order Placement (public)
Route::get('/checkout', [OrderController::class, 'checkout'])->name('checkout');
Route::post('/orders', [OrderController::class, 'store'])->name('orders.store');
Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');

// Customer Panel (authenticated)
Route::middleware('auth')->group(function () {
    // Wishlist
    Route::get('/wishlist', [WishlistController::class, 'index'])->name('wishlist.index');
    Route::post('/wishlist/add', [WishlistController::class, 'add'])->name('wishlist.add');
    Route::delete('/wishlist/{product}', [WishlistController::class, 'remove'])->name('wishlist.remove');

    // Reviews
    Route::post('/products/{product}/reviews', [ReviewController::class, 'store'])->name('reviews.store');
    Route::put('/reviews/{review}', [ReviewController::class, 'update'])->name('reviews.update');
    Route::delete('/reviews/{review}', [ReviewController::class, 'destroy'])->name('reviews.destroy');

    // Orders index
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
});

// Admin Panel (authenticated & role=admin)
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

    // Products CRUD
    Route::get('/products', [AdminController::class, 'productsIndex'])->name('products.index');
    Route::get('/products/create', [AdminController::class, 'productsCreate'])->name('products.create');
    Route::post('/products', [AdminController::class, 'productsStore'])->name('products.store');
    Route::get('/products/{product}/edit', [AdminController::class, 'productsEdit'])->name('products.edit');
    Route::put('/products/{product}', [AdminController::class, 'productsUpdate'])->name('products.update');
    Route::delete('/products/{product}', [AdminController::class, 'productsDestroy'])->name('products.destroy');

    // Categories CRUD
    Route::get('/categories', [AdminController::class, 'categoriesIndex'])->name('categories.index');
    Route::post('/categories', [AdminController::class, 'categoriesStore'])->name('categories.store');
    Route::delete('/categories/{category}', [AdminController::class, 'categoriesDestroy'])->name('categories.destroy');

    // Orders management
    Route::get('/orders', [AdminController::class, 'ordersIndex'])->name('orders.index');
    Route::get('/orders/{order}', [AdminController::class, 'ordersShow'])->name('orders.show');
    Route::put('/orders/{order}/status', [AdminController::class, 'ordersUpdateStatus'])->name('orders.update-status');
    Route::put('/orders/{order}/payment', [AdminController::class, 'ordersUpdatePayment'])->name('orders.update-payment');

    // Vendors management
    Route::get('/vendors', [AdminController::class, 'vendorsIndex'])->name('vendors.index');
    Route::put('/vendors/{vendor}/verify', [AdminController::class, 'vendorsToggleVerify'])->name('vendors.toggle-verify');

    // Shipping Locations CRUD
    Route::get('/shipping-locations', [AdminController::class, 'shippingIndex'])->name('shipping.index');
    Route::post('/shipping-locations', [AdminController::class, 'shippingStore'])->name('shipping.store');
    Route::delete('/shipping-locations/{location}', [AdminController::class, 'shippingDestroy'])->name('shipping.destroy');
});
