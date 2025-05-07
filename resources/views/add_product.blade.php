<script src="https://unpkg.com/preline@latest/dist/preline.js"></script>

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            {{ __('Add Product') }}
        </h2>
    </x-slot>

    <!-- Toast Notifications -->
    <div class="fixed top-4 right-4 z-50 space-y-4">
        @if(session('success'))
            <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 10000)" x-show="show"
                class="bg-green-600 text-white text-sm font-medium px-5 py-3 rounded-lg shadow-lg flex items-start gap-4">
                <span>{{ session('success') }}</span>
                <button @click="show = false" class="text-white hover:text-gray-300 ml-2 text-lg leading-none">
                    &times;
                </button>
            </div>
        @endif
    </div>

    <div class="py-10">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-gray-800 shadow-md rounded-lg p-8">
                <form id="productForm" class="form" method="POST" action="{{ route('products.store') }}"
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
                            <label class="block text-sm font-medium text-gray-300">Product Group
                                <span class="text-red-500">*</span></label>
                            <input type="text" name="product_group" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring focus:ring-blue-300"
                                value="{{ old('product_group') }}" placeholder="Noodles" autocomplete="off">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-300">Product Name
                                <span class="text-red-500">*</span></label>
                            <input type="text" name="product_name" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring focus:ring-blue-300"
                                value="{{ old('product_name') }}" placeholder="Samyang (spicy)" autocomplete="off">
                        </div>

                        <div class="mb-2">
                            <input id="product-consumable-checkbox" name="product_consumable_checkbox" type="checkbox"
                                value=""
                                class="w-4 h-4 text-blue-600 rounded-sm focus:ring-blue-600 ring-offset-gray-800 focus:ring-2 bg-gray-700 border-gray-600">
                            <label for="product-consumable-checkbox" class="ms-2 text-sm font-medium text-gray-300">This
                                product has no consumable item.</label>
                        </div>

                        <div>
                            <div class="mb-2">
                                <input id="product-required-stock-checkbox" name="product_required_stock_checkbox"
                                    type="checkbox" value=""
                                    class="w-4 h-4 text-blue-600 rounded-sm focus:ring-blue-600 ring-offset-gray-800 focus:ring-2 bg-gray-700 border-gray-600">
                                <label for="product-required-stock-checkbox"
                                    class="ms-2 text-sm font-medium text-gray-300">This
                                    product
                                    required stock.</label>
                            </div>

                            <!-- Quantity Input -->
                            <div class="relative flex items-center w-[11rem]">
                                <!-- Decrement Button -->
                                <button type="button"
                                    class="stock-decrement bg-gray-100 dark:bg-gray-700 dark:hover:bg-gray-600 border-gray-600 hover:bg-gray-200 border rounded-s-lg p-3 h-11 focus:ring-blue-300 focus:ring-2 focus:outline-none">
                                    <svg class="w-3 h-3 text-gray-900 dark:text-white"
                                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 18 2">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                            stroke-width="2" d="M1 1h16" />
                                    </svg>
                                </button>

                                <!-- Quantity Input -->
                                <input type="text" name="required_stock" value="1" min="1"
                                    class="stock-required bg-gray-50 border-x-0 h-11 font-medium text-center text-gray-900 text-sm focus:ring-blue-300 block w-full pb-6 border-gray-600 placeholder-gray-400 focus:border-blue-300"
                                    required>

                                <!-- Label -->
                                <div
                                    class="absolute bottom-1 start-1/2 -translate-x-1/2 flex items-center text-xs text-gray-900">
                                    <span>Stocks</span>
                                </div>

                                <!-- Increment Button -->
                                <button type="button"
                                    class="stock-increment bg-gray-100 dark:bg-gray-700 dark:hover:bg-gray-600 border-gray-600 hover:bg-gray-200 border rounded-e-lg p-3 h-11 focus:ring-blue-300 focus:ring-2 focus:outline-none">
                                    <svg class="w-3 h-3 text-gray-900 dark:text-white"
                                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 18 18">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                            stroke-width="2" d="M9 1v16M1 9h16" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>

                    @php
                        $isConsumableListEmpty = $consumable_list->groupBy('consumable_name')->sortBy('consumable_name')->isEmpty();
                    @endphp

                    <!-- Consumables Items -->
                    <div class="mt-8">
                        <h4 class="text-lg font-medium text-gray-200 mb-4">Select Consumable Items:</h4>

                        <div id="consumable-items-container"
                            data-is-empty="{{ $isConsumableListEmpty ? 'true' : 'false' }}">
                            <div class="flex flex-col md:flex-row items-start md:items-end mb-4 gap-4 consumable-item"
                                data-index="0">
                                <div class="flex-1">
                                    <label class="block text-sm font-medium text-gray-300 mb-1">Consumable Name [1]
                                        <span class="text-red-500">*</span>
                                    </label>

                                    <div class="flex flex-wrap items-center gap-3">
                                        <!-- Select with Search - Now with appropriate width -->
                                        <div class="flex-1 min-w-[200px]">
                                            <select id="consumable-select-0" name="consumable[0][name]"
                                                onchange="validateConsumableSelection(this)" data-hs-select='{
                                        "placeholder": "Search consumable...",
                                        "hasSearch": true,
                                        "searchPlaceholder": "Search consumables...",
                                        "searchClasses": "block w-full sm:text-sm border-gray-200 rounded-lg focus:border-blue-500 focus:ring-blue-500 before:absolute before:inset-0 before:z-1 dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400 dark:placeholder-neutral-500 py-1.5 sm:py-2 px-3",
                                        "searchWrapperClasses": "bg-white p-2 -mx-1 sticky top-0 dark:bg-neutral-900",
                                        "toggleTag": "<button type=\"button\" aria-expanded=\"false\"></button>",
                                        "toggleClasses": "hs-select-disabled:pointer-events-none hs-select-disabled:opacity-50 relative py-3 ps-4 pe-9 flex gap-x-2 text-nowrap w-full cursor-pointer bg-white border border-gray-200 rounded-lg text-start text-sm focus:outline-hidden focus:ring-2 focus:ring-blue-500 dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400 dark:focus:outline-hidden dark:focus:ring-1 dark:focus:ring-neutral-600",
                                        "dropdownClasses": "mt-2 z-50 w-full max-h-72 pb-1 px-1 space-y-0.5 bg-white border border-gray-200 rounded-lg overflow-hidden overflow-y-auto [&::-webkit-scrollbar]:w-2 [&::-webkit-scrollbar-thumb]:rounded-full [&::-webkit-scrollbar-track]:bg-gray-100 [&::-webkit-scrollbar-thumb]:bg-gray-300 dark:[&::-webkit-scrollbar-track]:bg-neutral-700 dark:[&::-webkit-scrollbar-thumb]:bg-neutral-500 dark:bg-neutral-900 dark:border-neutral-700",
                                        "optionClasses": "py-2 px-4 w-full text-sm text-gray-800 cursor-pointer hover:bg-gray-100 rounded-lg focus:outline-hidden focus:bg-gray-100 dark:bg-neutral-900 dark:hover:bg-neutral-800 dark:text-neutral-200 dark:focus:bg-neutral-800",
                                        "optionTemplate": "<div class=\"flex justify-between items-center w-full\"><span data-title></span><span class=\"hidden hs-selected:block\"><svg class=\"shrink-0 size-3.5 text-blue-600 dark:text-blue-500\" xmlns=\"http://www.w3.org/2000/svg\" width=\"24\" height=\"24\" viewBox=\"0 0 24 24\" fill=\"none\" stroke=\"currentColor\" stroke-width=\"2\" stroke-linecap=\"round\" stroke-linejoin=\"round\"><polyline points=\"20 6 9 17 4 12\"/></svg></span></div>",
                                        "extraMarkup": "<div class=\"absolute top-1/2 end-3 -translate-y-1/2\"><svg class=\"shrink-0 size-3.5 text-gray-500 dark:text-neutral-500\" xmlns=\"http://www.w3.org/2000/svg\" width=\"24\" height=\"24\" viewBox=\"0 0 24 24\" fill=\"none\" stroke=\"currentColor\" stroke-width=\"2\" stroke-linecap=\"round\" stroke-linejoin=\"round\"><path d=\"m7 15 5 5 5-5\"/><path d=\"m7 9 5-5 5 5\"/></svg></div>"
                                    }' class="hidden selection">
                                                <option value="">Select a consumable</option>
                                                @foreach($consumable_list->groupBy('consumable_name')->sortBy('consumable_name') as $name => $consumables)
                                                    <option value="{{ $consumables->first()->id }}">{{ $name }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <!-- Quantity Input -->
                                        <div class="relative flex items-center w-[11rem]">
                                            <!-- Decrement Button -->
                                            <button type="button"
                                                class="decrement bg-gray-100 dark:bg-gray-700 dark:hover:bg-gray-600 border-gray-600 hover:bg-gray-200 border rounded-s-lg p-3 h-11 focus:ring-blue-300 focus:ring-2 focus:outline-none {{ $isConsumableListEmpty ? 'opacity-50 pointer-events-none' : '' }}">
                                                <svg class="w-3 h-3 text-gray-900 dark:text-white"
                                                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 18 2">
                                                    <path stroke="currentColor" stroke-linecap="round"
                                                        stroke-linejoin="round" stroke-width="2" d="M1 1h16" />
                                                </svg>
                                            </button>

                                            <!-- Quantity Input -->
                                            <input type="text" name="consumable[0][quantity_needed]" value="1" min="1"
                                                class="quantity-needed bg-gray-50 border-x-0 h-11 font-medium text-center text-gray-900 text-sm focus:ring-blue-300 block w-full pb-6 border-gray-600 placeholder-gray-400 focus:border-blue-300 {{ $isConsumableListEmpty ? 'opacity-50 pointer-events-none bg-gray-100' : '' }}"
                                                required>

                                            <!-- Label -->
                                            <div
                                                class="absolute bottom-1 start-1/2 -translate-x-1/2 flex items-center text-xs text-gray-900">
                                                <span>Quantity</span>
                                            </div>

                                            <!-- Increment Button -->
                                            <button type="button"
                                                class="increment bg-gray-100 dark:bg-gray-700 dark:hover:bg-gray-600 border-gray-600 hover:bg-gray-200 border rounded-e-lg p-3 h-11 focus:ring-blue-300 focus:ring-2 focus:outline-none {{ $isConsumableListEmpty ? 'opacity-50 pointer-events-none' : '' }}"
                                                {{ $isConsumableListEmpty ? 'disabled' : '' }}>
                                                <svg class="w-3 h-3 text-gray-900 dark:text-white"
                                                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 18 18">
                                                    <path stroke="currentColor" stroke-linecap="round"
                                                        stroke-linejoin="round" stroke-width="2" d="M9 1v16M1 9h16" />
                                                </svg>
                                            </button>
                                        </div>

                                        @php $isFirstIndex = 0 === 0; @endphp

                                        <button type="button"
                                            class="remove-item text-white bg-red-600 hover:bg-red-700 px-4 py-2.5 rounded-md text-sm {{ $isFirstIndex ? 'opacity-0 cursor-not-allowed' : '' }}"
                                            {{ $isFirstIndex ? 'disabled' : '' }}>
                                            <i class="fas fa-trash mr-1"></i> Remove
                                        </button>
                                    </div>
                                    <div class="select-error text-red-500 text-sm mt-1" style="display: none;">Please
                                        select a consumable item</div>
                                </div>
                            </div>
                        </div>

                        @if(session('error'))
                            <div class="text-red-500 text-sm mt-1">
                                <span>{{ session('error') }}</span>
                            </div>
                        @endif

                        @if ($isConsumableListEmpty)
                            <p class="text-sm font-semibold text-red-400 mb-3 {{ old('product_consumable_checkbox') ? 'hidden' : '' }}"
                                data-no-consumables>
                                There is no consumables listed yet.
                                <a href="{{ route('add.consumable') }}" class="underline text-blue-300 hover:text-blue-400">
                                    List Consumables
                                </a>
                            </p>
                        @endif


                        <button type="button" id="add-consumable-item"
                            data-is-empty="{{ $isConsumableListEmpty ? 'true' : 'false' }}"
                            class="text-white bg-blue-600 hover:bg-blue-700 px-4 py-2 rounded-md text-sm add-consumable-item {{ $isConsumableListEmpty ? 'opacity-50 cursor-not-allowed' : '' }}"
                            {{ $isConsumableListEmpty ? 'disabled' : '' }}>
                            <i class="fas fa-plus mr-1"></i> Add Consumable Item
                        </button>

                    </div>

                    <div class="grid gap-6 mb-6 md:grid-cols-2 mt-8">
                        <div>
                            <label class="block text-medium font-semibold text-yellow-300">Price
                                <span class="text-red-500">*</span></label>
                            <input type="number" name="product_price" min="1" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring focus:ring-blue-300"
                                value="{{ old('product_price') }}">
                        </div>

                        <div class="flex justify-end items-end h-full">
                            <button type="submit" id="submitButton"
                                class="inline-flex items-center px-6 py-2 bg-green-600 hover:bg-green-700 text-white rounded-md">
                                <i class="fas fa-save mr-2"></i>Add Product
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>

<script src="{{ asset('js/add_product.js') }}">
    document.getElementById("submitButton").addEventListener("click", function (event) {
        let isValid = true;
        const containers = document.querySelectorAll(".consumable-item");

        containers.forEach(container => {
            const select = container.querySelector("select");
            const errorMsg = container.querySelector(".select-error");

            if (select.value === "") {
                errorMsg.classList.remove("hidden");
                isValid = false;
            } else {
                errorMsg.classList.add("hidden");
            }
        });

        if (!isValid) {
            event.preventDefault(); // Prevent form submission
            // Optionally scroll to first error
            const firstError = document.querySelector(".select-error:not(.hidden)");
            firstError?.scrollIntoView({ behavior: "smooth", block: "center" });
        }
    });
</script>