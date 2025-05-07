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

    <div class="max-w-xl mx-auto mt-10 bg-gray-800 p-6 rounded shadow">
        <h2 class="text-white text-2xl font-bold mb-4">{{ $product->product_name }}</h2>
        <p class="text-gray-300 mb-2">Price: <span
                class="text-green-400 font-semibold">₱{{ number_format($product->product_price, 2) }}</span></p>

        <form id="orderForm" action="{{ route('product.order.submit', $product->id) }}" method="POST">
            @csrf
            <label class="block text-white mb-2" for="quantity">Enter quantity:</label>
            <input type="number" name="quantity" id="quantity" min="1" required
                class="w-full px-3 py-2 rounded bg-gray-700 text-white border border-gray-600 mb-4">

            <button type="button" onclick="document.getElementById('confirmationModal').classList.remove('hidden')"
                class="bg-yellow-600 hover:bg-yellow-500 text-white px-4 py-2 rounded">
                Proceed
            </button>
        </form>
    </div>

    <!-- Modal -->
    <div id="confirmationModal" class="fixed inset-0 bg-gray-900 bg-opacity-50 z-50 hidden overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen px-4 text-center">
            <div class="bg-white rounded-lg p-6 w-full max-w-md">
                <h3 class="text-lg font-semibold mb-4">Confirm Order</h3>
                <p class="mb-4">Quantity: <span id="modalQuantity" class="font-bold"></span>
                    unit(s) of <strong>{{ $product->product_name }}</strong>?</p>
                <div class="flex justify-end space-x-3">
                    <button onclick="document.getElementById('confirmationModal').classList.add('hidden')"
                        class="px-4 py-2 bg-gray-300 hover:bg-gray-400 rounded">Cancel</button>
                    <button onclick="document.getElementById('orderForm').submit()"
                        class="px-4 py-2 bg-green-600 hover:bg-green-500 text-white rounded">Confirm</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Auto-fill modal quantity when opening
        document.querySelector('[onclick*="confirmationModal"]').addEventListener('click', function () {
            const quantity = document.getElementById('quantity').value;
            document.getElementById('modalQuantity').innerText = quantity || 0;
        });
    </script>

    <script src="//unpkg.com/alpinejs" defer></script>
</x-app-layout>