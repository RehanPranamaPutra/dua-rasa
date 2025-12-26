<?php

namespace App\Filament\Resources\Orders\RelationManagers;

use App\Models\Payment;
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
use Filament\Forms\Components\DateTimePicker;
use Filament\Resources\RelationManagers\RelationManager;

class PaymentsRelationManager extends RelationManager
{
    protected static string $relationship = 'payment';

    protected static ?string $recordTitleAttribute = 'transaction_code';

    public function form(Schema $schema): Schema
    {
        return $schema->components([
            // Tampilkan invoice order read-only
            TextInput::make('order_invoice')
                ->label('Nomor Faktur')
                ->disabled()
                ->default(fn(?Payment $record) => $record?->order?->invoice_number),

            TextInput::make('transaction_code')
                ->label('Code Transaksi')
                ->maxLength(100)
                ->required(),

            TextInput::make('amount')
                ->label('Jumlah')
                ->numeric()
                ->required(),

            ToggleButtons::make('method')
                ->label('Metode Pembayaran')
                ->options([
                    'bank_transfer' => 'Bank Transfer',
                    'e_wallet' => 'E-Wallet',
                    'cash_on_delivery' => 'Cash on Delivery',
                ])
                ->required()
                ->inline(),

            Select::make('payment_status')
                ->label('Status')
                ->options([
                    'Pending' => 'Pending',
                    'Berhasil' => 'Berhasil',
                    'Gagal' => 'Gagal',
                    'Expired' => 'Expired',
                    'Refound' => 'Refound',
                ])
                ->required(),

            DateTimePicker::make('payment_time')
                ->label('Waktu Pembayaran')
                ->seconds(false)
                ->nullable(),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table->columns([
            TextColumn::make('order.invoice_number')
                ->label('Nomor Faktur')
                ->sortable()
                ->searchable(),

            TextColumn::make('method')
                ->label('Metode Pembayaran')
                ->sortable()
                ->searchable(),

            TextColumn::make('transaction_code')
                ->label('Code Transaksi')
                ->sortable()
                ->searchable(),

            TextColumn::make('amount')
                ->label('Jumlah')
                ->money('   ', true)
                ->sortable(),

            TextColumn::make('payment_status')
                ->label('Status')
                ->badge()
                ->colors([
                    'success' => 'Berhasil',
                    'warning' => 'Pending',
                    'danger' => 'Gagal',
                    'gray' => 'Expired',
                    'info' => 'Refound',
                ])
                ->sortable()
                ->searchable(),

            TextColumn::make('payment_time')
                ->label('Waktu Pembayaran')
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
