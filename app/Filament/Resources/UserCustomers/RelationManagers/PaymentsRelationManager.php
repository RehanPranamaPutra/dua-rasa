<?php

namespace App\Filament\Resources\UserCustomers\RelationManagers;

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
use Filament\Resources\RelationManagers\RelationManager;

class PaymentsRelationManager extends RelationManager
{
    protected static string $relationship = 'payments'; // relasi di UserCustomer

    protected static ?string $recordTitleAttribute = 'transaction_code';

    public function form(Schema $schema): Schema
    {
        return $schema->components([
            Select::make('order_id')
                ->label('Pesanan')
                ->relationship('order', 'invoice_number')
                ->options(function ($livewire) {
                    // Batasi order hanya milik customer ini
                    return $livewire->ownerRecord->orders()->pluck('invoice_number', 'id');
                })
                ->searchable()
                ->required(),

            TextInput::make('transaction_code')
                ->label('Kode Transaksi')
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
                ->label('Status Pemesanan')
                ->options([
                    'Berhasil' => 'Berhasil',
                    'Pending' => 'Pending',
                    'Gagal' => 'Gagal',
                    'Expired' => 'Expired',
                    'Refound' => 'Refound',
                ])
                ->required(),

            TextInput::make('payment_time')
                ->label('Waktu Pembayaran')
                ->type('datetime-local')
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
                ->label('Kode Transaksi')
                ->sortable()
                ->searchable(),

            TextColumn::make('amount')
                ->label('Jumlah')
                ->money('IDR', true)
                ->sortable(),

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

            TextColumn::make('payment_time')
                ->label('Waktu Pembayaran')
                ->dateTime()
                ->sortable(),
        ])
        ->filters([])
        ->headerActions([
            CreateAction::make()
                ->after(function () {
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
