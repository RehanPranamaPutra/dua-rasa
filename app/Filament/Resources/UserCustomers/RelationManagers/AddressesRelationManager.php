<?php

namespace App\Filament\Resources\UserCustomers\RelationManagers;

use Filament\Tables\Table;
use Filament\Schemas\Schema;
use Filament\Actions\EditAction;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;

class AddressesRelationManager extends RelationManager
{
    protected static string $relationship = 'addresses';

    protected static ?string $recordTitleAttribute = 'specific_address';

    public function form(Schema $schema): Schema
    {
        return $schema->components([

            TextInput::make('customer.name')
                ->label('Customer')
                ->disabled(),

            TextInput::make('customer_name')
                ->label('Nama Customer')
                ->required()
                ->maxLength(100),

            TextInput::make('no_telp')
                ->label('No. Telp')
                ->required()
                ->maxLength(100),

            TextInput::make('province')
                ->label('Provinsi')
                ->required()
                ->maxLength(150),

            TextInput::make('city')
                ->label('Kabupaten/Kota')
                ->required()
                ->maxLength(100),

            TextInput::make('subdistrict')
                ->label('kecamatan')
                ->required()
                ->maxLength(150),

            TextInput::make('village')
                ->label('Desa')
                ->required()
                ->maxLength(150),

            TextInput::make('postal_code')
                ->label('Kode Pos')
                ->required()
                ->maxLength(20),

            TextInput::make('specific_address')
                ->label('Alamat Spesifik')
                ->required()
                ->maxLength(255),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('customer.name')
                    ->label('Customer')
                    ->sortable()
                    ->searchable(),


                TextColumn::make('customer_name')
                    ->label('Nama Customer')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('no_telp')
                    ->label('No Telp')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('province')
                    ->label('Provinsi')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('city')
                    ->label('Kabupaten/kota')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('subdistrict')
                    ->label('Kecamatan')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('village')
                    ->label('Desa')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('postal_code')
                    ->label('Kode Pos')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('specific_address')
                    ->label('Alamat Spesifi')
                    ->sortable()
                    ->searchable(),
            ])
            ->filters([])
            ->headerActions([
                CreateAction::make()
                    ->after(function () {
                        // Reset search dan filters
                        $this->tableSearch = null;
                        $this->tableFilters = [];
                    }),
            ])
            ->defaultSort('created_at', 'desc')
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->groupedBulkActions([
                DeleteBulkAction::make(),
            ]);
    }
}
