<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            {{ __('Orders') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-100">

                    <!-- Date Range Filter -->
                    <div class="bg-gray-900 rounded-lg p-6 mb-6">
                        <h2 class="text-xl font-bold text-white mb-4">Filter Orders by Date</h2>

                        <!-- Date Picker Form -->
                        <form action="{{ route('orders.filter') }}" method="GET">
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <!-- Start Date Picker -->
                                <div class="flex flex-col">
                                    <label for="start_date" class="text-sm font-medium text-gray-300">Start Date</label>

                                    <div class="relative mt-2">
                                        <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400 pointer-events-none"
                                            xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                            <path
                                                d="M20 4a2 2 0 0 0-2-2h-2V1a1 1 0 0 0-2 0v1h-3V1a1 1 0 0 0-2 0v1H6V1a1 1 0 0 0-2 0v1H2a2 2 0 0 0-2 2v2h20V4ZM0 18a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8H0v10Zm5-8h10a1 1 0 0 1 0 2H5a1 1 0 0 1 0-2Z" />
                                        </svg>

                                        <input id="start_date" name="start_date" type="date"
                                            value="{{ request('start_date') }}"
                                            class="ps-10 py-2 pe-3 w-full bg-gray-700 text-gray-100 rounded-md placeholder-gray-400 focus:ring-blue-500 focus:border-blue-500"
                                            placeholder="Select Start Date" required />
                                    </div>
                                </div>


                                <!-- End Date Picker -->
                                <div class="flex flex-col">
                                    <label for="end_date" class="text-sm font-medium text-gray-300">End Date</label>

                                    <div class="relative mt-2">
                                        <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400 pointer-events-none"
                                            xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                            <path
                                                d="M20 4a2 2 0 0 0-2-2h-2V1a1 1 0 0 0-2 0v1h-3V1a1 1 0 0 0-2 0v1H6V1a1 1 0 0 0-2 0v1H2a2 2 0 0 0-2 2v2h20V4ZM0 18a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8H0v10Zm5-8h10a1 1 0 0 1 0 2H5a1 1 0 0 1 0-2Z" />
                                        </svg>

                                        <input id="end_date" name="end_date" type="text"
                                            value="{{ request('end_date') }}"
                                            class="ps-10 py-2 pe-3 w-full bg-gray-700 text-gray-100 rounded-md placeholder-gray-400 focus:ring-blue-500 focus:border-blue-500"
                                            placeholder="Select End Date" required />
                                    </div>
                                </div>
                            </div>

                            <!-- Buttons -->
                            <div class="mt-4 flex justify-between">
                                <button type="submit"
                                    class="px-4 py-2 bg-blue-600 hover:bg-blue-500 text-white rounded-md font-semibold">
                                    Apply Filters
                                </button>
                                <a href="{{ route('sales_report') }}"
                                    class="px-4 py-2 bg-gray-600 hover:bg-gray-500 text-white rounded-md font-semibold">
                                    Reset
                                </a>
                            </div>
                        </form>
                    </div>

                    @php
                        $groupedOrders = $orders->groupBy(function ($order) {
                            return $order->created_at->format('Y-m-d');
                        });
                    @endphp

                    @if ($groupedOrders->isEmpty())
                        <div class="text-center text-gray-300 py-10">
                            <p class="text-lg font-medium">No orders found</p>
                            <p class="text-sm text-gray-400 mt-1">There are no orders to display</p>
                        </div>
                    @else
                        @foreach ($groupedOrders as $date => $orderGroup)
                            <div class="mb-6">
                                <h2 class="text-xl font-bold text-white mb-2 border-b border-gray-700 pb-1">
                                    Orders on {{ \Carbon\Carbon::parse($date)->format('F j, Y') }}
                                </h2>

                                <div class="bg-gray-900 rounded-lg overflow-hidden">
                                    <table class="min-w-full divide-y divide-gray-700">
                                        <thead>
                                            <tr>
                                                <th
                                                    class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">
                                                    Order ID
                                                </th>
                                                <th
                                                    class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">
                                                    Staff ID
                                                </th>
                                                <th
                                                    class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">
                                                    Total Amount
                                                </th>
                                                <th
                                                    class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">
                                                    Payment Method
                                                </th>
                                                <th
                                                    class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">
                                                    Time Ordered
                                                </th>
                                                <th
                                                    class="px-6 py-3 text-right text-xs font-medium text-gray-300 uppercase tracking-wider">
                                                    Actions
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-gray-700">
                                            @foreach ($orderGroup as $order)
                                                <tr class="hover:bg-gray-800 transition-colors duration-150">
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">
                                                        {{ $order->id }}
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-white font-medium">
                                                        {{ $order->staff_id ?? 'N/A' }}
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-white font-medium">
                                                        ₱{{ number_format($order->total_amount, 2) }}
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-white font-medium">
                                                        {{ $order->payment_method ?? 'N/A' }}
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-white font-medium">
                                                        {{ $order->created_at->format('h:i A') }}
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-right">
                                                        <!-- Button -->
                                                        <button type="button"
                                                            onclick="document.getElementById('orderModal-{{ $order->id }}').classList.remove('hidden'); document.getElementById('orderModal-{{ $order->id }}').classList.add('flex')"
                                                            class="inline-flex items-center px-3 py-1.5 bg-blue-700 hover:bg-blue-600 text-white text-sm font-medium rounded">
                                                            View Details
                                                        </button>

                                                        <!-- Include the Modal -->
                                                        @include('modals.viewOrderModal', ['order' => $order])

                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        @endforeach
                    @endif

                    <!-- Pagination -->
                    <div class="mt-4">
                        {{ $orders->links() }}
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>

<script>
    // Initialize Flatpickr for Start Date and End Date
    flatpickr("#start_date", {
        dateFormat: "Y-m-d", // Format the date as YYYY-MM-DD
        maxDate: "today" // Prevent selection of future dates
    });
    flatpickr("#end_date", {
        dateFormat: "Y-m-d", // Format the date as YYYY-MM-DD
        maxDate: "today" // Prevent selection of future dates
    });
</script>