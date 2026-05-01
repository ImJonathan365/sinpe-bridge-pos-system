<x-filament-panels::page>
    <x-filament::section>
        <x-slot name="heading">Create Purchase Order</x-slot>
        <x-slot name="description">Build the order with local products and send it to the external API.</x-slot>

        <form wire:submit="submit" style="display:flex; flex-direction:column; gap:1rem;">
            <div style="display:grid; grid-template-columns:repeat(3, minmax(0, 1fr)); gap:1rem;">
                <div>
                    <label style="display:block; margin-bottom:0.35rem; font-size:0.8rem; font-weight:600; color:#374151;">
                        Order Number
                    </label>
                    <input
                        type="text"
                        wire:model="order_number"
                        style="width:100%; border:1px solid #d1d5db; border-radius:0.5rem; padding:0.6rem 0.75rem; font-size:0.9rem;"
                    >
                </div>

                <div>
                    <label style="display:block; margin-bottom:0.35rem; font-size:0.8rem; font-weight:600; color:#374151;">
                        Payment Method
                    </label>
                    <select
                        wire:model="payment_method"
                        style="width:100%; border:1px solid #d1d5db; border-radius:0.5rem; padding:0.6rem 0.75rem; font-size:0.9rem;"
                    >
                        <option value="sinpe">SINPE</option>
                        <option value="cash">Cash</option>
                        <option value="card">Card</option>
                        <option value="transfer">Transfer</option>
                    </select>
                </div>

                <div>
                    <label style="display:block; margin-bottom:0.35rem; font-size:0.8rem; font-weight:600; color:#374151;">
                        Ordered At
                    </label>
                    <input
                        type="text"
                        wire:model="ordered_at"
                        style="width:100%; border:1px solid #d1d5db; border-radius:0.5rem; padding:0.6rem 0.75rem; font-size:0.9rem;"
                    >
                </div>
            </div>

            <div style="display:flex; align-items:center; justify-content:space-between; margin-top:0.25rem;">
                <h3 style="font-size:0.95rem; font-weight:700; color:#111827; margin:0;">Products</h3>
                <x-filament::button type="button" color="gray" wire:click="addItem">
                    Add Line
                </x-filament::button>
            </div>

            <div style="overflow-x:auto; border:1px solid #e5e7eb; border-radius:0.75rem; overflow:hidden;">
                <table style="width:100%; border-collapse:collapse; font-size:0.875rem;">
                    <thead>
                        <tr style="background:#f9fafb; border-bottom:2px solid #e5e7eb;">
                            <th style="padding:0.85rem 1rem; text-align:left; font-size:0.75rem; font-weight:700; color:#374151; text-transform:uppercase; letter-spacing:0.04em;">Product</th>
                            <th style="padding:0.85rem 1rem; text-align:left; font-size:0.75rem; font-weight:700; color:#374151; text-transform:uppercase; letter-spacing:0.04em;">Qty</th>
                            <th style="padding:0.85rem 1rem; text-align:left; font-size:0.75rem; font-weight:700; color:#374151; text-transform:uppercase; letter-spacing:0.04em;">Unit Price</th>
                            <th style="padding:0.85rem 1rem; text-align:left; font-size:0.75rem; font-weight:700; color:#374151; text-transform:uppercase; letter-spacing:0.04em;">Line Total</th>
                            <th style="padding:0.85rem 1rem; text-align:left; font-size:0.75rem; font-weight:700; color:#374151; text-transform:uppercase; letter-spacing:0.04em;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($items as $index => $item)
                            @php
                                $selected = collect($products)->firstWhere('id', (int) ($item['product_id'] ?? 0));
                                $unitPrice = $selected['price'] ?? 0;
                                $lineTotal = $unitPrice * max(1, (int) ($item['quantity'] ?? 1));
                                $rowStyle = $index % 2 === 0 ? 'background:#ffffff;' : 'background:#f9fafb;';
                            @endphp

                            <tr style="{{ $rowStyle }} border-bottom:1px solid #f3f4f6;">
                                <td style="padding:0.9rem 1rem;">
                                    <select
                                        wire:model.live="items.{{ $index }}.product_id"
                                        style="width:100%; border:1px solid #d1d5db; border-radius:0.5rem; padding:0.5rem 0.6rem; font-size:0.85rem;"
                                    >
                                        <option value="">Select a product</option>
                                        @foreach ($products as $product)
                                            <option value="{{ $product['id'] }}">
                                                {{ $product['code'] }} - {{ $product['name'] }}
                                            </option>
                                        @endforeach
                                    </select>
                                </td>

                                <td style="padding:0.9rem 1rem; width:120px;">
                                    <input
                                        type="number"
                                        min="1"
                                        wire:model.live="items.{{ $index }}.quantity"
                                        style="width:80px; border:1px solid #d1d5db; border-radius:0.5rem; padding:0.5rem 0.6rem; font-size:0.85rem;"
                                    >
                                </td>

                                <td style="padding:0.9rem 1rem; color:#374151; font-weight:500;">
                                    CRC {{ number_format($unitPrice, 2) }}
                                </td>

                                <td style="padding:0.9rem 1rem; color:#111827; font-weight:700;">
                                    CRC {{ number_format($lineTotal, 2) }}
                                </td>

                                <td style="padding:0.9rem 1rem;">
                                    <x-filament::button
                                        type="button"
                                        color="danger"
                                        size="sm"
                                        wire:click="removeItem({{ $index }})"
                                    >
                                        Remove
                                    </x-filament::button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div style="display:flex; align-items:center; justify-content:space-between; background:#f9fafb; border:1px solid #e5e7eb; border-radius:0.75rem; padding:0.75rem 1rem;">
                <span style="font-size:0.85rem; color:#6b7280;">Computed Amount</span>
                <strong style="font-size:1.05rem; color:#111827;">CRC {{ number_format($this->computedTotal, 2) }}</strong>
            </div>

            <div style="display:flex; justify-content:flex-end; margin-top:0.25rem;">
                <x-filament::button type="submit" icon="heroicon-o-paper-airplane" wire:loading.attr="disabled">
                    Send Order To API
                </x-filament::button>
            </div>
        </form>
    </x-filament::section>
</x-filament-panels::page>
