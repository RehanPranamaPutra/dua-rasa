<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use App\Models\Product;
use App\Models\UserCustomer;
use Carbon\Carbon;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected static ?int $sort = 0;

    protected function getStats(): array
    {
        $formatNumber = fn($n) => number_format((int)$n, 0, ',', '.');

        $now = Carbon::now();
        $lastMonth = $now->copy()->subMonth();

        /*
        |--------------------------------------------------------------------------
        | CUSTOMERS
        |--------------------------------------------------------------------------
        */
        $thisMonth = UserCustomer::whereMonth('created_at', $now->month)->count();
        $lastMonthValue = UserCustomer::whereMonth('created_at', $lastMonth->month)->count();

        $diffCustomer = $thisMonth - $lastMonthValue;
        $percentCustomer = $lastMonthValue > 0 ? ($diffCustomer / $lastMonthValue) * 100 : 100;

        [$customerColor, $customerIcon] = $percentCustomer >= 0
            ? ['success', 'heroicon-m-arrow-trending-up']
            : ['danger', 'heroicon-m-arrow-trending-down'];

        /*
        |--------------------------------------------------------------------------
        | ORDERS
        |--------------------------------------------------------------------------
        */
        $thisMonthOrder = Order::whereMonth('created_at', $now->month)->count();
        $lastMonthOrder = Order::whereMonth('created_at', $lastMonth->month)->count();

        $diffOrder = $thisMonthOrder - $lastMonthOrder;
        $percentOrder = $lastMonthOrder > 0 ? ($diffOrder / $lastMonthOrder) * 100 : 100;

        [$orderColor, $orderIcon] = $percentOrder >= 0
            ? ['success', 'heroicon-m-arrow-trending-up']
            : ['danger', 'heroicon-m-arrow-trending-down'];

        /*
        |--------------------------------------------------------------------------
        | REVENUE
        |--------------------------------------------------------------------------
        */
        $thisMonthRevenue = Order::whereMonth('created_at', $now->month)->sum('total_price');
        $lastMonthRevenue = Order::whereMonth('created_at', $lastMonth->month)->sum('total_price');

        $diffRevenue = $thisMonthRevenue - $lastMonthRevenue;
        $percentRevenue = $lastMonthRevenue > 0 ? ($diffRevenue / $lastMonthRevenue) * 100 : 100;

        [$revColor, $revIcon] = $percentRevenue >= 0
            ? ['success', 'heroicon-m-arrow-trending-up']
            : ['danger', 'heroicon-m-arrow-trending-down'];

        /*
        |--------------------------------------------------------------------------
        | TOTAL PRODUCTS
        |--------------------------------------------------------------------------
        */
        $totalProducts = Product::count();

        /*
        |--------------------------------------------------------------------------
        | RETURN FINAL STAT WIDGETS
        |--------------------------------------------------------------------------
        */
        return [
            Stat::make('Customers Bulan Ini', $formatNumber($thisMonth))
                ->description(($diffCustomer >= 0 ? '+' : '') . $formatNumber($diffCustomer) . " dari bulan lalu")
                ->descriptionIcon($customerIcon)
                ->chart([7, 3, 4, 6, 8, 9, 12])
                ->color($customerColor),

            Stat::make('Orders Bulan Ini', $formatNumber($thisMonthOrder))
                ->description(($diffOrder >= 0 ? '+' : '') . $formatNumber($diffOrder) . " dari bulan lalu")
                ->descriptionIcon($orderIcon)
                ->chart([5, 6, 7, 8, 10, 9, 11])
                ->color($orderColor),

            Stat::make('Revenue Bulan Ini', 'Rp ' . $formatNumber($thisMonthRevenue))
                ->description(($diffRevenue >= 0 ? '+' : '') . 'Rp ' . $formatNumber($diffRevenue) . " dari bulan lalu")
                ->descriptionIcon($revIcon)
                ->chart([3, 5, 6, 7, 10, 12, 15])
                ->color($revColor),

            Stat::make('Total Produk', $formatNumber($totalProducts))
                ->description('Total produk terdaftar')
                ->descriptionIcon('heroicon-m-cube')
                ->color('primary'),
        ];
    }
}
