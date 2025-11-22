<?php

namespace App\Filament\Resources\Shop\Orders\Widgets;

use App\Models\Order;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Filament\Resources\Orders\Pages\ListOrders;
use Filament\Widgets\Concerns\InteractsWithPageTable;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;

class OrderStats extends BaseWidget
{
    use InteractsWithPageTable;


    protected ?string $pollingInterval = null;


    protected function getTablePage(): string
    {
        return ListOrders::class;
    }

    protected function getStats(): array
    {
        $orderData = Trend::model(Order::class)
            ->between(
                start: now()->subYear(),
                end: now(),
            )
            ->perMonth()
            ->count();

        return [
            Stat::make('Pesnan', $this->getPageTableQuery()->count())
                ->chart(
                    $orderData
                        ->map(fn (TrendValue $value) => $value->aggregate)
                        ->toArray()
                ),
            Stat::make('Buka pesanan', $this->getPageTableQuery()->whereIn('order_status', ['open', 'processing'])->count()),
            Stat::make('Harga rata-rata', number_format((float) $this->getPageTableQuery()->avg('total_price'), 2)),
        ];
    }
}
