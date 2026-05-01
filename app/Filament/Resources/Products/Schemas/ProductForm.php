<?php

namespace App\Filament\Resources\Products\Schemas;

use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;

class ProductForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Detalles del Producto')
                    ->description('Estos son los detalles del producto que deseas crear o editar.')
                    ->icon('heroicon-o-information-circle')
                    ->components([
                        TextInput::make('code')
                            ->label('Código')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(50),

                        TextInput::make('name')
                            ->label('Nombre')
                            ->required()
                            ->maxLength(120),

                        TextInput::make('price')
                            ->label('Precio')
                            ->required()
                            ->numeric()
                            ->minValue(0.01),

                        Toggle::make('active')
                            ->label('Activo')
                            ->default(true),
                    ])
                    ->columnSpan('full'),
            ]);
    }
}
