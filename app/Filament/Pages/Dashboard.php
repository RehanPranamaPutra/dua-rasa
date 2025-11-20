<?php

namespace App\Filament\Pages;


use App\Filament\Widgets\OrderChart;
use App\Filament\Widgets\LatestOrders;
use App\Filament\Widgets\RevenueChart;
use App\Filament\Widgets\StatsOverview;
use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
    protected static ?string $navigationLabel = 'Dashboard';

    public function getWidgets(): array
    {
        return [
            StatsOverview::class,
            OrderChart::class,
            RevenueChart::class,
            LatestOrders::class,
        ];
    }

}
