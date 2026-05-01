<x-filament-panels::page>
    <x-filament::section>
        <x-slot name="heading">Purchase Orders From API</x-slot>
        <x-slot name="description">Live list fetched from the external FastAPI service.</x-slot>

        {{-- Toolbar --}}
        <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:1rem;">
            <span style="font-size:0.85rem; color:#6b7280;">
                Total: <strong>{{ count($orders) }}</strong>
            </span>
            <x-filament::button color="gray" icon="heroicon-o-arrow-path" wire:click="refreshOrders">
                Refresh
            </x-filament::button>
        </div>

        {{-- Table --}}
        <div style="overflow-x:auto; border-radius:0.75rem; border:1px solid #e5e7eb; overflow:hidden;">
            <table style="width:100%; border-collapse:collapse; font-size:0.875rem;">
                <thead>
                    <tr style="background:#f9fafb; border-bottom:2px solid #e5e7eb;">
                        <th style="padding:0.85rem 1.25rem; text-align:left; font-size:0.75rem; font-weight:700; color:#374151; letter-spacing:0.05em; text-transform:uppercase;">Order Number</th>
                        <th style="padding:0.85rem 1.25rem; text-align:left; font-size:0.75rem; font-weight:700; color:#374151; letter-spacing:0.05em; text-transform:uppercase;">Status</th>
                        <th style="padding:0.85rem 1.25rem; text-align:left; font-size:0.75rem; font-weight:700; color:#374151; letter-spacing:0.05em; text-transform:uppercase;">Payment Method</th>
                        <th style="padding:0.85rem 1.25rem; text-align:left; font-size:0.75rem; font-weight:700; color:#374151; letter-spacing:0.05em; text-transform:uppercase;">Ordered At</th>
                        <th style="padding:0.85rem 1.25rem; text-align:left; font-size:0.75rem; font-weight:700; color:#374151; letter-spacing:0.05em; text-transform:uppercase;">Items</th>
                        <th style="padding:0.85rem 1.25rem; text-align:right; font-size:0.75rem; font-weight:700; color:#374151; letter-spacing:0.05em; text-transform:uppercase;">Amount</th>
                        <th style="padding:0.85rem 1.25rem; text-align:center; font-size:0.75rem; font-weight:700; color:#374151; letter-spacing:0.05em; text-transform:uppercase;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($orders as $index => $order)
                        @php
                            $method = strtoupper($order['payment_method'] ?? '-');
                            $status = strtoupper((string) ($order['status'] ?? 'PENDING'));
                            $statusBadgeStyle = match($status) {
                                'PAID'      => 'background:#dcfce7; color:#166534;',
                                'PENDING'   => 'background:#fef3c7; color:#92400e;',
                                'CANCELLED' => 'background:#fee2e2; color:#991b1b;',
                                default     => 'background:#f3f4f6; color:#374151;',
                            };
                            $badgeStyle = match($method) {
                                'SINPE'     => 'background:#dcfce7; color:#166534;',
                                'CASH'      => 'background:#dbeafe; color:#1e40af;',
                                'CARD'      => 'background:#ede9fe; color:#5b21b6;',
                                'TRANSFER'  => 'background:#fef3c7; color:#92400e;',
                                default     => 'background:#f3f4f6; color:#374151;',
                            };
                            $rowBg = $index % 2 === 0 ? 'background:#ffffff;' : 'background:#f9fafb;';
                            $orderedAt = $order['ordered_at'] ?? '-';
                            if ($orderedAt !== '-') {
                                try {
                                    $orderedAt = \Illuminate\Support\Carbon::parse($orderedAt)->format('d/m/Y H:i');
                                } catch (\Throwable $e) {}
                            }
                            $productCount = count($order['products'] ?? []);
                        @endphp
                        <tr style="{{ $rowBg }} border-bottom:1px solid #f3f4f6;">
                            <td style="padding:1rem 1.25rem; font-weight:600; color:#111827;">
                                {{ $order['order_number'] ?? '-' }}
                            </td>
                            <td style="padding:1rem 1.25rem;">
                                <span style="{{ $statusBadgeStyle }} padding:0.25rem 0.75rem; border-radius:9999px; font-size:0.75rem; font-weight:600;">
                                    {{ $status }}
                                </span>
                            </td>
                            <td style="padding:1rem 1.25rem;">
                                <span style="{{ $badgeStyle }} padding:0.25rem 0.75rem; border-radius:9999px; font-size:0.75rem; font-weight:600;">
                                    {{ $method }}
                                </span>
                            </td>
                            <td style="padding:1rem 1.25rem; color:#6b7280;">
                                {{ $orderedAt }}
                            </td>
                            <td style="padding:1rem 1.25rem;">
                                <span style="background:#f3f4f6; color:#374151; padding:0.25rem 0.75rem; border-radius:9999px; font-size:0.75rem; font-weight:500;">
                                    {{ $productCount }} item{{ $productCount !== 1 ? 's' : '' }}
                                </span>
                            </td>
                            <td style="padding:1rem 1.25rem; text-align:right; font-weight:700; color:#111827;">
                                CRC {{ number_format((float) ($order['amount'] ?? 0), 2) }}
                            </td>
                            <td style="padding:1rem 1.25rem; text-align:center;">
                                <a
                                    href="{{ \App\Filament\Pages\ViewPurchaseOrder::getUrl(['orderNumber' => $order['order_number'] ?? '']) }}"
                                    style="display:inline-flex; align-items:center; border:1px solid #d1d5db; border-radius:0.5rem; padding:0.35rem 0.7rem; font-size:0.8rem; font-weight:600; color:#374151; text-decoration:none;"
                                >
                                    View
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" style="padding:3rem 1.25rem; text-align:center; color:#9ca3af; font-size:0.875rem;">
                                No orders loaded from API.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </x-filament::section>
</x-filament-panels::page>
