<x-app-layout>
    <x-slot name="header">
        <div class="flex">
            <div class="font-medium text-white">
                <x-nav-link :href="route('add_product')" :active="request()->routeIs('add_product')">
                    {{ __('Add Product') }}
                </x-nav-link>
            </div>

            <div class="font-medium text-white sm:ms-5">
                <x-nav-link :href="route('product.stock.view')" :active="request()->routeIs('product.stock.view')">
                    {{ __('Product List') }}
                </x-nav-link>
            </div>
        </div>
    </x-slot>

    @include('components.toast')

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-100">
                    <!-- Search row -->
                    <div class="flex items-center mb-6">
                        <div class="relative w-64">
                            <input type="text" id="search" placeholder="Search products..."
                                class="bg-gray-700 text-white rounded-md pl-10 pr-4 py-2 w-full focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg"
                                    viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd"
                                        d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                                        clip-rule="evenodd" />
                                </svg>
                            </div>
                        </div>
                    </div>

                    <div class="bg-gray-900 rounded-lg overflow-hidden">
                        <table class="min-w-full divide-y divide-gray-700">
                            <thead>
                                <tr>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">
                                        Product ID
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">
                                        Product Name
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">
                                        Total Stock Left
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">
                                        Total Expenses
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">
                                        Status
                                    </th>

                                    <th scope="col"
                                        class="px-6 py-3 text-right text-xs font-medium text-gray-300 uppercase tracking-wider">
                                        Actions
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-700">
                                @forelse ($product_with_stock_list as $product_stock)
                                    <tr
                                        class="hover:bg-gray-800 transition-colors duration-150 {{ $product_stock->required_stock_count <= 5 ? 'bg-red-900/40' : '' }} {{ ($product_stock->product->is_active ?? 'available') === 'unavailable' ? 'opacity-60 bg-gray-800' : '' }}">

                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">
                                            {{ $product_stock->product_id }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-white font-medium">
                                            {{ $product_stock->product_name }}
                                        </td>
                                        
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-white font-medium">
                                            @if($product_stock->required_stock === true)
                                                {{ $product_stock->required_stock_count }}
                                                @if ($product_stock->required_stock_count <= 5)
                                                    <span class="ml-2 text-red-400 text-xs">(Low)</span>
                                                @endif
                                            @else
                                                <span class="text-gray-400">Product doesn't require stock</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-white font-medium">
                                            ₱{{ number_format($product_stock->productStockInList->where('is_active', 1)->sum('stock_expenses'), 2) }}
                                        </td>

                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            @if (isset($product_stock->product->is_active))
                                                <span
                                                    class="{{ $product_stock->product->is_active === 'available' ? 'text-green-400' : 'text-gray-400' }}">
                                                    {{ ucfirst($product_stock->product->is_active) }}
                                                </span>
                                            @else
                                                <span class="text-red-400">Unknown</span>
                                            @endif
                                        </td>

                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300 text-right">
                                            <div class="flex justify-end">
                                                @if ($product_stock->required_stock === true)

                                                    @if (($product_stock->product->is_active ?? 'available') === 'unavailable')
                                                        @if ($product_stock->required_stock_count > 0)
                                                            <button type="button"
                                                                class="inline-flex items-center px-3 py-1.5 bg-gray-700 text-gray-400 text-sm font-medium rounded cursor-not-allowed"
                                                                disabled>
                                                                View Stocks
                                                            </button>
                                                        @else
                                                            <button type="button"
                                                                class="inline-flex items-center px-3 py-1.5 bg-blue-500 hover:bg-blue-600 text-white text-sm font-medium rounded cursor-not-allowed"
                                                                disabled>
                                                                <svg class="h-4 w-4 mr-1" xmlns="http://www.w3.org/2000/svg"
                                                                    viewBox="0 0 20 20" fill="currentColor">
                                                                    <path
                                                                        d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                                                                </svg>
                                                                Add Stock
                                                            </button>
                                                        @endif
                                                    @else
                                                        {{-- If available: normal buttons --}}
                                                        @if ($product_stock->required_stock_count > 0)
                                                            <a href="{{ route('view.product.stocks', $product_stock->product_id) }}"
                                                                class="inline-flex items-center px-3 py-1.5 bg-green-700 hover:bg-green-600 text-white text-sm font-medium rounded">
                                                                View Stocks
                                                            </a>
                                                        @else
                                                            <button type="button"
                                                                class="inline-flex items-center px-3 py-1.5 bg-blue-500 hover:bg-blue-600 text-white text-sm font-medium rounded"
                                                                onclick="document.getElementById('stockModal-{{ $product_stock->product_id }}').classList.remove('hidden')">
                                                                <svg class="h-4 w-4 mr-1" xmlns="http://www.w3.org/2000/svg" fill="none"
                                                                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                                        d="M12 4v16m8-8H4" />
                                                                </svg>
                                                                Add Stock
                                                            </button>
                                                            @include('modals.productStockModal', ['product_stock' => $product_stock])
                                                        @endif
                                                    @endif
                                                @endif


                                                <form method="POST"
                                                    action="{{ route('product.toggle.status', $product_stock->product_id) }}"
                                                    class="ml-2">
                                                    @csrf
                                                    @method('PATCH')
                                                    @if (($product_stock->product->is_active ?? 'available') === 'available')

                                                        <div
                                                            class="inline-flex items-center justify-center bg-red-500 hover:bg-red-600 rounded p-2">
                                                            <button type="submit"
                                                                class="p-0 m-0 bg-transparent border-none hover:opacity-75">
                                                                <img src="{{ asset('storage/logo/unavailable.png') }}"
                                                                    alt="Remove" class="w-5 h-5">
                                                            </button>
                                                        </div>

                                                    @else
                                                        <button type="submit"
                                                            class="inline-flex items-center px-3 py-1.5 bg-green-700 hover:bg-green-600 text-white text-sm font-medium rounded"
                                                            style="opacity: 1; position: relative; z-index: 10;">
                                                            Mark Available
                                                        </button>
                                                    @endif
                                                </form>
                                            </div>
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
                                                <p class="text-lg font-medium">No product yet</p>
                                                <p class="text-sm text-gray-400 mt-1">No product available</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="mt-4 ">
                        {{ $product_with_stock_list->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

<script>

    document.addEventListener('DOMContentLoaded', function () {
        // Open the modal when the "Add Stock" button is clicked
        const openModalButton = document.getElementById('openModalButton');
        const modal = document.getElementById('stockModal');
        const closeModalButton = document.getElementById('closeModalButton');
        const consumableIdInput = document.getElementById('consumable-id');
        const form = document.getElementById('addStockForm');

        // When the "Add Stock" button is clicked
        openModalButton.addEventListener('click', function (e) {
            e.preventDefault();

            // Get the consumable ID from the button data attribute
            const consumableId = openModalButton.getAttribute('data-consumable-id');

            // Set the consumable ID to the hidden input
            consumableIdInput.value = consumableId;

            // Show the modal
            modal.classList.remove('hidden');
        });

        // When the "Cancel" button is clicked
        closeModalButton.addEventListener('click', function () {
            // Hide the modal
            modal.classList.add('hidden');
        });

        // Optionally, handle form submission (e.g., submit via AJAX)
        form.addEventListener('submit', function (e) {
            // You can handle your form submission here, maybe via AJAX
            e.preventDefault();

            // Submit form (use AJAX or regular form submit)
            form.submit();
        });
    });

</script>