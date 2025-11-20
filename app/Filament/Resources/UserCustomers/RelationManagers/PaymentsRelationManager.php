<?php

namespace App\Filament\Resources\UserCustomers\RelationManagers;


use Filament\Tables\Table;
use Filament\Schemas\Schema;
use Filament\Actions\EditAction;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Forms\Components\Select;
use Filament\Actions\DeleteBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ToggleButtons;
use Filament\Resources\RelationManagers\RelationManager;

class PaymentsRelationManager extends RelationManager
{
    protected static string $relationship = 'payments';

    protected static ?string $recordTitleAttribute = 'method';



    public function form(Schema $schema): Schema
    {
        return $schema->components([

            Select::make('order_id')

                ->relationship('order', 'invoice_number')
                ->searchable()
                ->disabled()
                ->required(),

            TextInput::make('transaction_code')
                ->label('Transaction Code')
                ->maxLength(100)
                ->required(),

            TextInput::make('amount')
                ->label('Amount')
                ->numeric()
                ->required(),

            ToggleButtons::make('method')
                ->label('Payment Method')
                ->options([
                    'bank_transfer' => 'Bank Transfer',
                    'e_wallet' => 'E-Wallet',
                    'cash_on_delivery' => 'Cash on Delivery',
                ])
                ->required()
                ->inline(),

            TextColumn::make('payment_status')
                ->label('Status')
                ->badge()
                ->color(fn(string $state): string => match ($state) {
                    'Berhasil' => 'success',
                    'Pending' => 'warning',
                    'Gagal' => 'danger',
                    'Expired' => 'gray',
                    'Refound' => 'info',
                    default => 'gray',
                })
                ->sortable()
                ->searchable(),


            TextInput::make('payment_time')
                ->label('Payment Time')
                ->type('datetime-local')
                ->nullable(),
        ]);
    }


    public function table(Table $table): Table
    {
        return $table->columns([
            TextColumn::make('order.order_number')
                ->label('Order Number')
                ->sortable()
                ->searchable(),

            TextColumn::make('method')
                ->label('Payment Method')
                ->sortable()
                ->searchable(),

            TextColumn::make('transaction_code')
                ->label('Transaction Code')
                ->sortable()
                ->searchable(),

            TextColumn::make('amount')
                ->label('Amount')
                ->money('RP')
                ->sortable(),

            TextColumn::make('payment_status')
                ->label('Payment Status')
                ->badge()
                ->color(fn(string $state): string => match ($state) {
                    'Berhasil' => 'success',
                    'Pending' => 'warning',
                    'Gagal' => 'danger',
                    'Expired' => 'gray',
                    'Refound' => 'info',
                })
                ->sortable()
                ->searchable(),

            TextColumn::make('payment_time')
                ->label('Payment Time')
                ->dateTime()
                ->sortable(),
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
