<x-filament-panels::page>
    <x-filament::section>
        <x-slot name="heading">Detalle de orden de compra</x-slot>
        <x-slot name="description">Vista detallada de una orden cargada desde el API externo.</x-slot>

        <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:1rem;">
            <x-filament::button color="warning" icon="heroicon-o-key" wire:click="openTokenModal">
                Ver token de correlacion
            </x-filament::button>
            <x-filament::button color="gray" icon="heroicon-o-arrow-path" wire:click="refreshOrder">
                Actualizar
            </x-filament::button>
        </div>

        {{-- Modal Token de correlacion --}}
        @if ($showTokenModal)
            <div
                style="position:fixed; inset:0; z-index:50; display:flex; align-items:center; justify-content:center; background:rgba(0,0,0,0.5);"
                wire:click.self="closeTokenModal"
            >
                <div style="background:#ffffff; border-radius:1rem; padding:2rem; max-width:480px; width:90%; box-shadow:0 20px 60px rgba(0,0,0,0.3);">
                    <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:1.5rem;">
                        <h2 style="font-size:1.1rem; font-weight:700; color:#111827; margin:0;">Token de correlacion</h2>
                        <button wire:click="closeTokenModal" style="background:none; border:none; cursor:pointer; color:#9ca3af; font-size:1.25rem; line-height:1;">&times;</button>
                    </div>

                    <p style="font-size:0.85rem; color:#6b7280; margin-bottom:1rem;">
                        Entregue este token al cliente para que complete el pago por SINPE.
                    </p>

                    <div style="background:#f0fdf4; border:2px solid #86efac; border-radius:0.75rem; padding:1.25rem; text-align:center;">
                        <div style="font-size:0.7rem; color:#166534; font-weight:600; letter-spacing:0.1em; text-transform:uppercase; margin-bottom:0.4rem;">Token</div>
                        <div style="font-size:2rem; font-weight:800; color:#15803d; letter-spacing:0.15em; font-family:monospace;">
                            {{ $order['correlation_token'] ?? '-' }}
                        </div>
                    </div>

                    <div style="margin-top:1.25rem; background:#f9fafb; border-radius:0.5rem; padding:0.75rem; font-size:0.8rem; color:#6b7280;">
                        <strong style="color:#374151;">Orden:</strong> {{ $order['order_number'] ?? '-' }}
                        &nbsp;&nbsp;|&nbsp;&nbsp;
                        <strong style="color:#374151;">Monto:</strong> CRC {{ number_format((float) ($order['amount'] ?? 0), 2) }}
                    </div>

                    <div style="margin-top:1.5rem; display:flex; justify-content:flex-end;">
                        <x-filament::button wire:click="closeTokenModal">
                            Continuar
                        </x-filament::button>
                    </div>
                </div>
            </div>
        @endif

        @if (! $order)
            <div style="padding:1rem; border:1px solid #fecaca; background:#fef2f2; color:#991b1b; border-radius:0.5rem;">
                No se pudo cargar el detalle de la orden.
            </div>
        @else
            @php
                $orderedAt = $order['ordered_at'] ?? '-';
                if ($orderedAt !== '-') {
                    try {
                        $orderedAt = \Illuminate\Support\Carbon::parse($orderedAt)->format('d/m/Y H:i:s');
                    } catch (\Throwable $e) {}
                }
                $expiresAt = $order['expires_at'] ?? '-';
                if ($expiresAt !== '-') {
                    try {
                        $expiresAt = \Illuminate\Support\Carbon::parse($expiresAt)->format('d/m/Y H:i:s');
                    } catch (\Throwable $e) {}
                }
                $createdAt = $order['created_at'] ?? '-';
                if ($createdAt !== '-') {
                    try {
                        $createdAt = \Illuminate\Support\Carbon::parse($createdAt)->format('d/m/Y H:i:s');
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
                $statusLabel = match($status) {
                    'PAID' => 'PAGADA',
                    'PENDING' => 'PENDIENTE',
                    'CANCELLED' => 'CANCELADA',
                    default => $status,
                };
                $methodLabel = match($method) {
                    'SINPE' => 'SINPE',
                    'CASH' => 'EFECTIVO',
                    'CARD' => 'TARJETA',
                    'TRANSFER' => 'TRANSFERENCIA',
                    default => $method,
                };
                $products = $order['products'] ?? [];
            @endphp

            <div style="display:grid; grid-template-columns:repeat(3,minmax(0,1fr)); gap:0.75rem; margin-bottom:1rem;">
                <div style="border:1px solid #e5e7eb; border-radius:0.75rem; padding:0.75rem 1rem;">
                    <div style="font-size:0.75rem; color:#6b7280;">Numero de orden</div>
                    <div style="font-size:0.95rem; font-weight:700; color:#111827;">{{ $order['order_number'] ?? '-' }}</div>
                </div>

                <div style="border:1px solid #e5e7eb; border-radius:0.75rem; padding:0.75rem 1rem;">
                    <div style="font-size:0.75rem; color:#6b7280;">Estado</div>
                    <div>
                        <span style="{{ $statusBadgeStyle }} display:inline-flex; align-items:center; padding:0.25rem 0.75rem; border-radius:9999px; font-size:0.75rem; font-weight:600; margin-top:0.2rem;">
                            {{ $statusLabel }}
                        </span>
                    </div>
                </div>

                <div style="border:1px solid #e5e7eb; border-radius:0.75rem; padding:0.75rem 1rem;">
                    <div style="font-size:0.75rem; color:#6b7280;">POS ID</div>
                    <div style="font-size:0.95rem; font-weight:700; color:#111827;">{{ $order['id_pos'] ?? '-' }}</div>
                </div>
            </div>

            <div style="display:grid; grid-template-columns:repeat(3,minmax(0,1fr)); gap:0.75rem; margin-bottom:1rem;">
                <div style="border:1px solid #e5e7eb; border-radius:0.75rem; padding:0.75rem 1rem;">
                    <div style="font-size:0.75rem; color:#6b7280;">Metodo de pago</div>
                    <div style="font-size:0.95rem; font-weight:700; color:#111827;">{{ $methodLabel }}</div>
                </div>

                <div style="border:1px solid #e5e7eb; border-radius:0.75rem; padding:0.75rem 1rem;">
                    <div style="font-size:0.75rem; color:#6b7280;">Fecha de orden</div>
                    <div style="font-size:0.95rem; font-weight:700; color:#111827;">{{ $orderedAt }}</div>
                </div>

                <div style="border:1px solid #e5e7eb; border-radius:0.75rem; padding:0.75rem 1rem;">
                    <div style="font-size:0.75rem; color:#6b7280;">Expira en</div>
                    <div style="font-size:0.95rem; font-weight:700; color:#111827;">{{ $expiresAt }}</div>
                </div>
            </div>

            <div style="display:grid; grid-template-columns:repeat(3,minmax(0,1fr)); gap:0.75rem; margin-bottom:1rem;">
                <div style="border:1px solid #e5e7eb; border-radius:0.75rem; padding:0.75rem 1rem;">
                    <div style="font-size:0.75rem; color:#6b7280;">Monto</div>
                    <div style="font-size:0.95rem; font-weight:700; color:#111827;">CRC {{ number_format((float) ($order['amount'] ?? 0), 2) }}</div>
                </div>

                <div style="border:1px solid #e5e7eb; border-radius:0.75rem; padding:0.75rem 1rem;">
                    <div style="font-size:0.75rem; color:#6b7280;">Token de correlacion</div>
                    <div style="font-size:0.95rem; font-weight:700; color:#111827; word-break:break-all;">{{ $order['correlation_token'] ?? '-' }}</div>
                </div>

                <div style="border:1px solid #e5e7eb; border-radius:0.75rem; padding:0.75rem 1rem;">
                    <div style="font-size:0.75rem; color:#6b7280;">Creada en</div>
                    <div style="font-size:0.95rem; font-weight:700; color:#111827;">{{ $createdAt }}</div>
                </div>
            </div>

            <div style="overflow-x:auto; border:1px solid #e5e7eb; border-radius:0.75rem; overflow:hidden;">
                <table style="width:100%; border-collapse:collapse; font-size:0.875rem;">
                    <thead>
                        <tr style="background:#f9fafb; border-bottom:2px solid #e5e7eb;">
                            <th style="padding:0.85rem 1rem; text-align:left; font-size:0.75rem; font-weight:700; color:#374151; text-transform:uppercase; letter-spacing:0.04em;">Codigo</th>
                            <th style="padding:0.85rem 1rem; text-align:left; font-size:0.75rem; font-weight:700; color:#374151; text-transform:uppercase; letter-spacing:0.04em;">Nombre</th>
                            <th style="padding:0.85rem 1rem; text-align:right; font-size:0.75rem; font-weight:700; color:#374151; text-transform:uppercase; letter-spacing:0.04em;">Precio</th>
                            <th style="padding:0.85rem 1rem; text-align:right; font-size:0.75rem; font-weight:700; color:#374151; text-transform:uppercase; letter-spacing:0.04em;">Cantidad</th>
                            <th style="padding:0.85rem 1rem; text-align:right; font-size:0.75rem; font-weight:700; color:#374151; text-transform:uppercase; letter-spacing:0.04em;">Total de linea</th>
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
                                    No se encontraron productos para esta orden.
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
                    Volver al listado
                </a>
            </div>
        @endif
    </x-filament::section>
</x-filament-panels::page>
