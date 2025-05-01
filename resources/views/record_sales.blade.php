<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            {{ __('Record Sales') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-gray-800 shadow sm:rounded-lg p-6">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-semibold text-gray-100">Products List</h2>
                    <a href="{{ route('add_product') }}"
                        class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium">Add
                        New Product</a>
                </div>

                <div class="border-t border-gray-200 my-4"></div>

                @if (session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                        {{ session('success') }}
                    </div>
                @endif

                @if (session('error'))
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                        {{ session('error') }}
                    </div>
                @endif

                @if($products->count() > 0)
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                        @foreach($products as $product)
                            <div class="bg-gray-600 hover:bg-indigo-600 rounded-lg shadow-md p-5">
                                <div class="relative w-full pt-[56.25%] border rounded-xl overflow-hidden">
                                    <img class="absolute top-0 left-0 w-full h-full object-cover" src="{{ asset('storage/' . $product->image_path) }}">
                                </div>
                                <h3 class="text-xl font-semibold text-white mb-2">
                                    {{ $product->product_name }}
                                </h3>
                                <p class="text-sm text-gray-200 mb-1"><span class="font-semibold text-white">Group:</span>
                                    {{ $product->product_group }}</p>
                                <div class="mb-2">
                                    <p class="text-sm font-semibold text-white mb-1">Inventories:</p>
                                    @if($product->inventories->count() > 0)
                                        <ul class="list-disc list-inside text-sm text-gray-300">
                                            @foreach($product->inventories as $inventory)
                                                <li>{{ $inventory->inventory_name }}</li>
                                            @endforeach
                                        </ul>
                                    @else
                                        <p class="text-sm text-gray-400">No inventories</p>
                                    @endif
                                </div>
                                <p class="text-xs text-gray-300 mt-4">Created: {{ $product->created_at->format('Y-m-d H:i') }}
                                </p>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="bg-yellow-100 border border-yellow-400 text-yellow-800 px-4 py-3 rounded">No products found.
                    </div>
                @endif
            </div>
        </div>

</x-app-layout>