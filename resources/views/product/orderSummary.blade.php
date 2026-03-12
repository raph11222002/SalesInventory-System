<div id="orderSummary"
    class="fixed bottom-4 right-5 transform -translate-x-1 w-full max-w-3xl mx-auto bg-gray-800 border border-gray-700 rounded-lg shadow-lg z-50 transition-all duration-300">
    <div class="flex justify-between items-center p-3 bg-gray-700 rounded-t-lg cursor-pointer" id="orderSummaryHeader">
        <h3 class="text-white font-bold flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                <path
                    d="M3 1a1 1 0 000 2h1.22l.305 1.222a.997.997 0 00.01.042l1.358 5.43-.893.892C3.74 11.846 4.632 14 6.414 14H15a1 1 0 000-2H6.414l1-1H14a1 1 0 00.894-.553l3-6A1 1 0 0017 3H6.28l-.31-1.243A1 1 0 005 1H3z" />
            </svg>
            Order Summary
        </h3>
        <button id="toggleOrderSummary" class="text-gray-300 hover:text-white transition-colors duration-200">
            <svg xmlns="http://www.w3.org/2000/svg" id="toggleIcon" class="h-5 w-5 transition-transform duration-300"
                viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd"
                    d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                    clip-rule="evenodd" />
            </svg>
        </button>
    </div>

    <div id="orderSummaryContent" class="overflow-hidden transition-all duration-300">
        <div class="p-4 space-y-4">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-gray-700">
                        <th class="text-left pb-2 text-gray-300 font-medium">Product</th>
                        <th class="text-right pb-2 text-gray-300 font-medium">Price</th>
                        <th class="text-center pb-2 text-gray-300 font-medium">Qty</th>
                        <th class="text-right pb-2 text-gray-300 font-medium">Amount</th>
                        <th class="text-right pb-2 text-gray-300 font-medium">Remove</th>
                    </tr>
                </thead>
                <tbody id="orderItems" class="divide-y divide-gray-700">
                    @if(session()->has('orderItems') && count(session('orderItems')) > 0)
                        @foreach(session('orderItems') as $index => $item)
                            <tr class="hover:bg-gray-750">
                                <td class="py-3 pr-2">
                                    <div class="flex items-center">
                                        <div class="truncate max-w-[200px]">
                                            <span class="text-white">{{ $item['product_name'] }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="text-right text-gray-400">₱{{ number_format($item['product_price'], 2) }}</td>
                                <td>
                                    <form action="{{ route('order.update-quantity') }}" method="POST"
                                        class="flex justify-center">
                                        @csrf
                                        <input type="hidden" name="index" value="{{ $index }}">
                                        <div class="flex items-center bg-gray-700 rounded max-w-[120px]">
                                            <button type="button" onclick="decrementQuantity(this)"
                                                class="text-gray-400 hover:text-white px-2 py-1 transition-colors duration-200">-</button>
                                            <input type="text" name="quantity" value="{{ $item['quantity'] }}" min="1"
                                                class="w-12 bg-gray-700 text-white text-center border-0 focus:ring-0"
                                                onkeypress="return isNumberKey(event)" onchange="validateAndSubmit(this)">
                                            <button type="button" onclick="incrementQuantity(this)"
                                                class="text-gray-400 hover:text-white px-2 py-1 transition-colors duration-200">+</button>
                                        </div>
                                    </form>
                                </td>
                                <td class="py-3 text-right text-white font-medium">
                                    ₱{{ number_format($item['quantity'] * $item['product_price'], 2) }}</td>
                                <td class="py-3 text-right">
                                    <form action="{{ route('order.remove-item') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="index" value="{{ $index }}">

                                        <button type="submit"
                                            class="remove-item bg-red-500 hover:bg-red-600 px-2 py-1.5 rounded-md text-sm mt-2 md:mt-0 border-none hover:opacity-75">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-white" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="5" class="py-6 text-center text-gray-400">
                                <div class="flex flex-col items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 mb-2 text-gray-500" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                            d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                                    </svg>
                                    No items in your order yet
                                </div>
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>

            <div class="border-t border-gray-500 mt-0 mb-0"></div>

            <div class="flex justify-end font-bold text-white">
                <div>
                    <span>Total:</span>
                    <span class="text-orange-200 mr-5">₱{{ number_format(session('totalAmount', 0), 2) }}</span>

                    @if(session()->has('orderItems') && count(session('orderItems')) > 0)
                        <button id="confirmOrderBtn"
                            class="w-56 bg-green-600 hover:bg-green-700 text-white py-2 rounded font-medium transition-colors duration-200 transform hover:scale-[1.02]">
                            Confirm Order
                        </button>
                    @else
                        <button disabled class="w-56 bg-gray-600 text-gray-400 py-2 rounded font-medium cursor-not-allowed">
                            Confirm Order
                        </button>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<div id="paymentMethodModal" class="fixed inset-0 bg-gray-900 bg-opacity-50 z-50 hidden overflow-y-auto">
    <div class="flex justify-center min-h-screen px-4 py-8">
        <div class="bg-white rounded-lg p-6 w-full max-w-md">
            <h3 class="text-lg font-semibold mb-4">Confirm Order</h3>

            <div class="border-t border-gray-200 my-4"></div>

            <form id="finalOrderForm" action="{{ route('order.confirm') }}" method="POST">
                @csrf
                <!-- Order Summary in Modal -->
                <div class="mb-4">
                    <table class="min-w-full text-left text-gray-700">
                        <thead>
                            <tr class="text-sm text-gray-700 uppercase tracking-wider border-b border-gray-400">
                                <th class="px-4 py-2">Item</th>
                                <th class="px-4 py-2">Qty</th>
                                <th class="px-4 py-2 text-right">Price</th>
                                <th class="px-4 py-2 text-right">Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(session()->has('orderItems'))
                                @foreach(session('orderItems') as $item)
                                    <tr class="border-b border-gray-400">
                                        <td class="px-4 py-2">{{ $item['product_name'] }}</td>
                                        <td class="px-4 py-2">{{ $item['quantity'] }}</td>
                                        <td class="px-4 py-2 text-right">₱{{ number_format($item['product_price'], 2) }}</td>
                                        <td class="px-4 py-2 text-right">
                                            ₱{{ number_format($item['quantity'] * $item['product_price'], 2) }}</td>
                                    </tr>
                                @endforeach
                            @endif

                            <tr>

                                <td colspan="3" class="px-4 py-2 text-right font-bold">Total:</td>
                                <td class="px-4 py-2 text-right font-bold">
                                    ₱{{ number_format(session('totalAmount', 0), 2) }}</td>
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
                    <button type="button"
                        onclick="document.getElementById('paymentMethodModal').classList.add('hidden')"
                        class="px-4 py-2 bg-gray-400 hover:bg-gray-500 rounded">Cancel</button>
                    <button type="submit" class="px-4 py-2 bg-green-500 hover:bg-green-400 rounded text-white">Complete
                        Order</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // Toggle order summary visibility
    document.addEventListener('DOMContentLoaded', function () {
        const orderSummary = document.getElementById('orderSummary');
        const orderSummaryContent = document.getElementById('orderSummaryContent');
        const toggleIcon = document.getElementById('toggleIcon');
        const orderSummaryHeader = document.getElementById('orderSummaryHeader');
        let isCollapsed = false;

        // Set initial height
        orderSummaryContent.style.maxHeight = orderSummaryContent.scrollHeight + 'px';

        function toggleOrderSummary() {
            isCollapsed = !isCollapsed;

            if (isCollapsed) {
                // Collapse animation
                orderSummaryContent.style.maxHeight = '0px';
                toggleIcon.classList.add('rotate-180');
                orderSummary.classList.add('shadow-md');
                orderSummary.classList.remove('shadow-lg');
            } else {
                // Expand animation
                orderSummaryContent.style.maxHeight = orderSummaryContent.scrollHeight + 'px';
                toggleIcon.classList.remove('rotate-180');
                orderSummary.classList.remove('shadow-md');
                orderSummary.classList.add('shadow-lg');
            }
        }

        // Toggle when clicking the header or button
        orderSummaryHeader.addEventListener('click', toggleOrderSummary);

        const confirmOrderBtn = document.getElementById('confirmOrderBtn');
        if (confirmOrderBtn) {
            confirmOrderBtn.addEventListener('click', function () {
                document.getElementById('paymentMethodModal').classList.remove('hidden');
            });
        }

        // Helper functions for quantity buttons
        window.incrementQuantity = function (button) {
            const input = button.parentNode.querySelector('input[name="quantity"]');
            input.value = parseInt(input.value) + 1;
            input.form.submit();
        }

        window.decrementQuantity = function (button) {
            const input = button.parentNode.querySelector('input[name="quantity"]');
            if (parseInt(input.value) > 1) {
                input.value = parseInt(input.value) - 1;
                input.form.submit();
            }
        }
        // Only allow number inputs
        window.isNumberKey = function (evt) {
            const charCode = (evt.which) ? evt.which : evt.keyCode;
            if (charCode > 31 && (charCode < 48 || charCode > 57)) {
                return false;
            }
            return true;
        }

        window.validateAndSubmit = function (input) {
            let value = parseInt(input.value);
            if (isNaN(value) || value < 1) {
                input.value = 1;
            }
            input.form.submit();
        }
    });
</script>