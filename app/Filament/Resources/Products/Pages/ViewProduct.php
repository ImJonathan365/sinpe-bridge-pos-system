<?php

namespace App\Filament\Resources\Products\Pages;

use App\Filament\Resources\Products\ProductResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;
use Filament\Actions\Action;

class ViewProduct extends ViewRecord
{
    protected static string $resource = ProductResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('back')
                ->label(('Volver'))
                ->url($this->getResource()::getUrl('index')),

            EditAction::make()
                ->label(('Editar'))
                ->visible(fn () => auth()->user()?->isAdmin() ?? false),
        ];
    }
}
