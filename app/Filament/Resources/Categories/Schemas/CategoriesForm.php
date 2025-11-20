<?php

namespace App\Filament\Resources\Categories\Schemas;

use App\Models\Categories;
use Illuminate\Support\Str;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Grid;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;

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
                                ->afterStateUpdated(function (string $operation, $state, callable $set) {
                                    if ($operation === 'create' || $operation === 'edit') {
                                        $set('slug', Str::slug($state));
                                    }
                                })
                                ->extraAttributes(['class' => 'rounded-xl py-3']),


                            TextInput::make('slug')
                               ->disabled()
                                    ->dehydrated()
                                    ->required()
                                    ->maxLength(255)
                                    ->unique(Categories::class, 'slug', ignoreRecord: true),


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
