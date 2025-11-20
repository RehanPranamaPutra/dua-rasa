<?php

namespace App\Filament\Resources\Orders\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class OrderForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('customer_id')
                    ->required()
                    ->numeric(),
                TextInput::make('address_id')
                    ->required()
                    ->numeric(),
                TextInput::make('invoice_number')
                    ->required(),
                TextInput::make('total_price')
                    ->required()
                    ->numeric(),
                TextInput::make('shipping_cost')
                    ->required()
                    ->numeric(),
                TextInput::make('order_status')
                    ->required(),
            ]);
    }
}
