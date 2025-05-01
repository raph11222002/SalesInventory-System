<x-app-layout>
    <x-slot name="header">

        <div class="flex">
            <div class="font-medium text-white">
                <x-nav-link :href="route('inventory_log')" :active="request()->routeIs('inventory_log')">
                    {{ __('Inventory Log') }}
                </x-nav-link>
            </div>

            <div class="font-medium text-white sm:ms-5">
                <x-nav-link :href="route('add_product')" :active="request()->routeIs('add_product')">
                    {{ __('Inventory Outflows') }}
                </x-nav-link>
            </div>

            @if (session('success'))
                <div class="mb-4 text-green-600 bg-green-100 border border-green-300 p-3 rounded">
                    {{ session('success') }}
                </div>
            @endif

        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-100">
                    <!-- Search row -->
                    <div class="flex items-center mb-6">
                        <div class="relative w-64">
                            <input type="text" id="search" placeholder="Search inventories..."
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

                    <!-- Inventory list -->
                    <div class="bg-gray-900 rounded-lg overflow-hidden">
                        <table class="min-w-full divide-y divide-gray-700">
                            <thead>
                                <tr>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">
                                        Inventory ID
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">
                                        Inventory Name
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">
                                        Total Quantity
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-right text-xs font-medium text-gray-300 uppercase tracking-wider">
                                        Actions
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-700">
                                @forelse ($inventories as $inventory)
                                    <tr class="hover:bg-gray-800 transition-colors duration-150">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">
                                            {{ $inventory->id }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-white font-medium">
                                            {{ $inventory->inventory_name }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">
                                            {{ $inventory->total_quantity }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300 text-right">
                                            <div class="flex justify-end">
                                                <button type="button"
                                                    class="inline-flex items-center px-3 py-1.5 bg-yellow-900 hover:bg-yellow-700 text-white text-sm font-medium rounded"
                                                    onclick="document.getElementById('stockModal-{{ $inventory->id }}').classList.remove('hidden')">
                                                    <svg class="h-4 w-4 mr-1" xmlns="http://www.w3.org/2000/svg"
                                                        viewBox="0 0 20 20" fill="currentColor">
                                                        <path
                                                            d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                                                    </svg>
                                                    Add Stock
                                                </button>


                                                @include('modals.stockModal', ['inventory' => $inventory])
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
                                                <p class="text-lg font-medium">No inventories found</p>
                                                <p class="text-sm text-gray-400 mt-1">No inventory records available</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="mt-4">
                        {{ $inventories->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

<script src="{{ asset('js/add_product.js') }}"></script>