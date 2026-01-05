<?php

use Laravolt\Indonesia\Models\City;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;
use Laravolt\Indonesia\Models\Village;
use Laravolt\Indonesia\Models\District;
use Laravolt\Indonesia\Models\Province;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\User\OrderController;
use App\Http\Controllers\LandingPageController;
use App\Http\Controllers\User\OngkirController;
use App\Http\Controllers\User\AddressController;
use App\Http\Controllers\user\PaymentController;
use App\Http\Controllers\User\CheckoutController;
use App\Http\Controllers\User\DashboardController;
use App\Http\Controllers\Auth\CustomerAuthController;
use App\Http\Controllers\User\CartController as UserCartController;
use App\Http\Controllers\User\ProductController as UserProductController;

// ====================
// Halaman Utama (Guest)
// ====================
// Route::get('/', function () {
//     return view('welcome');
// })->name('home');

// landing-page
Route::get('/', function () {
    return redirect()->route('landing-page');
});

Route::get('/landing-page', [LandingPageController::class, 'index'])->name('landing-page');



// ====================
// ROUTE UNTUK ADMIN
// ====================
// Route::middleware(['auth', 'verified'])->group(function () {
//     Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

//     // Profil umum (admin & user)
//     Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
//     Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
//     Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
// });


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
    Route::post('/payment/callback', [App\Http\Controllers\User\PaymentController::class, 'midtransCallback'])->name('payment.callback');
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
        Route::delete('/cart', [UserCartController::class, 'clear'])->name('user.cart.clear');
        Route::delete('/cart/remove/{productId}', [UserCartController::class, 'remove'])->name('user.cart.remove');


        // Checkout

        // Route::get('/checkout', function () {
        //     return view('user.checkout');
        // })->name('checkout');

        Route::get('/checkout', [OrderController::class, 'index'])->name('customer.checkout');
        Route::post('/checkout', [OrderController::class, 'store'])->name('customer.checkout.store');

        Route::get('/order', [OrderController::class, 'index'])->name('customer.order');
        Route::post('/order', [OrderController::class, 'store'])->name('customer.order.store');

        //Route::get('/orders/{invoice}', [OrderController::class, 'show'])->name('customer.orders.show');





        Route::get('/orders/history', [OrderController::class, 'history'])->name('orders.history');
        Route::get('/orders/show/{invoice}', [OrderController::class, 'show'])->name('orders.show');
        Route::get('/orders/filter', [OrderController::class, 'checkStatusPayment'])->name('orders.filter');

        Route::post('/cart/update', [App\Http\Controllers\User\CartController::class, 'update'])->name('user.cart.update');

        Route::post('/payment/callback', [PaymentController::class, 'handleCallback'])->name('payment.callback');


        Route::get('/address/create', [AddressController::class, 'create'])->name('customer.address.create');
        Route::post('/address/store', [AddressController::class, 'store'])->name('customer.address.store');

        // API Helper (Pindahkan ke sini agar konsisten dengan JS)
        Route::get('/get-cities/{provinceId}', [AddressController::class, 'getCities'])->name('api.cities');
        Route::get('/get-districts/{cityId}', [AddressController::class, 'getDistricts'])->name('api.districts');
        Route::get('/get-villages/{districtId}', [AddressController::class, 'getVillages'])->name('api.villages');


        //ongkir

        Route::post('/calculate-ongkir', [OngkirController::class, 'calculate'])->name('calculate');
    });
});

// require __DIR__ . '/auth.php';

// Product detail page - accessible without login
Route::get('/product/{id}', [ProductController::class, 'show'])->name('product.detail');
// Temporary route for cart without auth
Route::get('/cart', function () {
    return redirect()->route('user.cart.index');
})->name('temp.cart');
Route::get('api/address/cities', [AddressController::class, 'cities'])->name('api.address.cities');
Route::get('api/address/districts', [AddressController::class, 'districts'])->name('api.address.districts');
Route::get('api/address/villages', [AddressController::class, 'villages'])->name('api.address.villages');
