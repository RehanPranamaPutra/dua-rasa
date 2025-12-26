<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use Filament\Tables\Table;
use Filament\Actions\Action;
use Filament\Widgets\TableWidget;
use Filament\Actions\BulkActionGroup;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\Orders\OrderResource;

class LatestOrders extends TableWidget
{
    protected static ?string $heading = 'Latest Orders';
    protected static ?int $sort = 2;
    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                fn(): Builder =>
                Order::query()
                    ->with(['customer', 'address'])
                    ->latest()
            )
            ->columns([
                // CUSTOMER NAME
                TextColumn::make('customer.name')
                    ->label('Customer')
                    ->sortable()
                    ->searchable(),

                // CITY FROM ADDRESS
                TextColumn::make('address.city')
                    ->label('City')
                    ->sortable()
                    ->toggleable(),

                // INVOICE NUMBER (CLICKABLE)
                TextColumn::make('invoice_number')
                    ->label('Invoice')
                    ->searchable()
                    ->url(fn($record) => OrderResource::getUrl('view', ['record' => $record]))
                    ->openUrlInNewTab(),

                // TOTAL PRICE (IDR FORMAT)
                TextColumn::make('total_price')
                    ->label('Total')
                    ->money('idr')
                    ->sortable(),

                // SHIPPING COST (IDR FORMAT)
                TextColumn::make('shipping_cost')
                    ->label('Shipping')
                    ->money('idr')
                    ->sortable()
                    ->toggleable(),

                // ORDER STATUS AS BADGE
                TextColumn::make('order_status')
                    ->badge()
                    ->colors([
                        'warning' => 'pending',
                        'primary' => 'processing',
                        'success' => 'completed',
                        'danger'  => 'cancelled',
                    ])
                    ->sortable(),

                // ORDER DATE
                TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime('d M Y H:i')
                    ->sortable(),
            ])
            ->recordActions([
                Action::make('open')
                    ->url(fn (Order $record): string => OrderResource::getUrl('edit', ['record' => $record])),
            ]);

    }
}
