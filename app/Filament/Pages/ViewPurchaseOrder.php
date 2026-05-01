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

    protected static ?string $navigationLabel = 'View Order';

    protected static UnitEnum|string|null $navigationGroup = 'Purchase Orders';

    protected static ?int $navigationSort = 3;

    protected static bool $shouldRegisterNavigation = false;

    protected static ?string $slug = 'purchase-orders/{orderNumber}';

    protected string $view = 'filament.pages.view-purchase-order';

    public string $orderNumber = '';

    /** @var array<string, mixed>|null */
    public ?array $order = null;

    public function mount(string $orderNumber, OrdersApiClient $ordersApiClient): void
    {
        $this->orderNumber = $orderNumber;

        $this->loadOrder($ordersApiClient);
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
                ->title('Could not load purchase order')
                ->danger()
                ->send();

            $this->order = null;
        }
    }

    public function getTitle(): string
    {
        return 'Purchase Order Details';
    }
}
