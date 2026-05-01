<x-filament-panels::page>
    <x-filament::section>
        <x-slot name="heading">Purchase Order Details</x-slot>
        <x-slot name="description">Detailed view of one order loaded from external API.</x-slot>

        <div style="display:flex; justify-content:flex-end; margin-bottom:1rem;">
            <x-filament::button color="gray" icon="heroicon-o-arrow-path" wire:click="refreshOrder">
                Refresh
            </x-filament::button>
        </div>

        @if (! $order)
            <div style="padding:1rem; border:1px solid #fecaca; background:#fef2f2; color:#991b1b; border-radius:0.5rem;">
                Could not load order details.
            </div>
        @else
            @php
                $orderedAt = $order['ordered_at'] ?? '-';
                if ($orderedAt !== '-') {
                    try {
                        $orderedAt = \Illuminate\Support\Carbon::parse($orderedAt)->format('d/m/Y H:i:s');
                    } catch (\Throwable $e) {}
                }
                $method = strtoupper((string) ($order['payment_method'] ?? '-'));
                $status = strtoupper((string) ($order['status'] ?? 'PENDING'));
                $statusBadgeStyle = match($status) {
                    'PAID'      => 'background:#dcfce7; color:#166534;',
                    'PENDING'   => 'background:#fef3c7; color:#92400e;',
                    'CANCELLED' => 'background:#fee2e2; color:#991b1b;',
                    default     => 'background:#f3f4f6; color:#374151;',
                };
                $products = $order['products'] ?? [];
            @endphp

            <div style="display:grid; grid-template-columns:repeat(5,minmax(0,1fr)); gap:0.75rem; margin-bottom:1rem;">
                <div style="border:1px solid #e5e7eb; border-radius:0.75rem; padding:0.75rem 1rem;">
                    <div style="font-size:0.75rem; color:#6b7280;">Order Number</div>
                    <div style="font-size:0.95rem; font-weight:700; color:#111827;">{{ $order['order_number'] ?? '-' }}</div>
                </div>

                <div style="border:1px solid #e5e7eb; border-radius:0.75rem; padding:0.75rem 1rem;">
                    <div style="font-size:0.75rem; color:#6b7280;">Status</div>
                    <div>
                        <span style="{{ $statusBadgeStyle }} display:inline-flex; align-items:center; padding:0.25rem 0.75rem; border-radius:9999px; font-size:0.75rem; font-weight:600; margin-top:0.2rem;">
                            {{ $status }}
                        </span>
                    </div>
                </div>

                <div style="border:1px solid #e5e7eb; border-radius:0.75rem; padding:0.75rem 1rem;">
                    <div style="font-size:0.75rem; color:#6b7280;">Payment Method</div>
                    <div style="font-size:0.95rem; font-weight:700; color:#111827;">{{ $method }}</div>
                </div>

                <div style="border:1px solid #e5e7eb; border-radius:0.75rem; padding:0.75rem 1rem;">
                    <div style="font-size:0.75rem; color:#6b7280;">Ordered At</div>
                    <div style="font-size:0.95rem; font-weight:700; color:#111827;">{{ $orderedAt }}</div>
                </div>

                <div style="border:1px solid #e5e7eb; border-radius:0.75rem; padding:0.75rem 1rem;">
                    <div style="font-size:0.75rem; color:#6b7280;">Amount</div>
                    <div style="font-size:0.95rem; font-weight:700; color:#111827;">CRC {{ number_format((float) ($order['amount'] ?? 0), 2) }}</div>
                </div>
            </div>

            <div style="overflow-x:auto; border:1px solid #e5e7eb; border-radius:0.75rem; overflow:hidden;">
                <table style="width:100%; border-collapse:collapse; font-size:0.875rem;">
                    <thead>
                        <tr style="background:#f9fafb; border-bottom:2px solid #e5e7eb;">
                            <th style="padding:0.85rem 1rem; text-align:left; font-size:0.75rem; font-weight:700; color:#374151; text-transform:uppercase; letter-spacing:0.04em;">Code</th>
                            <th style="padding:0.85rem 1rem; text-align:left; font-size:0.75rem; font-weight:700; color:#374151; text-transform:uppercase; letter-spacing:0.04em;">Name</th>
                            <th style="padding:0.85rem 1rem; text-align:right; font-size:0.75rem; font-weight:700; color:#374151; text-transform:uppercase; letter-spacing:0.04em;">Price</th>
                            <th style="padding:0.85rem 1rem; text-align:right; font-size:0.75rem; font-weight:700; color:#374151; text-transform:uppercase; letter-spacing:0.04em;">Quantity</th>
                            <th style="padding:0.85rem 1rem; text-align:right; font-size:0.75rem; font-weight:700; color:#374151; text-transform:uppercase; letter-spacing:0.04em;">Line Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($products as $index => $product)
                            @php
                                $rowStyle = $index % 2 === 0 ? 'background:#ffffff;' : 'background:#f9fafb;';
                            @endphp
                            <tr style="{{ $rowStyle }} border-bottom:1px solid #f3f4f6;">
                                <td style="padding:0.9rem 1rem; color:#111827; font-weight:600;">{{ $product['code'] ?? '-' }}</td>
                                <td style="padding:0.9rem 1rem; color:#374151;">{{ $product['name'] ?? '-' }}</td>
                                <td style="padding:0.9rem 1rem; text-align:right; color:#374151;">CRC {{ number_format((float) ($product['price'] ?? 0), 2) }}</td>
                                <td style="padding:0.9rem 1rem; text-align:right; color:#374151;">{{ $product['quantity'] ?? 0 }}</td>
                                <td style="padding:0.9rem 1rem; text-align:right; color:#111827; font-weight:700;">CRC {{ number_format((float) ($product['line_total'] ?? 0), 2) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" style="padding:2rem 1rem; text-align:center; color:#9ca3af;">
                                    No products found for this order.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div style="margin-top:1rem;">
                <a
                    href="{{ \App\Filament\Pages\ListPurchaseOrders::getUrl() }}"
                    style="display:inline-flex; align-items:center; gap:0.4rem; border:1px solid #d1d5db; border-radius:0.5rem; padding:0.5rem 0.75rem; font-size:0.85rem; font-weight:600; color:#374151; text-decoration:none;"
                >
                    Back to list
                </a>
            </div>
        @endif
    </x-filament::section>
</x-filament-panels::page>
