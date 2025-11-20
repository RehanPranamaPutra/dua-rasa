<?php

namespace App\Filament\Resources\Orders\Pages;

use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Tabs\Tab;
use App\Filament\Resources\Orders\OrderResource;
use Filament\Pages\Concerns\ExposesTableToWidgets;

class ListOrders extends ListRecords
{
    use ExposesTableToWidgets;

    protected static string $resource = OrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }

     protected function getHeaderWidgets(): array
    {
        return OrderResource::getWidgets();
    }

    public function getTabs(): array
    {
        return [
            null => Tab::make('All'),
            'new' => Tab::make()->query(fn ($query) => $query->where('order_status', 'new')),
            'processing' => Tab::make()->query(fn ($query) => $query->where('order_status', 'processing')),
            'shipped' => Tab::make()->query(fn ($query) => $query->where('order_status', 'shipped')),
            'delivered' => Tab::make()->query(fn ($query) => $query->where('order_status', 'delivered')),
            'cancelled' => Tab::make()->query(fn ($query) => $query->where('order_status', 'cancelled')),
        ];
    }
}
