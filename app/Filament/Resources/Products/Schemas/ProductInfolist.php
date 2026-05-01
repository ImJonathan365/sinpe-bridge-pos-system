<?php

namespace App\Filament\Resources\Products\Schemas;

use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\IconEntry;

class ProductInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Detalles del Producto')
                    ->description('Estos son los detalles del producto.')
                    ->icon('heroicon-o-information-circle')
                    ->components([
                        TextEntry::make('code')
                            ->label('Código'),

                        TextEntry::make('name')
                            ->label('Nombre'),

                        TextEntry::make('price')
                            ->label('Precio')
                            ->money('USD'),

                        IconEntry::make('active')
                            ->label('Activo')
                            ->boolean(),
                    ])
                    ->columnSpan('full'),
            ]);
    }
}
