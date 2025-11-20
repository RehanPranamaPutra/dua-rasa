<?php

namespace App\Filament\Resources\Orders\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class OrderInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('customer_id')
                    ->numeric(),
                TextEntry::make('address_id')
                    ->numeric(),
                TextEntry::make('invoice_number'),
                TextEntry::make('total_price')
                    ->numeric(),
                TextEntry::make('shipping_cost')
                    ->numeric(),
                TextEntry::make('order_status'),
                TextEntry::make('created_at')
                    ->dateTime(),
                TextEntry::make('updated_at')
                    ->dateTime(),
            ]);
    }
}
