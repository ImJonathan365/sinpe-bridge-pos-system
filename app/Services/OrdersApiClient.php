<?php

namespace App\Services;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;

class OrdersApiClient
{
    protected function client(): PendingRequest
    {
        $request = Http::baseUrl(config('sinpe_api.base_url'))
            ->acceptJson()
            ->connectTimeout(3)
            ->timeout(config('sinpe_api.timeout'))
            ->retry(
                config('sinpe_api.retries'),
                config('sinpe_api.retry_sleep_ms')
            );

        if (filled(config('sinpe_api.token'))) {
            $request = $request->withToken(config('sinpe_api.token'));
        }

        return $request;
    }

    public function listOrders(): array
    {
        return $this->client()
            ->get('/orders')
            ->throw()
            ->json();
    }

    public function getOrder(string $orderNumber): array
    {
        return $this->client()
            ->get("/orders/{$orderNumber}")
            ->throw()
            ->json();
    }

    public function createOrder(array $payload): array
    {
        return $this->client()
            ->post('/orders', $payload)
            ->throw()
            ->json();
    }
}
