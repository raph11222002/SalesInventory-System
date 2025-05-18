<x-app-layout>
    <x-slot name="header">

        <div class="flex">
            <div class="font-medium text-white">
                <x-nav-link :href="route('consumable_list')" :active="request()->routeIs('consumable_list')">
                    {{ __('Consumable Item List') }}
                </x-nav-link>
            </div>

            <div class="font-medium text-white sm:ms-5">
                <x-nav-link :href="route('add.consumable')" :active="request()->routeIs('add.consumable')">
                    {{ __('List Consumable Item') }}
                </x-nav-link>
            </div>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-gray-800 shadow-md rounded-lg p-8">
                <form id="productForm" method="POST" action="{{ route('consumable.store') }}"
                    enctype="multipart/form-data">
                    @csrf

                    <!-- Consumables Items -->

                    <h4 class="text-lg font-medium text-gray-200 mb-4">Consumable Items</h4>

                    <div id="consumable-items-container">
                        <div class="flex flex-col md:flex-row items-start md:items-end mb-4 gap-4 consumable-item">
                            <div class="flex-1">
                                <label class="block text-sm font-medium text-gray-300 mb-1">Consumable Name
                                    <span class="text-red-500">*</span></label>
                                <input type="text" name="consumable[${currentIndex}][name]" required autocomplete="off"
                                    value="{{ old('consumable[${currentIndex}][name]') }}" placeholder="Cup (12oz)"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring focus:ring-blue-300">
                            </div>

                            <button type="button"
                                class="remove-item text-white bg-red-600 hover:bg-red-700 px-4 py-2.5 rounded-md text-sm mt-2 md:mt-0">
                                <i class="fas fa-trash mr-1"></i> Remove
                            </button>
                        </div>
                    </div>

                    <button type="button" id="add-consumable-item"
                        class="mt-3 text-white bg-blue-600 hover:bg-blue-700 px-4 py-2 rounded-md text-sm">
                        <i class="fas fa-plus mr-1"></i> Add Consumable Item
                    </button>

                    <!-- Submit Button -->
                    <div class="text-right mt-8">
                        <button type="submit" id="submitButton"
                            class="inline-flex items-center px-6 py-2 bg-green-600 hover:bg-green-700 text-white rounded-md">
                            <i class="fas fa-save mr-2"></i>List Consumables
                        </button>
                    </div>

                    <!-- Success Message -->
                    @if (session('success'))
                        <div class="mt-6">
                            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative">
                                {{ session('success') }}
                                <button type="button" onclick="this.parentElement.style.display='none'"
                                    class="absolute top-2 right-2 text-xl leading-none">&times;</button>

                                <a href="{{ route('add_product') }}" class="underline text-blue-600 hover:text-blue-700">
                                    Add Product Now.
                                </a>
                            </div>
                        </div>
                    @endif

                    <!-- Duplicate or custom error (from session) -->
                    @if (session('error'))
                        <div class="mt-6">
                            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative">
                                {{ session('error') }}
                                <button type="button" onclick="this.parentElement.style.display='none'"
                                    class="absolute top-2 right-2 text-xl leading-none">&times;</button>
                            </div>
                        </div>
                    @endif

                    <!-- Validation errors (from $errors) -->
                    @if ($errors->any())
                        <div class="mt-6">
                            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative">
                                <ul class="list-disc list-inside text-sm text-red-700">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
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

<script src="{{ asset('js/add_consumable.js') }}"></script>