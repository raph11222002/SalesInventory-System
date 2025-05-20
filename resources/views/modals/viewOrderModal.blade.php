<div id="orderModal-{{ $order->id }}" class="fixed z-50 inset-0 bg-black bg-opacity-50 hidden justify-center items-center">
    <div class="bg-gray-700 rounded-lg shadow-xl w-full max-w-2xl mx-auto">
        <div class="p-6">
            <!-- Modal Header -->
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl font-bold text-white">Order Slip</h2>
                <button type="button"
                    onclick="document.getElementById('orderModal-{{ $order->id }}').classList.remove('flex'); document.getElementById('orderModal-{{ $order->id }}').classList.add('hidden')"
                    class="text-gray-300 hover:text-white text-2xl font-bold focus:outline-none">
                    &times;
                </button>
            </div>

            <!-- Download as PDF Button -->
            <div class="flex justify-end mb-4">
                <a href="{{ route('orders.downloadReceipt', $order->id) }}"
                   class="inline-block px-4 py-2 bg-blue-600 hover:bg-blue-500 text-white text-sm font-semibold rounded">
                    Download
                </a>
            </div>

            <!-- Divider -->
            <div class="border-t border-gray-400 my-4"></div>

            <!-- Order Items Table -->
            <table class="min-w-full text-left text-gray-100">
                <thead>
                    <tr class="text-sm text-gray-300 uppercase tracking-wider border-b border-gray-400">
                        <th class="px-4 py-2">Item</th>
                        <th class="px-4 py-2">Qty</th>
                        <th class="px-4 py-2 text-right">Price</th>
                        <th class="px-4 py-2 text-right">Amount</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($order->items as $item)
                        <tr class="border-b border-gray-500">
                            <td class="px-4 py-2">{{ $item->product->product_name ?? 'N/A' }}</td>
                            <td class="px-4 py-2">{{ $item->quantity }}</td>
                            <td class="px-4 py-2 text-right">₱{{ number_format($item->product_price, 2) }}</td>
                            <td class="px-4 py-2 text-right">
                                ₱{{ number_format($item->amount, 2) }}
                            </td>
                        </tr>
                    @endforeach
                    <tr>
                        <td colspan="3" class="px-4 py-2 text-right font-bold">Total:</td>
                        <td class="px-4 py-2 text-right font-bold">
                            ₱{{ number_format($order->total_amount, 2) }}
                        </td>
                    </tr>
                </tbody>
            </table>

            <!-- Divider -->
            <div class="border-t border-gray-400 my-4"></div>

            <!-- Additional Info -->
            <div class="text-sm text-gray-100 space-y-1">
                <p><span class="font-semibold">Payment Method:</span> {{ $order->payment_method ?? 'N/A' }}</p>
                <p><span class="font-semibold">Staff ID:</span> {{ $order->staff_id ?? 'N/A' }}</p>
                <p><span class="font-semibold">Date Ordered:</span> {{ $order->created_at->format('F j, Y - h:i A') }}</p>
            </div>
        </div>
    </div>
</div>