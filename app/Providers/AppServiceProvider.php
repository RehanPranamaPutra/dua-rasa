<?php

namespace App\Providers;

use App\Models\Cart;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::composer('*', function ($view) {
            if (Auth::guard('customer')->check()) {
                $count = Cart::where('customer_id', Auth::guard('customer')->id())->sum('quantity');
                $view->with('cartCount', $count);
            } else {
                $view->with('cartCount', 0);
            }
        });
    }
}
