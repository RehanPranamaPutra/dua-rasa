<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use Carbon\Carbon;
use Filament\Support\RawJs;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class OrderChart extends ChartWidget
{
    protected ?string $heading = 'Order Chart';

    protected static ?int $sort = 1;

    protected function getData(): array
    {
        $year = Carbon::now()->year;

        // Ambil jumlah order per bulan
        $orderCounts = Order::select(
                DB::raw('MONTH(created_at) AS month'),
                DB::raw('COUNT(*) AS total')
            )
            ->whereYear('created_at', $year)
            ->groupBy('month')
            ->pluck('total', 'month')
            ->toArray();

        // Isi data bulan kosong dengan 0
        $monthlyOrders = [];
        for ($i = 1; $i <= 12; $i++) {
            $monthlyOrders[] = isset($orderCounts[$i]) ? (int) $orderCounts[$i] : 0;
        }

        return [
            'datasets' => [
                [
                    'name'      => 'Orders',
                    'type'      => 'line',
                    'data'      => $monthlyOrders,
                    'smooth'    => true,
                    'areaStyle' => (object) [],
                ],
            ],
            'labels' => [
                'Jan','Feb','Mar','Apr','May','Jun',
                'Jul','Aug','Sep','Oct','Nov','Dec'
            ],
        ];
    }

    protected function getOptions(): array
    {
        return [
            'yAxis' => [
                [
                    'type' => 'value',
                    'axisLabel' => [
                        'formatter' => RawJs::from(<<<'JS'
function (value) {
    return value; // untuk jumlah order cukup angka biasa
}
JS),
                    ],
                    'splitLine' => ['show' => true],
                ],
            ],
            'xAxis' => [
                [
                    'type' => 'category',
                ],
            ],
            'tooltip' => [
                'trigger' => 'axis',
                'formatter' => RawJs::from(<<<'JS'
function (params) {
    var p = params[0];
    return p.axisValueLabel + ": " + p.value + " orders";
}
JS),
            ],
            'grid' => [
                'left' => '6%',
                'right' => '6%',
                'bottom' => '10%',
                'containLabel' => true,
            ],
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
