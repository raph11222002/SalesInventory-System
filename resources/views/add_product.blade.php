<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            {{ __('Add Product') }}
        </h2>
    </x-slot>

    <div class="py-10">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-gray-800 shadow-md rounded-lg p-8">
                <form id="productForm" method="POST" action="{{ route('products.store') }}"
                    enctype="multipart/form-data">
                    @csrf

                    <!-- Product Image Upload -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-300 mb-2">Product Image</label>
                        <input type="file" name="image_path" accept="image/*" id="productImage"
                            class="block w-full text-sm text-gray-200 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">

                        <!-- Image Preview -->
                        <div id="imagePreview" class="mt-4" style="display: none;">
                            <div class="flex items-center justify-between mb-2">
                                <p class="text-sm text-gray-400">Image Preview:

                                    <button type="button" id="removeImage"
                                        class="text-red-500 text-sm hover:text-red-400">
                                        Remove Image
                                    </button>
                                </p>
                            </div>
                            <div class="w-40 h-40 relative border rounded overflow-hidden">
                                <img id="previewImg" src="" alt="Image Preview"
                                    class="absolute w-full h-full object-cover">
                            </div>
                        </div>
                    </div>

                    <!-- Product Info -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-1">Product Group
                                <span class="text-red-500">*</span></label>
                            <input type="text" name="product_group" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring focus:ring-blue-300"
                                value="{{ old('product_group') }}">
                            @error('product_group')
                                <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                            @enderror

                            <label class="block text-sm font-medium text-gray-300 mb-1 mt-3">Price
                                <span class="text-red-500">*</span></label>
                            <input type="number" name="product_price" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring focus:ring-blue-300"
                                value="{{ old('product_price') }}">
                            @error('product_group')
                                <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-1">Product Name
                                <span class="text-red-500">*</span></label>
                            <input type="text" name="product_name" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring focus:ring-blue-300"
                                value="{{ old('product_name') }}">
                            @error('product_name')
                                <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Inventory Items -->
                    <div class="mt-8">
                        <h4 class="text-lg font-medium text-gray-200 mb-4">Inventory Items</h4>

                        <div id="inventory-items-container">
                            <div class="flex flex-col md:flex-row items-start md:items-end mb-4 gap-4 inventory-item">
                                <div class="flex-1">
                                    <label class="block text-sm font-medium text-gray-300 mb-1">Inventory Name
                                        <span class="text-red-500">*</span></label>
                                    <input type="text" name="inventory_items[0][name]" required
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring focus:ring-blue-300">
                                    @error('inventory_items.0.name')
                                        <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                                    @enderror
                                </div>

                                <button type="button"
                                    class="remove-item text-white bg-red-600 hover:bg-red-700 px-4 py-2.5 rounded-md text-sm mt-2 md:mt-0">
                                    <i class="fas fa-trash mr-1"></i> Remove
                                </button>
                            </div>
                        </div>

                        <button type="button" id="add-inventory-item"
                            class="mt-3 text-white bg-green-600 hover:bg-green-700 px-4 py-2 rounded-md text-sm">
                            <i class="fas fa-plus mr-1"></i> Add Inventory Item
                        </button>
                    </div>

                    <!-- Submit Button -->
                    <div class="text-right mt-8">
                        <button type="submit" id="submitButton"
                            class="inline-flex items-center px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-md">
                            <i class="fas fa-save mr-2"></i>Add Product
                        </button>
                    </div>

                    <!-- Success Message -->
                    @if (session('success'))
                        <div class="mt-6">
                            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative">
                                {{ session('success') }}
                                <button type="button" onclick="this.parentElement.style.display='none'"
                                    class="absolute top-2 right-2 text-xl leading-none">&times;</button>
                            </div>
                        </div>
                    @endif
                </form>
            </div>
        </div>
    </div>
</x-app-layout>

<script src="{{ asset('js/add_product.js') }}"></script>