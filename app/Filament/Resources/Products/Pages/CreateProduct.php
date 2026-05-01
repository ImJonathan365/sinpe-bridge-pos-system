<?php

namespace App\Filament\Resources\Products\Pages;

use App\Filament\Resources\Products\ProductResource;
use Filament\Resources\Pages\CreateRecord;
use Filament\Actions\Action;

class CreateProduct extends CreateRecord
{
    protected static string $resource = ProductResource::class;

    protected function getCreatedNotificationTitle(): ?string
    {
        return ('Producto creado');
    }

    public function getHeaderActions(): array
    {
        return [
            Action::make('back')
                ->label(('Volver'))
                ->url($this->getResource()::getUrl('index')),
        ];
    }

}
