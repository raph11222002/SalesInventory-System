<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            {{ __('Record Sales') }}
        </h2>
    </x-slot>

    @include('components.toast')

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-gray-800 shadow sm:rounded-lg p-6">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-semibold text-gray-100">Create an Order</h2>
                </div>

                <div class="border-t border-gray-200 my-4"></div>

                @if($groupedProducts->isEmpty())
                    <div class="text-center py-20">
                        @if(auth()->guard('web')->check())
                        <h3 class="text-2xl font-semibold text-white mb-4">No Added Products Yet</h3>

                            <a href="{{ route('add_product') }}"
                                class="inline-block bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-6 rounded-lg transition duration-300">
                                Add Product
                            </a>
                        @elseif(auth()->guard('staff')->check())
                        <h3 class="text-2xl font-semibold text-white mb-4">Admin Doesn't Add Products Yet</h3>
                        @endif
                    </div>
                @else
                    @foreach($groupedProducts as $group => $products)
                        <h2 class="text-2xl font-semibold text-white mb-4">{{ $group }}</h2>
                        <div class="mb-5 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                            @foreach($products as $product)
                                <a href="{{ route('product.order', $product->id) }}"
                                    class="block bg-gray-600 hover:bg-indigo-600 rounded-lg shadow-md p-5 transition duration-300">
                                    <div class="relative w-full pt-[56.25%] border rounded-xl overflow-hidden">
                                        <img class="absolute top-0 left-0 w-full h-full object-cover"
                                            src="{{ asset('storage/' . $product->image_path) }}">
                                    </div>
                                    <h3 class="text-xl font-semibold text-white mb-2">
                                        {{ $product->product_name }}
                                    </h3>
                                    <div class="mb-2">
                                        <p class="text-sm font-semibold text-white mb-1">Consumable:</p>
                                        @if($product->productConsumableNeeded->count() > 0)
                                            <ul class="list-disc list-inside text-sm text-gray-300">
                                                @foreach($product->productConsumableNeeded as $consumable)
                                                    <li>{{ $consumable->quantity_needed }} {{ $consumable->consumable_name }}</li>
                                                @endforeach
                                            </ul>
                                        @else
                                            <p class="text-sm text-gray-400">No Consumables</p>
                                        @endif
                                    </div>
                                    <p class="text-xs text-gray-300 mt-4">Created: {{ $product->created_at->format('Y-m-d H:i') }}
                                    </p>
                                </a>
                            @endforeach
                        </div>
                    @endforeach
                @endif
            </div>
        </div>
    </div>

    @include('product.orderSummary')
</x-app-layout>