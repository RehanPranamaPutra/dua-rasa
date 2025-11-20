<?php

namespace App\Filament\Resources\UserCustomers\Schemas;

use Filament\Schemas\Schema;
use Filament\Schemas\Components\Grid;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;

class UserCustomerForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make()
                    ->description()
                    ->schema(([
                        Grid::make(1)->schema([
                            TextInput::make('name')
                                ->required(),


                            TextInput::make('email')
                                ->label('Email address')
                                ->required()
                                ->email()
                                ->unique(ignoreRecord: true),


                            TextInput::make('no_hp')
                                ->numeric()
                                ->required(),


                            TextInput::make('password')
                                ->numeric()
                                ->required(),
                        ])
                    ]))
                    ->columnSpanFull()
                    ->collapsible()
                    ->compact(),
            ]);
    }
}
