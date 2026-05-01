<?php

namespace App\Filament\Pages;

use App\Services\OrdersApiClient;
use BackedEnum;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use UnitEnum;

class ListPurchaseOrders extends Page
{
    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-queue-list';

    protected static ?string $navigationLabel = 'List Orders';

    protected static UnitEnum|string|null $navigationGroup = 'Purchase Orders';

    protected static ?int $navigationSort = 2;

    protected string $view = 'filament.pages.list-purchase-orders';

    /**
     * @var array<int, array<string, mixed>>
     */
    public array $orders = [];

    public function mount(OrdersApiClient $ordersApiClient): void
    {
        $this->loadOrders($ordersApiClient);
    }

    public function refreshOrders(OrdersApiClient $ordersApiClient): void
    {
        $this->loadOrders($ordersApiClient);
    }

    protected function loadOrders(OrdersApiClient $ordersApiClient): void
    {
        try {
            $response = $ordersApiClient->listOrders();
            $this->orders = is_array($response) ? $response : [];
        } catch (\Throwable $exception) {
            report($exception);
            $this->orders = [];

            Notification::make()
                ->title('Could not load orders from API')
                ->danger()
                ->send();
        }
    }
}
