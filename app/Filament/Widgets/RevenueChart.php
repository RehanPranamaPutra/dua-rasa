<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use Carbon\Carbon;
use Filament\Support\RawJs;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class RevenueChart extends ChartWidget
{
    protected ?string $heading = 'Revenue Chart';

    protected static ?int $sort = 1;

    protected function getData(): array
    {
        $currentYear = Carbon::now()->year;

        $revenueByMonth = Order::select(
                DB::raw('MONTH(created_at) as month'),
                DB::raw('SUM(total_price) as total')
            )
            ->whereYear('created_at', $currentYear)
            ->groupBy('month')
            ->pluck('total', 'month')
            ->toArray();

        $monthlyRevenue = [];
        for ($i = 1; $i <= 12; $i++) {
            // Pastikan cast ke int/float agar ECharts tidak error
            $monthlyRevenue[] = isset($revenueByMonth[$i]) ? (float) $revenueByMonth[$i] : 0;
        }

        return [
            'datasets' => [
                [
                    'name'      => 'Revenue',
                    'type'      => 'line',
                    'data'      => $monthlyRevenue,
                    'smooth'    => true,
                    'areaStyle' => (object) [], // supaya area fill aktif
                ],
            ],
            'labels' => [
                'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun',
                'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec',
            ],
            

        ];
    }

    protected function getOptions(): array
    {
        // Formatter ECharts harus dikirim sebagai RawJs agar tidak di-json-encode
        $formatter = RawJs::from(<<<'JS'
function (value) {
    // value bisa berupa angka besar -> kita singkat sesuai permintaan
    var abs = Math.abs(value);
    if (abs >= 1000000000) {
        return (value / 1000000000).toFixed(1).replace('.0','') + ' M';
    }
    if (abs >= 1000000) {
        return (value / 1000000).toFixed(1).replace('.0','') + ' jt';
    }
    if (abs >= 1000) {
        return (value / 1000).toFixed(1).replace('.0','') + ' rb';
    }
    return value;
}
JS
        );

        return [
            // konfigurasi ECharts untuk axis
            'yAxis' => [
                [
                    'type' => 'value',
                    'axisLabel' => [
                        'formatter' => $formatter,
                    ],
                    'splitLine' => [
                        'show' => true,
                    ],
                ],
            ],
            'xAxis' => [
                [
                    'type' => 'category',
                    'axisLabel' => [
                        'rotate' => 0,
                    ],
                ],
            ],
            'tooltip' => [
                'trigger' => 'axis',
                'formatter' => RawJs::from(<<<'JS'
function (params) {
    // params adalah array; kita ambil pertama
    var p = Array.isArray(params) ? params[0] : params;
    var val = p.value ?? p.data ?? 0;
    // format angka rp singkat
    var abs = Math.abs(val);
    var formatted;
    if (abs >= 1000000000) {
        formatted = (val / 1000000000).toFixed(1).replace('.0','') + ' M';
    } else if (abs >= 1000000) {
        formatted = (val / 1000000).toFixed(1).replace('.0','') + ' jt';
    } else if (abs >= 1000) {
        formatted = (val / 1000).toFixed(1).replace('.0','') + ' rb';
    } else {
        formatted = val;
    }
    return p.axisValueLabel + ' : Rp ' + formatted;
}
JS
                ),
            ],
            // sedikit padding agar grafik tidak menempel
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
        // Filament v4 menggunakan ECharts, tipe diset di dataset; tetap gunakan 'line'
        return 'line';
    }
}
