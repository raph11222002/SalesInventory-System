<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('record_sales') }}"
                class="inline-flex items-center pr-3 py-2 bg-gray-800 hover:bg-gray-700 text-white font-medium rounded text-lg">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5"
                    stroke="currentColor" class="size-7">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5" />
                </svg>
                Back
            </a>

            <h2 class="font-semibold text-xl text-white leading-tight">
                {{ __('Order for ') }}
                <span class="text-orange-400">{{ $product->product_name }}</span>
            </h2>
        </div>
    </x-slot>

    @include('components.toast')

    <div class="max-w-7xl mx-auto mt-5 grid grid-cols-1 lg:grid-cols-2 gap-4 mb-10">

        <!-- Left: Order Form -->
        <div class="bg-gray-800 p-6 rounded shadow">
            <div class="flex justify-between">
                <span class="text-white text-lg font-bold">{{ $product->product_group }}</span>
                <span class="text-white text-sm">Product ID: {{ $product->id }}</span>
            </div>
            <h2 class="text-white text-2xl font-bold mb-4">{{ $product->product_name }}</h2>

            <div class="border-t border-gray-200 my-4"></div>

            <!-- Consumables List -->
            <div class="mb-4">
                <p class="text-sm font-semibold text-white mb-1">Consumable:</p>
                @if($product->productConsumableNeeded->count() > 0)
                    <ul class="list-disc list-inside text-sm text-gray-300">
                        @foreach($product->productConsumableNeeded as $consumable)
                            <li>{{ $consumable->quantity_needed }} {{ $consumable->consumable_name }}</li>
                        @endforeach
                    </ul>
                @else
                    <p class="text-sm text-gray-400">No Consumables</p>
                @endif
            </div>

            <!-- Price -->
            <p class="text-gray-300 mb-2">Price:
                <span class="text-yellow-300 font-semibold">
                    ₱{{ number_format($product->product_price, 2) }}
                </span>
            </p>

            <!-- Order Form -->
            <form id="orderForm" action="{{ route('order.add-to-order', $product->id) }}" method="POST">
                @csrf
                <input type="hidden" name="product_name" value="{{ $product->product_name }}">
                <input type="hidden" name="product_price" value="{{ $product->product_price }}">

                <label class="block text-white mb-2" for="quantity">Enter quantity ordered:</label>

                <input type="number" name="quantity" id="quantity" min="1" autocomplete="off" required
                    class="w-full px-3 py-2 rounded bg-gray-700 text-white border border-gray-600 mb-4">

                <div class="flex justify-between items-center">
                    <p class="text-medium font-bold text-white">Total Amount:
                        <span id="totalAmountDisplay">₱0.00</span>
                    </p>

                    <input type="hidden" name="amount" id="amount">
                    <input type="hidden" name="payment_method" id="payment_method_hidden">

                    <button type="submit" id="proceedBtn" disabled
                        class="bg-yellow-600 hover:bg-yellow-500 text-white px-4 py-2 rounded opacity-50 cursor-not-allowed">
                        Add to Order
                    </button>
                </div>

                <div id="orderButtonWrapper"></div>
            </form>
        </div>

        <!-- Right: Stock Info -->
        <div class="grid grid-cols-1 gap-4">

            {{-- Product Stock: only show if product has stock requirement --}}
            @if ($product_with_stock_list->isNotEmpty())
                <div class="bg-gray-800 text-white p-4 rounded-lg shadow">
                    <h1 class="text-white text-2xl font-bold">Product Stock</h1>
                    <div class="border-t border-gray-200 my-4"></div>
                    <table class="min-w-full divide-y divide-gray-700">
                        <thead class="bg-gray-900">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Product Name</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Total Stock Left</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-700">
                            @foreach ($product_with_stock_list as $product_stock)
                                <tr class="bg-gray-800 transition-colors duration-150 {{ $product_stock->required_stock <= 5 ? 'bg-red-900/40' : '' }}">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-white font-medium">
                                        {{ $product_stock->product_name }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-white font-medium">
                                        {{ $product_stock->required_stock }}
                                        @if ($product_stock->required_stock <= 5)
                                            <span class="ml-2 text-red-400 text-xs">(Low)</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif

            {{-- Consumable Stock: only show if product has consumables --}}
            @if ($consumable_list->isNotEmpty())
                <div class="bg-gray-800 text-white p-4 rounded-lg shadow">
                    <div class="flex justify-between items-center">
                        <h1 class="text-white text-2xl font-bold">Consumable Stock</h1>
                        <div class="rounded-lg">
                            {{ $consumable_list->links() }}
                        </div>
                    </div>
                    <div class="border-t border-gray-200 my-4"></div>
                    <table class="min-w-full divide-y divide-gray-700">
                        <thead class="bg-gray-900">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Consumable Name</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Total Stock Left</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-700">
                            @foreach ($consumable_list as $consumable)
                                <tr class="bg-gray-800 transition-colors duration-150 {{ $consumable->total_stock_left <= 5 ? 'bg-red-900/40' : '' }}">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-white font-medium">
                                        {{ $consumable->consumable_name }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-white font-medium">
                                        {{ $consumable->total_stock_left }}
                                        @if ($consumable->total_stock_left <= 5)
                                            <span class="ml-2 text-red-400 text-xs">(Low)</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif

            {{-- Show only when BOTH stock and consumables are absent --}}
            @if ($product_with_stock_list->isEmpty() && $consumable_list->isEmpty())
                <div class="bg-gray-800 rounded-lg shadow flex flex-col items-center justify-center py-16 px-6 text-center">
                    <div class="w-14 h-14 rounded-full bg-gray-700 flex items-center justify-center mb-5">
                        <svg class="w-7 h-7 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-.375c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v.375c0 .621.504 1.125 1.125 1.125z" />
                        </svg>
                    </div>
                    <p class="text-white font-semibold text-lg mb-1">No Stock Tracking Required</p>
                    <p class="text-gray-400 text-sm">This product has no stock or consumable requirements.</p>
                </div>
            @endif

        </div>

        @include('product.orderSummary')

        <script>
            const quantityInput = document.getElementById('quantity');
            const amountInput = document.getElementById('amount');
            const proceedBtn = document.getElementById('proceedBtn');

            const productPrice = {{ $product->product_price }};
            const totalAmountDisplay = document.getElementById('totalAmountDisplay');

            const productStocks = @json($product_with_stock_list);
            const productConsumables = @json($product->productConsumableNeeded);
            const consumableStocks = @json($consumable_list->items());

            const buttonWrapper = document.getElementById('orderButtonWrapper');

            const warningsContainer = document.createElement('div');
            warningsContainer.id = 'warningsContainer';
            warningsContainer.className = 'mt-2 text-sm text-red-300';
            buttonWrapper.appendChild(warningsContainer);

            quantityInput.addEventListener('input', () => {
                const quantity = parseInt(quantityInput.value) || 0;
                const total = quantity * productPrice;
                totalAmountDisplay.textContent = `₱${total.toLocaleString('en-PH', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}`;
                amountInput.value = total;

                let stockIssues = [];

                // Check Product Stocks
                productStocks.forEach(stock => {
                    const required = stock.required_stock;
                    const needed = quantity;
                    if (required < needed) {
                        stockIssues.push(`Insufficient product stock for "${stock.product_name}". Required: ${needed}, Available: ${required}`);
                    }
                });

                // Check Consumable Stocks
                productConsumables.forEach(consumable => {
                    const totalRequired = consumable.quantity_needed * quantity;
                    const stock = consumableStocks.find(c => c.id === consumable.consumable_id);

                    if (stock && stock.total_stock_left < totalRequired) {
                        stockIssues.push(`Insufficient consumable stock for "${stock.consumable_name}". Required: ${totalRequired}, Available: ${stock.total_stock_left}`);
                    }
                });

                if (stockIssues.length > 0) {
                    proceedBtn.disabled = true;
                    proceedBtn.classList.add('opacity-50', 'cursor-not-allowed');
                    warningsContainer.innerHTML = stockIssues.map(w => `<div>⚠️ ${w}</div>`).join('');
                } else if (quantity > 0) {
                    proceedBtn.disabled = false;
                    proceedBtn.classList.remove('opacity-50', 'cursor-not-allowed');
                    warningsContainer.innerHTML = '';
                } else {
                    proceedBtn.disabled = true;
                    proceedBtn.classList.add('opacity-50', 'cursor-not-allowed');
                    warningsContainer.innerHTML = '';
                }
            });

            function submitOrderForm() {
                const selectedPayment = document.querySelector('input[name="payment_method"]:checked');
                if (selectedPayment) {
                    document.getElementById('payment_method_hidden').value = selectedPayment.value;
                }
                document.getElementById('orderForm').submit();
            }
        </script>

        <script src="//unpkg.com/alpinejs" defer></script>
</x-app-layout>