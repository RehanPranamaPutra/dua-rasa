<?php

namespace App\Filament\Resources\Products\Widgets;

use App\Filament\Resources\Products\Pages\ListProducts;
use Filament\Widgets\Concerns\InteractsWithPageTable;
use App\Models\Product;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class ProductStats extends BaseWidget
{

     use InteractsWithPageTable;

    protected ?string $pollingInterval = null;

    protected function getTablePage(): string
    {
        return ListProducts::class;
    }

    protected function getStats(): array
    {
        return [
            Stat::make('Total Produk', $this->getPageTableQuery()->count()),
            Stat::make('stok', $this->getPageTableQuery()->sum('stok')),
            Stat::make('Harga Rata-Rata', number_format((float)$this->getPageTableQuery()->avg('price'), 2)),
        ];
    }
}
