<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\User\DashboardController;
use App\Http\Controllers\User\ProductController as UserProductController;
use App\Http\Controllers\User\CartController as UserCartController;
use App\Http\Controllers\Auth\CustomerAuthController;
use App\Http\Controllers\LandingPageController;
use App\Http\Controllers\ProductController;

// ====================
// Halaman Utama (Guest)
// ====================
Route::get('/', function () {
    return view('welcome');
})->name('home');

// ====================
// ROUTE UNTUK ADMIN
// ====================
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Profil umum (admin & user)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// ====================
// ROUTE UNTUK CUSTOMER
// ====================
Route::prefix('customer')->group(function () {

    // Auth Customer
    Route::get('/login', [CustomerAuthController::class, 'showLoginForm'])->name('customer.login');
    Route::post('/login', [CustomerAuthController::class, 'login']);
    Route::get('/register', [CustomerAuthController::class, 'showRegisterForm'])->name('customer.register');
    Route::post('/register', [CustomerAuthController::class, 'register']);
    Route::post('/logout', [CustomerAuthController::class, 'logout'])->name('customer.logout');

    // Setelah login
    Route::middleware('auth:customer')->group(function () {
        Route::get('/dashboard', [UserProductController::class, 'index'])->name('user.dashboard');

        // Produk
        Route::middleware(['auth:customer'])->group(function () {
            Route::get('/products', [UserProductController::class, 'index'])->name('user.products');
            Route::get('/product/{id}', [UserProductController::class, 'show'])->name('user.product.show');
        });
        // Keranjang
        // Keranjang
        Route::get('/cart', [UserCartController::class, 'index'])->name('user.cart.index');
        Route::post('/cart/add', [UserCartController::class, 'store'])->name('user.cart.store');
        Route::post('/cart/remove/{productId}', [UserCartController::class, 'remove'])->name('user.cart.remove');

        // Checkout
        Route::get('/checkout', function () {
            return view('user.checkout');
        })->name('checkout');
    });
});

require __DIR__ . '/auth.php';

require __DIR__ . '/auth.php';

// landing-page
Route::get('/landing-page', [LandingPageController::class, 'index'])->name('landing-page');

// Product detail page - accessible without login
Route::get('/product/{id}', [ProductController::class, 'show'])->name('product.detail');
