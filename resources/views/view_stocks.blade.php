<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('consumable_list') }}"
                class="inline-flex items-center pr-3 py-2 bg-gray-800 hover:bg-gray-700 text-white font-medium rounded text-lg">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5"
                    stroke="currentColor" class="size-7">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5" />
                </svg>
                Back
            </a>

            <h2 class="font-semibold text-xl text-white leading-tight">
                {{ __('Stock Records for ') }}
                <span class="text-green-500">{{ $consumable->consumable_name }}</span>
            </h2>

            <button type="button"
                class="inline-flex items-center px-8 py-3 bg-yellow-900 hover:bg-yellow-700 text-white text-sm font-medium rounded ml-auto"
                onclick="document.getElementById('stockModal-{{ $consumable->id }}').classList.remove('hidden')">
                <svg class="h-4 w-4 mr-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path
                        d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                </svg>
                Add Stock
            </button>

            @include('modals.stockModal', ['inventory' => $consumable])
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
                                    Date Received
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
                                        {{ \Carbon\Carbon::parse($stock->date_received)->format('Y-m-d') }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <div class="mt-4">
                        {{ $stocks->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>