<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('product.stock.view') }}"
                class="inline-flex items-center pr-3 py-2 bg-gray-800 hover:bg-gray-700 text-white font-medium rounded text-lg">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5"
                    stroke="currentColor" class="size-7">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5" />
                </svg>
                Back
            </a>

            <h2 class="font-semibold text-xl text-white leading-tight">
                {{ __('Stock Records for ') }}
                <span class="text-green-500">{{ $product_stock->product_name }}</span>
            </h2>

            <button type="button"
                class="inline-flex items-center px-8 py-3 bg-yellow-900 hover:bg-yellow-700 text-white text-sm font-medium rounded ml-auto"
                onclick="document.getElementById('stockModal-{{ $product_stock->product_id }}').classList.remove('hidden')">
                <svg class="h-4 w-4 mr-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path
                        d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                </svg>
                Add Stock
            </button>

            @include('modals.productStockModal', $product_stock)
        </div>
    </x-slot>

    @include('components.toast')

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-100">
                    <table class="min-w-full divide-y divide-gray-700">
                        <thead>
                            <tr>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">
                                    Stock ID
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">
                                    Quantity Added
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">
                                    Price
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">
                                    Expenses
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">
                                    Date Received
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-right text-xs font-medium text-gray-300 uppercase tracking-wider">
                                    Actions
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-700">
                            @foreach ($stocks as $stock)
                                <tr class="hover:bg-gray-800 transition-colors duration-150">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">
                                        {{ $stock->id }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-white font-medium">
                                        {{ $stock->quantity_added }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-white font-medium">
                                        ₱{{ number_format($stock->stock_price, 2) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-white font-medium">
                                        ₱{{ number_format($stock->stock_expenses, 2) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-white font-medium">
                                        {{ \Carbon\Carbon::parse($stock->created_at)->format(' h:i A, Y-m-d') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-white font-medium">
                                        <div class="flex justify-end">

                                            <div class="inline-flex items-center justify-center bg-red-500 rounded p-2">
                                                <button type="button" onclick="openConfirmModal({{ $stock->id }})"
                                                    class="p-0 m-0 bg-transparent border-none hover:opacity-75">
                                                    <img src="{{ asset('storage/logo/remove.png') }}" alt="Remove"
                                                        class="w-5 h-5">
                                                </button>
                                            </div>

                                            <form id="removeForm-{{ $stock->id }}" method="POST"
                                                action="{{ route('product.remove.stocks', [$stock->id, $product_stock->product_id]) }}">
                                                @csrf
                                                @method('PATCH')
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <!-- Confirmation Modal -->
                    <div id="confirmModal"
                        class="items-center justify-center z-50 fixed inset-0 bg-black bg-opacity-50 hidden">
                        <div>
                            <div class="bg-white rounded-lg shadow-lg max-w-sm w-full p-6">
                                <h2 class="text-lg font-semibold text-gray-800 mb-4">Confirm Removal
                                </h2>
                                <p class="text-gray-700 mb-6">Are you sure you want to remove this added stock?</p>
                                <div class="flex justify-end space-x-3">
                                    <button onclick="closeConfirmModal()"
                                        class="px-4 py-2 text-sm text-gray-700 bg-gray-200 rounded hover:bg-gray-300">Cancel</button>
                                    <button id="confirmBtn"
                                        class="px-4 py-2 text-sm text-white bg-red-600 rounded hover:bg-red-700">Remove</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-4">
                        {{ $stocks->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        let selectedId = null;

        function openConfirmModal(stockId) {
            selectedId = stockId;
            document.getElementById('confirmModal').classList.remove('hidden');
            document.getElementById('confirmModal').classList.add('flex');
        }

        function closeConfirmModal() {
            selectedId = null;
            document.getElementById('confirmModal').classList.add('hidden');
        }

        document.getElementById('confirmBtn').addEventListener('click', function () {
            if (selectedId) {
                document.getElementById('removeForm-' + selectedId).submit();
            }
        });
    </script>
</x-app-layout>