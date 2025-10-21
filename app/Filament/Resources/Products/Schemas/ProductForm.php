<?php

namespace App\Filament\Resources\Products\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ProductForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make()
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                Select::make('category_id')
                                    ->label('Kategori')
                                    ->relationship('category', 'name')
                                    ->required()
                                    ->extraAttributes(['class' => 'rounded-xl py-3']),

                                TextInput::make('name')
                                    ->label('Nama Produk')
                                    ->placeholder('Masukkan nama produk...')
                                    ->required()
                                    ->autofocus()
                                    ->extraAttributes(['class' => 'rounded-xl py-3']),

                                Textarea::make('description')
                                    ->label('Deskripsi')
                                    ->rows(4)
                                    ->columnSpanFull()
                                    ->extraAttributes(['class' => 'rounded-xl py-3']),

                                TextInput::make('price')
                                    ->label('Harga')
                                    ->numeric()
                                    ->prefix('Rp')
                                    ->required()
                                    ->extraAttributes(['class' => 'rounded-xl py-3']),

                                TextInput::make('stok')
                                    ->label('Stok')
                                    ->numeric()
                                    ->required()
                                    ->extraAttributes(['class' => 'rounded-xl py-3']),

                                TextInput::make('weight')
                                    ->label('Berat (gram)')
                                    ->numeric()
                                    ->required()
                                    ->extraAttributes(['class' => 'rounded-xl py-3']),

                                Select::make('status')
                                    ->label('Status Produk')
                                    ->options([
                                        'aktif' => 'Aktif',
                                        'non aktif' => 'Non Aktif'
                                    ])
                                    ->required()
                                    ->extraAttributes(['class' => 'rounded-xl py-3']),
                            ]),
                    ])
                    ->extraAttributes(['class' => 'rounded-xl'])
                    ->columnSpanFull()
                    ->collapsible()
                    ->compact(),
            ]);
    }
}
