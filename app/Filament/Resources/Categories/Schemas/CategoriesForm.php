<?php

namespace App\Filament\Resources\Categories\Schemas;

use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Grid;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class CategoriesForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make()
                    ->description()
                    ->schema([
                        Grid::make(2)->schema([
                            TextInput::make('name')
                                ->label('Nama Kategori')
                                ->placeholder('misal: Action, Fantasy')
                                ->required()
                                ->autofocus()
                                ->lazy()
                                ->extraAttributes(['class' => 'rounded-xl py-3']),

                            TextInput::make('slug')
                                ->label('Slug URL')
                                ->placeholder('isilah slug unik untuk kategori ini...')
                                ->required()
                                ->lazy()
                                ->extraAttributes(['class' => 'rounded-xl py-3']),


                        ]),

                        Textarea::make('description')
                            ->label('Deskripsi (opsional)')
                            ->placeholder('Tuliskan deskripsi singkat kategori ini...')
                            ->rows(5)
                            ->columnSpanFull()
                            ->extraAttributes(['class' => 'rounded-xl py-3']),



                    ])

                    ->columnSpanFull()
                    ->collapsible()
                    ->compact(),


            ]);
    }
}
