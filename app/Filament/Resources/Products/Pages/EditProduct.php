<?php

namespace App\Filament\Resources\Products\Pages;

use App\Filament\Resources\Products\ProductResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;
use Filament\Actions\Action;

class EditProduct extends EditRecord
{
    protected static string $resource = ProductResource::class;

    protected function getSavedNotificationTitle(): ?string
    {
        return ('Producto actualizado');
    }

    protected function getRedirectUrl(): ?string
    {
        return $this->getResource()::getUrl('view', ['record' => $this->record]);
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('back')
                ->label('Volver')
                ->url($this->getResource()::getUrl('index')),

            ViewAction::make()
                ->label('Ver'),

            DeleteAction::make()
                ->label('Eliminar')
                ->successNotificationTitle('Producto eliminado')
                ->failureNotificationTitle('No se puede eliminar el producto')
                ->visible(fn () => auth()->user()?->isAdmin() ?? false),
        ];
    }
}
