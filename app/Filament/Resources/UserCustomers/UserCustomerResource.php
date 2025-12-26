<?php

namespace App\Filament\Resources\UserCustomers;

use UnitEnum;
use BackedEnum;
use Filament\Tables\Table;
use App\Models\UserCustomer;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use App\Filament\Resources\UserCustomers\Pages\EditUserCustomer;
use App\Filament\Resources\UserCustomers\Pages\ListUserCustomers;
use App\Filament\Resources\UserCustomers\Pages\CreateUserCustomer;
use App\Filament\Resources\UserCustomers\Schemas\UserCustomerForm;
use App\Filament\Resources\UserCustomers\Tables\UserCustomersTable;
use App\Filament\Resources\UserCustomers\Schemas\UserCustomerInfolist;
use App\Filament\Resources\UserCustomers\RelationManagers\DeliveryRelationManager;
use App\Filament\Resources\UserCustomers\RelationManagers\PaymentsRelationManager;
use App\Filament\Resources\UserCustomers\RelationManagers\AddressesRelationManager;

class UserCustomerResource extends Resource
{
    protected static ?string $model = UserCustomer::class;

    protected static string|UnitEnum|null $navigationGroup = 'User';
    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-users';
    protected static ?string $recordTitleAttribute = 'name';

    protected static ?string $navigationLabel = 'Cutomers';
    protected static ?string $pluralModelLabel = 'Cutomers';
    protected static ?string $modelLabel = 'Cutomers';

    public static function form(Schema $schema): Schema
    {
        return UserCustomerForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return UserCustomerInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return UserCustomersTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            AddressesRelationManager::class,
            PaymentsRelationManager::class,

        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListUserCustomers::route('/'),
            'create' => CreateUserCustomer::route('/create'),
            'edit' => EditUserCustomer::route('/{record}/edit'),
        ];
    }
}
