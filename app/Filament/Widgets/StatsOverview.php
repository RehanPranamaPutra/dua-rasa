<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use App\Models\Product;
use App\Models\UserCustomer;
use Carbon\Carbon;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    use InteractsWithPageFilters;

    protected static ?int $sort = 0;

    protected function getStats(): array
    {
        // Format angka ribuan
        $formatNumber = fn($number) => number_format((int)$number, 0, ',', '.');

        $now = Carbon::now();

        // ================= CUSTOMERS =================
        $totalCustomers = UserCustomer::count();
        $thisMonthCustomer = UserCustomer::whereMonth('created_at', $now->month)
            ->whereYear('created_at', $now->year)
            ->count();
        $lastMonthCustomer = UserCustomer::whereMonth('created_at', $now->copy()->subMonth()->month)
            ->whereYear('created_at', $now->copy()->subMonth()->year)
            ->count();

        // Hitung persentase customer dari total
        $customerPercent = $totalCustomers > 0
            ? ($thisMonthCustomer / $totalCustomers) * 100
            : 0;

        // Tentukan warna berdasarkan persentase
        if ($customerPercent >= 15) {
            $customerColor = 'success';
            $customerIcon = 'heroicon-m-arrow-trending-up';
        } elseif ($customerPercent >= 8) {
            $customerColor = 'warning';
            $customerIcon = 'heroicon-m-arrow-trending-up';
        } else {
            $customerColor = 'danger';
            $customerIcon = 'heroicon-m-arrow-trending-down';
        }

        // ================= ORDERS =================
        $totalOrders = Order::count();
        $thisMonthOrder = Order::whereMonth('created_at', $now->month)
            ->whereYear('created_at', $now->year)
            ->count();
        $lastMonthOrder = Order::whereMonth('created_at', $now->copy()->subMonth()->month)
            ->whereYear('created_at', $now->copy()->subMonth()->year)
            ->count();

        // Hitung persentase order dari total
        $orderPercent = $totalOrders > 0
            ? ($thisMonthOrder / $totalOrders) * 100
            : 0;

        // Tentukan warna berdasarkan persentase
        if ($orderPercent >= 15) {
            $orderColor = 'success';
            $orderIcon = 'heroicon-m-arrow-trending-up';
        } elseif ($orderPercent >= 8) {
            $orderColor = 'warning';
            $orderIcon = 'heroicon-m-arrow-trending-up';
        } else {
            $orderColor = 'danger';
            $orderIcon = 'heroicon-m-arrow-trending-down';
        }

        // ================= REVENUE =================
        $totalRevenue = Order::sum('total_price');
        $thisMonthRevenue = Order::whereMonth('created_at', $now->month)
            ->whereYear('created_at', $now->year)
            ->sum('total_price');
        $lastMonthRevenue = Order::whereMonth('created_at', $now->copy()->subMonth()->month)
            ->whereYear('created_at', $now->copy()->subMonth()->year)
            ->sum('total_price');

        // Hitung persentase revenue dari total
        $revenuePercent = $totalRevenue > 0
            ? ($thisMonthRevenue / $totalRevenue) * 100
            : 0;

        // Tentukan warna berdasarkan persentase
        if ($revenuePercent >= 15) {
            $revenueColor = 'success';
            $revenueIcon = 'heroicon-m-arrow-trending-up';
        } elseif ($revenuePercent >= 8) {
            $revenueColor = 'warning';
            $revenueIcon = 'heroicon-m-arrow-trending-up';
        } else {
            $revenueColor = 'danger';
            $revenueIcon = 'heroicon-m-arrow-trending-down';
        }

        // ================= PRODUCTS =================
        $totalProducts = Product::count();

        return [
            // ================= CUSTOMERS =================
            Stat::make('Total Customers', $formatNumber($totalCustomers))
                ->description(
                    number_format($customerPercent, 1) . '% dari total bulan ini (' . $formatNumber($thisMonthCustomer) . ')'
                )
                ->descriptionIcon($customerIcon)
                ->chart([7, 3, 4, 5, 6, 3, 5])
                ->color($customerColor),

            // ================= ORDERS =================
            Stat::make('Total Orders', $formatNumber($totalOrders))
                ->description(
                    number_format($orderPercent, 1) . '% dari total bulan ini (' . $formatNumber($thisMonthOrder) . ')'
                )
                ->descriptionIcon($orderIcon)
                ->chart([5, 6, 4, 3, 5, 7, 9])
                ->color($orderColor),

            // ================= REVENUE =================
            Stat::make('Total Revenue', 'Rp ' . $formatNumber($totalRevenue))
                ->description(
                    number_format($revenuePercent, 1) . '% dari total bulan ini (Rp ' . $formatNumber($thisMonthRevenue) . ')'
                )
                ->descriptionIcon($revenueIcon)
                ->chart([4, 6, 7, 8, 6, 7, 9])
                ->color($revenueColor),

            // ================= PRODUCTS =================
            Stat::make('Total Products', $formatNumber($totalProducts))
                ->description('Total produk aktif')
                ->descriptionIcon('heroicon-m-cube')
                ->color('primary'),
        ];
    }
}
