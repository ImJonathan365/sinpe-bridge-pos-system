<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use App\Services\OrdersApiClient;
use BackedEnum;
use Filament\Notifications\Notification;
use UnitEnum;

class ViewPurchaseOrder extends Page
{
    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-eye';

    protected static ?string $navigationLabel = 'Ver orden';

    protected static UnitEnum|string|null $navigationGroup = 'Ordenes de compra';

    protected static ?int $navigationSort = 3;

    protected static bool $shouldRegisterNavigation = false;

    protected static ?string $slug = 'purchase-orders/{orderNumber}';

    protected string $view = 'filament.pages.view-purchase-order';

    public string $orderNumber = '';

    /** @var array<string, mixed>|null */
    public ?array $order = null;

    public bool $showTokenModal = false;

    public function mount(string $orderNumber, OrdersApiClient $ordersApiClient): void
    {
        $this->orderNumber = $orderNumber;

        $this->loadOrder($ordersApiClient);

        if (request()->query('showToken') === '1') {
            $this->showTokenModal = true;
        }
    }

    public function openTokenModal(): void
    {
        $this->showTokenModal = true;
    }

    public function closeTokenModal(): void
    {
        $this->showTokenModal = false;
    }

    public function refreshOrder(OrdersApiClient $ordersApiClient): void
    {
        $this->loadOrder($ordersApiClient);
    }

    protected function loadOrder(OrdersApiClient $ordersApiClient): void
    {
        try {
            $this->order = $ordersApiClient->getOrder($this->orderNumber);
        } catch (\Throwable $exception) {
            report($exception);

            Notification::make()
                ->title('No se pudo cargar la orden de compra')
                ->danger()
                ->send();

            $this->order = null;
        }
    }

    public function getTitle(): string
    {
        return 'Detalle de orden de compra';
    }
}
