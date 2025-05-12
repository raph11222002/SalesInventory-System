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

            <!-- Divider -->
            <div class="border-t border-gray-400 my-4"></div>

            <!-- Date and Time -->
            <div class="flex justify-end text-sm text-gray-300 mb-4">
                <p>{{ $order->created_at->format('F j, Y - h:i A') }}</p>
            </div>

            <!-- Modal Body aligned left -->
            <div class="text-gray-200 space-y-4 text-left">
                <p><strong>Order ID:</strong> {{ $order->id }}</p>
                <p><strong>Product Group:</strong> {{ $order->product_group }}</p>
                <p><strong>Product Name:</strong> {{ $order->product_name }}</p>

                <div>
                    <p class="font-semibold">Consumable:</p>
                    @if ($order->products && $order->products->productConsumableNeeded->count())
                        <ul class="list-disc list-inside ml-4 mt-1 text-gray-100">
                            @foreach ($order->products->productConsumableNeeded as $consumable)
                                <li>{{ $consumable->consumable_name }}</li>
                            @endforeach
                        </ul>
                    @else
                        <p class="text-sm text-gray-400">None</p>
                    @endif
                </div>
            </div>

            <!-- Order Summary Table -->
            <div class="mt-6">
                <table class="min-w-full text-left text-gray-200 border-t border-gray-400 pt-4">
                    <thead>
                        <tr class="text-sm text-gray-300 uppercase tracking-wider border-b border-gray-400">
                            <th class="px-4 py-2">Quantity Ordered</th>
                            <th class="px-4 py-2">Product Price</th>
                            <th class="px-4 py-2">Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="px-4 py-2">{{ $order->quantity_ordered }}</td>
                            <td class="px-4 py-2">₱{{ number_format($order->product_price, 2) }}</td>
                            <td class="px-4 py-2">₱{{ number_format($order->amount, 2) }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
