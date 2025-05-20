<!-- resources/views/modals/stockModal.blade.php -->
<div id="stockModal-{{ $consumable->id }}" class="fixed inset-0 z-50 hidden overflow-y-auto" role="dialog" aria-modal="true" aria-labelledby="modal-title">
    <div class="flex items-center justify-center min-h-screen px-4 text-center">
        <!-- Background overlay -->
        <div class="fixed inset-0 bg-gray-900 bg-opacity-50 transition-opacity" aria-hidden="true"></div>

        <!-- Modal panel -->
        <div class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-md w-full">
            <div class="bg-gray-300 px-6 py-5">
                <div class="flex items-center mb-4">
                    <div class="flex-shrink-0 bg-green-100 text-green-600 rounded-full p-2">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z" />
                        </svg>
                    </div>
                    <h3 class="ml-3 text-lg font-semibold text-gray-800" id="modal-title">
                        Add Stock to <span class="text-indigo-600">{{ $consumable->consumable_name }}</span>
                    </h3>
                </div>

                <form method="POST" action="{{ route('inventories.addStock', $consumable->id) }}">
                    @csrf

                    <label for="quantity" class="block text-sm font-medium text-gray-700">Quantity</label>
                    <input type="number" name="quantity" id="quantity" min="1" autocomplete="off"
                           class="mt-1 block w-full rounded-md text-black border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 p-2 text-sm"
                           placeholder="Enter quantity" required>

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
