<?php

namespace App\Filament\Pages;

use App\Models\Product;
use App\Services\OrdersApiClient;
use BackedEnum;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use UnitEnum;

class CreatePurchaseOrder extends Page
{
    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-document-plus';

    protected static ?string $navigationLabel = 'Crear orden';

    protected static UnitEnum|string|null $navigationGroup = 'Ordenes de compra';

    protected static ?int $navigationSort = 1;

    protected string $view = 'filament.pages.create-purchase-order';

    protected static ?string $title = 'Crear orden de compra';

    public string $order_number = '';

    public string $id_pos = 'POS-TIENDA-01';

    public string $payment_method = 'sinpe';

    public string $ordered_at = '';

    public string $correlation_token = '';

    public string $expires_at = '';

    /**
     * @var array<int, array{product_id: string, quantity: int}>
     */
    public array $items = [];

    /**
     * @var array<int, array{id: int, code: string, name: string, price: float}>
     */
    public array $products = [];

    public function mount(): void
    {
        $this->products = Product::query()
            ->where('active', true)
            ->orderBy('name')
            ->get(['id', 'code', 'name', 'price'])
            ->map(fn (Product $product): array => [
                'id' => (int) $product->id,
                'code' => (string) $product->code,
                'name' => (string) $product->name,
                'price' => (float) $product->price,
            ])
            ->toArray();

        $this->resetForm();
    }

    public function addItem(): void
    {
        $this->items[] = [
            'product_id' => '',
            'quantity' => 1,
        ];
    }

    public function removeItem(int $index): void
    {
        unset($this->items[$index]);
        $this->items = array_values($this->items);
    }

    public function submit(OrdersApiClient $ordersApiClient): void
    {
        $productsById = collect($this->products)->keyBy('id');
        $payloadProducts = [];
        $amount = 0.0;

        foreach ($this->items as $item) {
            $productId = (int) ($item['product_id'] ?? 0);
            $quantity = max(1, (int) ($item['quantity'] ?? 1));
            $selected = $productsById->get($productId);

            if (! $selected) {
                continue;
            }

            $price = (float) $selected['price'];
            $lineTotal = round($price * $quantity, 2);
            $amount += $lineTotal;

            $payloadProducts[] = [
                'code' => $selected['code'],
                'name' => $selected['name'],
                'price' => $price,
                'quantity' => $quantity,
                'line_total' => $lineTotal,
            ];
        }

        if ($payloadProducts === []) {
            Notification::make()
                ->title('Agrega al menos una linea de producto')
                ->danger()
                ->send();

            return;
        }

        $payload = [
            'order_number' => $this->order_number,
            'id_pos' => $this->id_pos,
            'payment_method' => $this->payment_method,
            'ordered_at' => now()->parse($this->ordered_at)->format('Y-m-d\\TH:i:s'),
            'expires_at' => now()->parse($this->expires_at)->format('Y-m-d\\TH:i:s'),
            'correlation_token' => $this->correlation_token,
            'products' => $payloadProducts,
            'amount' => round($amount, 2),
        ];

        try {
            $orderNumber = $this->order_number;

            $ordersApiClient->createOrder($payload);

            Notification::make()
                ->title('Orden enviada al API correctamente')
                ->success()
                ->send();

            $this->redirect(ViewPurchaseOrder::getUrl(['orderNumber' => $orderNumber]) . '?showToken=1', navigate: true);
        } catch (\Throwable $exception) {
            report($exception);

            Notification::make()
                ->title('No se pudo enviar la orden al API')
                ->danger()
                ->send();
        }
    }

    public function getComputedTotalProperty(): float
    {
        $productsById = collect($this->products)->keyBy('id');
        $amount = 0.0;

        foreach ($this->items as $item) {
            $productId = (int) ($item['product_id'] ?? 0);
            $quantity = max(1, (int) ($item['quantity'] ?? 1));
            $selected = $productsById->get($productId);

            if (! $selected) {
                continue;
            }

            $amount += (float) $selected['price'] * $quantity;
        }

        return round($amount, 2);
    }

    protected function resetForm(): void
    {
        $this->order_number = 'ORD-' . now()->format('Ymd-His');
        $this->id_pos = 'POS-TIENDA-01';
        $this->payment_method = 'sinpe';
        $this->ordered_at = now()->format('Y-m-d H:i:s');
        $this->correlation_token = '';
        $this->expires_at = now()->addMinutes(15)->format('Y-m-d H:i:s');
        $this->items = [
            [
                'product_id' => '',
                'quantity' => 1,
            ],
        ];
    }
}
