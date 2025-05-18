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

    <div class="max-w-7xl mx-auto mt-10 grid grid-cols-1 lg:grid-cols-2 gap-4 mb-6">
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
            <form id="orderForm" action="{{ route('product.order.submit', [
    'id' => $product->id,
    'product_group' => $product->product_group,
    'product_name' => $product->product_name,
    'product_price' => $product->product_price
]) }}" method="POST">
                @csrf
                <label class="block text-white mb-2" for="quantity">Enter quantity ordered:</label>

                <input type="number" name="quantity" id="quantity" min="1" required
                    class="w-full px-3 py-2 rounded bg-gray-700 text-white border border-gray-600 mb-4">

                <div class="flex justify-between items-center">
                    <p class="text-medium font-bold text-white">Total Amount:
                        <span id="totalAmountDisplay">₱0.00</span>
                    </p>

                    <input type="hidden" name="amount" id="amount">
                    <input type="hidden" name="payment_method" id="payment_method_hidden">

                    <button type="button" id="proceedBtn" disabled
                        onclick="document.getElementById('confirmationModal').classList.remove('hidden')"
                        class="bg-yellow-600 hover:bg-yellow-500 text-white px-4 py-2 rounded opacity-50 cursor-not-allowed">
                        Add to Order
                    </button>
                </div>
            </form>
        </div>

        <!-- Modal -->
        <div id="confirmationModal" class="fixed inset-0 bg-gray-900 bg-opacity-50 z-50 hidden overflow-y-auto">
            <div class="flex justify-center min-h-screen px-4">
                <div class="bg-white rounded-lg p-6 w-full max-w-md">
                    <h3 class="text-lg font-semibold mb-4">Confirm Order</h3>

                    <div class="border-t border-gray-900 my-4"></div>

                    <p class="mb-4">Product Name: <strong>{{ $product->product_name }}</strong></p>

                    <div class="mt-6">
                        <table class="min-w-full text-left text-gray-700 border-t pt-4">
                            <thead>
                                <tr class="text-sm text-gray-700 uppercase tracking-wider border-b border-gray-900">
                                    <th class="px-4 py-2">Quantity</th>
                                    <th class="px-4 py-2">Price</th>
                                    <th class="px-4 py-2">Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="px-4 py-2 font-bold"><span id="modalQuantity"></span></td>
                                    <td class="px-4 py-2 font-bold">₱{{ $product->product_price }}</td>
                                    <td class="px-4 py-2 font-bold">₱<span id="modalAmount"></span>.00</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Payment Method Toggle -->
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Payment Method</label>
                        <div class="flex space-x-4">
                            <!-- Cash Option -->
                            <label class="flex items-center">
                                <input type="radio" id="cash" name="payment_method" value="Cash"
                                    class="form-radio h-4 w-4 text-blue-500" checked>
                                <span class="ml-2">Cash</span>
                            </label>
                            <!-- Gcash Option -->
                            <label class="flex items-center">
                                <input type="radio" id="gcash" name="payment_method" value="Gcash"
                                    class="form-radio h-4 w-4 text-blue-500">
                                <span class="ml-2">Gcash</span>
                            </label>
                        </div>
                    </div>

                    <div class="flex justify-end space-x-3">
                        <button onclick="document.getElementById('confirmationModal').classList.add('hidden')"
                            class="px-4 py-2 bg-gray-300 hover:bg-gray-400 rounded">Cancel</button>
                        <button onclick="submitOrderForm()"
                            class="px-4 py-2 bg-green-600 hover:bg-green-500 text-white rounded">Confirm</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right: Stock & Consumables -->
        <div class="grid grid-cols-1 gap-4">
            <div class="bg-gray-800 text-white p-4 rounded-lg shadow">
                <h1 class="text-white text-2xl font-bold">Product Stock</h1>

                <div class="border-t border-gray-200 my-4"></div>

                <table class="min-w-full divide-y divide-gray-700">
                    <thead>
                        <tr>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">
                                Product Name
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">
                                Total Stock Left
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-700">
                        @forelse ($product_with_stock_list as $product_stock)
                            <tr class="hover:bg-gray-800 transition-colors duration-150">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-white font-medium">
                                    {{ $product_stock->product_name }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-white font-medium">
                                    {{ $product_stock->required_stock }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-10 text-center text-gray-300">
                                    <div class="flex flex-col items-center justify-center">
                                        <svg class="h-8 w-8 text-gray-500 mb-2" xmlns="http://www.w3.org/2000/svg"
                                            viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd"
                                                d="M5 3a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2V5a2 2 0 00-2-2H5zm0 2h10v10H5V5z"
                                                clip-rule="evenodd" />
                                        </svg>
                                        <p class="text-lg font-medium">This product doesn't require stock</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="bg-gray-800 text-white p-4 rounded-lg shadow">
                <h1 class="text-white text-2xl font-bold">Consumable Stock</h1>

                <div class="border-t border-gray-200 my-4"></div>


                <table class="min-w-full divide-y divide-gray-700">
                    <thead>
                        <tr>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">
                                Consumable Name
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">
                                Total Stock Left
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-700">
                        @forelse ($consumable_list as $consumable)
                            <tr class="hover:bg-gray-800 transition-colors duration-150">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-white font-medium">
                                    {{ $consumable->consumable_name }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-white font-medium">
                                    {{ $consumable->total_stock_left }}
                                </td>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-10 text-center text-gray-300">
                                    <div class="flex flex-col items-center justify-center">
                                        <svg class="h-8 w-8 text-gray-500 mb-2" xmlns="http://www.w3.org/2000/svg"
                                            viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd"
                                                d="M5 3a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2V5a2 2 0 00-2-2H5zm0 2h10v10H5V5z"
                                                clip-rule="evenodd" />
                                        </svg>
                                        <p class="text-lg font-medium">No consumables found</p>
                                        <p class="text-sm text-gray-400 mt-1">No consumable records available</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @include('product.orderSummary')

        <script>
            const quantityInput = document.getElementById('quantity');
            const amountInput = document.getElementById('amount');
            // Auto-fill modal quantity when opening
            document.querySelector('[onclick*="confirmationModal"]').addEventListener('click', function () {
                const quantity = quantityInput.value;
                const amount = amountInput.value;

                document.getElementById('modalQuantity').innerText = quantity || 0;
                document.getElementById('modalAmount').innerText = amount || 0;
            });

            const proceedBtn = document.getElementById('proceedBtn');

            const productPrice = {{ $product->product_price }};
            const totalAmountDisplay = document.getElementById('totalAmountDisplay');

            quantityInput.addEventListener('input', () => {
                const quantity = parseInt(quantityInput.value) || 0;
                const total = quantity * productPrice;

                totalAmountDisplay.textContent = `₱${total.toLocaleString('en-PH', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}`;
                amountInput.value = total;

                if (quantityInput.value && parseInt(quantityInput.value) > 0) {
                    proceedBtn.disabled = false;
                    proceedBtn.classList.remove('opacity-50', 'cursor-not-allowed');
                } else {
                    proceedBtn.disabled = true;
                    proceedBtn.classList.add('opacity-50', 'cursor-not-allowed');
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