<?php

namespace App\Filament\Resources\Orders\Schemas;

use App\Models\Order;
use App\Models\Address;
use Filament\Actions\Action;
use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use Filament\Schemas\Components\Group;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Utilities\Set;

class OrderForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Group::make()
                    ->schema([
                        // Section detail order (read-only)
                        Section::make('Order Details')
                            ->schema(static::getDetailsComponents())
                            ->columns(2),

                        // Section item order (Repeater)
                        Section::make('Order Items')
                            ->afterHeader([
                                Action::make('reset')
                                    ->modalHeading('Are you sure?')
                                    ->modalDescription('All existing items will be removed from the order.')
                                    ->requiresConfirmation()
                                    ->color('danger')
                                    ->action(fn(Set $set) => $set('details', [])),
                            ])
                            ->schema([
                                static::getItemsRepeater(),
                            ]),
                    ])
                    ->columnSpan(['lg' => fn(?Order $record) => $record === null ? 3 : 2]),

                // Section metadata (created/updated)
                Section::make()
                    ->schema([
                        TextEntry::make('created_at')
                            ->label('Tanggal Pesanan')
                            ->state(fn(?Order $record): ?string => $record?->created_at?->diffForHumans()),

                        TextEntry::make('updated_at')
                            ->label('Terakhir Diperbarui')
                            ->state(fn(?Order $record): ?string => $record?->updated_at?->diffForHumans()),
                    ])
                    ->columnSpan(['lg' => 1])
                    ->hidden(fn(?Order $record) => $record === null),
            ])
            ->columns(3);
    }

    protected static function getDetailsComponents(): array
    {

        return [
            Select::make('customer_id')
                ->label('Nama Customer')
                ->relationship('customer', 'name')
                ->searchable()
                ->reactive()
                ->required(),

            Select::make('address_id')
                ->label('Alamat Pengiriman')
                ->relationship('address', 'specific_address')
                ->searchable()
                ->preload()
                ->required(),

            TextInput::make('invoice_number')
                ->label('Invoice Number')
                ->required()
                ->maxLength(100),


            TextInput::make('total_price')
                ->label('Total Harga')
                ->disabled(),

            TextInput::make('shipping_cost')
                ->label('Biaya Pengiriman')
                ->required(),

            Select::make('order_status')
                ->label('Status Pesanan')
                ->options([
                    'pending' => 'Pending',
                    'processing' => 'Processing',
                    'completed' => 'Completed',
                    'cancelled' => 'Cancelled',
                ])
                ->required(),
        ];
    }

    public static function getItemsRepeater(): Repeater
    {
        return Repeater::make('details')
            ->label('Item Pesanan')
            ->relationship('details')
            ->columns(4)
            ->reactive()
            ->afterStateUpdated(function ($state, callable $set, callable $get) {
                // Hitung total_price parent dari semua detail
                $total = collect($state)->sum(fn($item) => $item['total'] ?? 0);
                $set('total_price', $total);
            })
            ->schema([
                Select::make('product_id')
                    ->label('Nama Produk')
                    ->relationship('product', 'name')
                    ->searchable()
                    ->required()
                    ->reactive()
                    ->afterStateUpdated(function ($state, callable $set, callable $get) {
                        if ($state) {
                            $product = \App\Models\Product::find($state);
                            $price = $product->price ?? 0;
                            $set('price', $price);
                            $amount = (int) $get('amount');
                            $set('total', $price * $amount);
                        }
                    }),

                TextInput::make('price')
                    ->label('Harga')
                    ->numeric()
                    ->required()
                    ->reactive()
                    ->afterStateUpdated(function ($state, callable $set, callable $get) {
                        $price = (int) $state;
                        $amount = (int) $get('amount');
                        $set('total', $price * $amount);
                    }),

                TextInput::make('amount')
                    ->label('Qty')
                    ->numeric()
                    ->required()
                    ->reactive()
                    ->afterStateUpdated(function ($state, callable $set, callable $get) {
                        $price = (int) $get('price');
                        $amount = (int) $state;
                        $set('total', $price * $amount);
                    }),

                TextInput::make('total')
                    ->label('Total')
                    ->disabled()
                    ->numeric(),
            ])
            ->columnSpan('full');
    }
}
