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
                    {{ __('Product Stock List') }}
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
                                        class="px-6 py-3 text-right text-xs font-medium text-gray-300 uppercase tracking-wider">
                                        Actions
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-700">
                                @forelse ($product_with_stock_list as $product_stock)
                                    <tr
                                        class="hover:bg-gray-800 transition-colors duration-150 {{ $product_stock->required_stock < 5 ? 'bg-red-900/40' : '' }}">

                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">
                                            {{ $product_stock->product_id }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-white font-medium">
                                            {{ $product_stock->product_name }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-white font-medium">
                                            {{ $product_stock->required_stock }}
                                            @if ($product_stock->required_stock < 5)
                                                <span class="ml-2 text-red-400 text-xs">(Low)</span>
                                            @endif
                                        </td>

                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300 text-right">
                                            <div class="flex justify-end">
                                                @if ($product_stock->product_stock_in_list_count > 0)
                                                    <a href="{{ route('view.product.stocks', $product_stock->product_id) }}"
                                                        class="inline-flex items-center px-3 py-1.5 bg-green-700 hover:bg-green-600 text-white text-sm font-medium rounded">
                                                        View Stocks
                                                    </a>
                                                @else
                                                    <button type="button"
                                                        class="inline-flex items-center px-3 py-1.5 bg-yellow-900 hover:bg-yellow-700 text-white text-sm font-medium rounded"
                                                        onclick="document.getElementById('stockModal-{{ $product_stock->product_id }}').classList.remove('hidden')">
                                                        <svg class="h-4 w-4 mr-1" xmlns="http://www.w3.org/2000/svg"
                                                            viewBox="0 0 20 20" fill="currentColor">
                                                            <path
                                                                d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                                                        </svg>
                                                        Add Stock
                                                    </button>
                                                    @include('modals.productStockModal', $product_stock)
                                                @endif
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
                                                <p class="text-lg font-medium">No product that requires stock found</p>
                                                <p class="text-sm text-gray-400 mt-1">No product that requires stock records
                                                    available</p>
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