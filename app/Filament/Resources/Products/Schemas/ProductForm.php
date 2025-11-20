<?php

namespace App\Filament\Resources\Products\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use Filament\Schemas\Components\Grid;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\FileUpload;

class ProductForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Produk')
                    ->description('Lengkapi informasi detail produk')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                Select::make('category_id')
                                    ->label('Kategori')
                                    ->relationship('category', 'name')
                                    ->required()
                                    ->searchable()
                                    ->preload()
                                    ->placeholder('Pilih kategori produk')
                                    ->native(false),

                                Select::make('status')
                                    ->label('Status Produk')
                                    ->options([
                                        'aktif' => 'Aktif',
                                        'non aktif' => 'Non Aktif'
                                    ])
                                    ->default('aktif')
                                    ->required()
                                    ->native(false),
                            ]),

                        TextInput::make('name')
                            ->label('Nama Produk')
                            ->placeholder('Masukkan nama produk...')
                            ->required()
                            ->autofocus()
                            ->maxLength(255)
                            ->columnSpanFull(),

                        Textarea::make('description')
                            ->label('Deskripsi')
                            ->placeholder('Tulis deskripsi produk secara detail...')
                            ->rows(5)
                            ->maxLength(1000)
                            ->columnSpanFull(),

                        Grid::make(3)
                            ->schema([
                                TextInput::make('price')
                                    ->label('Harga')
                                    ->numeric()
                                    ->prefix('Rp')
                                    ->placeholder('0')
                                    ->required()
                                    ->minValue(0)
                                    ->step(1000),

                                TextInput::make('stok')
                                    ->label('Stok')
                                    ->numeric()
                                    ->placeholder('0')
                                    ->required()
                                    ->minValue(0)
                                    ->step(1),

                                TextInput::make('weight')
                                    ->label('Berat (gram)')
                                    ->numeric()
                                    ->placeholder('0')
                                    ->required()
                                    ->minValue(0)
                                    ->suffix('gr')
                                    ->step(1),
                            ]),
                    ])
                    ->columnSpanFull()
                    ->collapsible(),

                Section::make('Media Produk')
                    ->description('Upload gambar produk dengan kualitas terbaik')
                    ->schema([
                        FileUpload::make('image')
                            ->label('Gambar Produk')
                            ->required()
                            ->image()
                            ->directory('products')
                            ->disk('public')
                            ->visibility('public')
                            ->maxSize(10240)
                            ->imageResizeMode('contain')
                            ->imageResizeTargetWidth('1920')
                            ->imageResizeTargetHeight('1080')
                            ->imageCropAspectRatio(null)
                            ->imageResizeUpscale(false)
                            ->imagePreviewHeight('250')
                            ->panelLayout('integrated')
                            ->uploadingMessage('Sedang mengunggah gambar...')
                            ->acceptedFileTypes([
                                'image/jpeg',
                                'image/png',
                                'image/webp',
                                'image/jpg'

                            ])
                            ->fetchFileInformation(false)
                            ->downloadable()
                            ->openable()
                            ->helperText('Maksimal 10MB. Format: JPG, JPEG, PNG, WebP, Resolusi optimal: 1920x1080px')
                            ->columnSpanFull(),
                    ])
                    ->columnSpanFull()
                    ->collapsible(),
            ]);
    }
}
