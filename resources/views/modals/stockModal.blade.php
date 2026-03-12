<!-- resources/views/modals/stockModal.blade.php -->
<div id="stockModal-{{ $consumable->id }}" class="fixed inset-0 z-50 hidden overflow-y-auto" role="dialog"
    aria-modal="true" aria-labelledby="modal-title">
    <div class="flex items-center justify-center min-h-screen px-4 text-center">
        <!-- Background overlay -->
        <div class="fixed inset-0 bg-gray-900 bg-opacity-50 transition-opacity" aria-hidden="true"></div>

        <!-- Modal panel -->
        <div
            class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-md w-full">
            <div class="bg-gray-300 px-6 py-5">
                <div class="flex items-center mb-4">
                    <h3 class="text-lg font-semibold text-gray-800" id="modal-title">
                        Add Stock to <span class="text-indigo-600">{{ $consumable->consumable_name }}</span>
                    </h3>
                </div>

                <form method="POST" action="{{ route('inventories.addStock', $consumable->id) }}">
                    @csrf

                    <input type="number" name="quantity" id="quantity" min="1" autocomplete="off"
                        class="mt-1 block w-full rounded-md text-black border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 p-2 text-sm"
                        placeholder="Enter quantity" required>

                    <input type="number" name="stock_price" id="quantity" min="0" autocomplete="off"
                        class="mt-3 block w-full rounded-md text-black border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 p-2 text-sm"
                        placeholder="Stock Total Expenses" required>

                    <div class="mt-6 flex justify-end space-x-3">
                        <button type="button"
                            class="inline-flex justify-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-100"
                            onclick="document.getElementById('stockModal-{{ $consumable->id }}').classList.add('hidden')">
                            Cancel
                        </button>
                        <button type="submit"
                            class="inline-flex justify-center rounded-md bg-green-600 px-4 py-2 text-sm font-medium text-white hover:bg-green-700 shadow-sm">
                            Add Stock
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>