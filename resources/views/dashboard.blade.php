<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-white leading-tight">
                {{ __('Dashboard') }}
            </h2>
            <div class="flex items-center gap-4">
                <!-- Dark Mode Toggle -->
                <div class="flex items-center">
                    <span class="text-sm text-gray-300 mr-2 hidden sm:inline">Theme</span>
                    <button id="theme-toggle" type="button"
                        class="text-gray-400 hover:text-white focus:outline-none focus:ring-2 focus:ring-gray-600 rounded-lg text-sm p-2">
                        <!-- Sun icon (light mode) -->
                        <svg id="theme-toggle-light-icon" class="hidden w-5 h-5" fill="currentColor" viewBox="0 0 20 20"
                            xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z"
                                fill-rule="evenodd" clip-rule="evenodd"></path>
                        </svg>
                        <!-- Moon icon (dark mode) -->
                        <svg id="theme-toggle-dark-icon" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"
                            xmlns="http://www.w3.org/2000/svg">
                            <path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"></path>
                        </svg>
                    </button>
                </div>
                <div class="text-sm text-gray-300">
                    <span id="current-date"></span>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Sales Overview Container -->
            <div
                class="bg-white dark:bg-gray-800 shadow-lg rounded-xl p-6 mb-6 border border-gray-200 dark:border-gray-700">
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
                    <h2 class="text-2xl font-bold text-gray-800 dark:text-white flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2 text-blue-500" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                        Sales Overview
                    </h2>

                    <!-- Toggle Buttons -->
                    <div class="flex bg-gray-600 p-1 rounded-lg shadow-inner self-start sm:self-auto">
                        <button
                            class="px-4 py-2 rounded-md text-sm font-medium transition-all duration-200 filter-btn text-gray-300 hover:bg-gray-500 active"
                            id="btn-today" onclick="setFilter('today', '{{ route('show.dashboard') }}')">Today</button>
                        <button
                            class="px-4 py-2 rounded-md text-sm font-medium transition-all duration-200 filter-btn text-gray-300 hover:bg-gray-500"
                            id="btn-last3days" onclick="setFilter('last3days', '{{ route('show.dashboard') }}')">Last 3
                            Days</button>
                        <button
                            class="px-4 py-2 rounded-md text-sm font-medium transition-all duration-200 filter-btn text-gray-300 hover:bg-gray-500"
                            id="btn-lifetime"
                            onclick="setFilter('lifetime', '{{ route('show.dashboard') }}')">Lifetime</button>
                    </div>
                </div>

                <!-- Stats Cards -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Total Sales -->
                    <div
                        class="bg-gradient-to-br from-blue-500 to-blue-700 text-white rounded-xl p-6 shadow-lg relative overflow-hidden">
                        <div class="absolute right-0 top-0 opacity-10">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-32 w-32" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <p class="text-sm font-medium text-blue-100">Total Sales</p>
                        <div class="flex items-baseline mt-1">
                            <h3 class="text-3xl font-extrabold">₱{{ $totalSales }}</h3>
                            <span class="ml-2 text-sm text-blue-200" id="sales-period">Today</span>
                        </div>
                    </div>

                    <!-- Total Orders -->
                    <div
                        class="bg-gradient-to-br from-purple-500 to-purple-700 text-white rounded-xl p-6 shadow-lg relative overflow-hidden">
                        <div class="absolute right-0 top-0 opacity-10">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-32 w-32" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                            </svg>
                        </div>
                        <p class="text-sm font-medium text-purple-100">Total Orders</p>
                        <div class="flex items-baseline mt-1">
                            <h3 class="text-3xl font-extrabold">{{ $totalOrders }}</h3>
                            <span class="ml-2 text-sm text-purple-200" id="orders-period">Today</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Inventory and Performance Container -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                <!-- Product Performance -->
                <div
                    class="bg-white dark:bg-gray-800 shadow-lg rounded-xl p-6 border border-gray-200 dark:border-gray-700">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-bold text-gray-800 dark:text-white flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-green-500" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                            </svg>
                            Product Performance
                        </h3>

                        <span class="ml-2 text-sm text-gray-800 dark:text-white" id="performance-period">Today</span>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full">
                            <thead class="bg-gray-100 dark:bg-gray-700">
                                <tr>
                                    <th
                                        class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider rounded-l-lg">
                                        Product</th>
                                    <th
                                        class="px-4 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider rounded-r-lg">
                                        Revenue</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-400">
                                @foreach ($productPerformance as $product)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-all duration-150">
                                        <td class="px-4 py-3 text-sm font-medium text-gray-900 dark:text-white rounded-l-lg">
                                            {{ $loop->iteration }}. {{ $product['product_name'] }}
                                        </td>
                                        <td
                                            class="px-4 py-3 text-sm text-right font-semibold text-gray-900 dark:text-white rounded-r-lg">
                                            ₱{{ number_format($product['revenue'], 2) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Inventory Overview -->
                <div class="grid grid-cols-1 gap-6">
                    <!-- Product Stocks -->
                    <div
                        class="bg-white dark:bg-gray-800 shadow-lg rounded-xl p-6 border border-gray-200 dark:border-gray-700">
                        <div class="flex justify-between items-center mb-6">
                            <h3 class="text-lg font-bold text-gray-800 dark:text-white flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-yellow-500" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                </svg>
                                Product Stocks
                            </h3>
                            <a href="{{ route('product.stock.view') }}" class="text-sm text-blue-600 dark:text-blue-400 hover:underline">View product stock</a>
                        </div>
                        <div class="space-y-3">
                            @foreach ($productWithStock as $product)
                                <div class="flex justify-between items-center">
                                    <span
                                        class="text-sm font-medium text-gray-900 dark:text-white">{{ $product->product_name }}</span>
                                    <span
                                        class="px-3 py-1 text-xs font-medium rounded-full {{ $product->required_stock > 5 ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300' : 'bg-red-200 text-red-800 dark:bg-red-900 dark:text-red-300' }}">
                                        {{ $product->required_stock }}
                                    </span>
                                </div>
                                <div class="border-t border-gray-400 my-4"></div>
                            @endforeach
                        </div>

                        <div class="mt-4 bg-gray-600 text-gray-300 p-2 rounded-lg">
                            {{ $productWithStock->links() }}
                        </div>
                    </div>

                    <!-- Consumable Stocks -->
                    <div
                        class="bg-white dark:bg-gray-800 shadow-lg rounded-xl p-6 border border-gray-200 dark:border-gray-700">
                        <div class="flex justify-between items-center mb-6">
                            <h3 class="text-lg font-bold text-gray-800 dark:text-white flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-red-500" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                                </svg>
                                Consumable Stocks
                            </h3>
                            <a href="{{ route('consumable_list') }}" class="text-sm text-blue-600 dark:text-blue-400 hover:underline">View consumable stock</a>
                        </div>
                        <div class="space-y-3">
                            @foreach ($consumables as $item)
                                <div class="flex justify-between items-center">
                                    <span
                                        class="text-sm font-medium text-gray-900 dark:text-white">{{ $item->consumable_name }}</span>
                                    <span
                                        class="px-3 py-1 text-xs font-medium rounded-full {{ $item->total_stock_left > 5 ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300' : 'bg-red-200 text-red-800 dark:bg-red-900 dark:text-red-300' }}">
                                        {{ $item->total_stock_left }}
                                    </span>
                                </div>
                                <div class="border-t border-gray-400 my-4"></div>
                            @endforeach
                        </div>

                        <div class="mt-4 bg-gray-600 text-gray-300 p-2 rounded-lg">
                            {{ $consumables->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Display current date and initialize theme
        document.addEventListener('DOMContentLoaded', function () {
            // Set current date
            const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
            const today = new Date();
            document.getElementById('current-date').textContent = today.toLocaleDateString('en-US', options);

            // Set active filter button based on current filter
            const currentFilter = '{{ $currentFilter }}'; // From controller
            const buttons = document.querySelectorAll('.filter-btn');
            buttons.forEach(btn => {
                btn.classList.remove('active', 'bg-blue-600', 'text-white');
            });

            // Set the active button
            document.getElementById(`btn-${currentFilter}`).classList.add('active', 'bg-blue-600');

            // Update period labels
            const periodLabels = {
                'today': 'Today',
                'last3days': 'Last 3 Days',
                'lifetime': 'Lifetime'
            };
            document.getElementById('sales-period').textContent = periodLabels[currentFilter];
            document.getElementById('orders-period').textContent = periodLabels[currentFilter];
            document.getElementById('performance-period').textContent = periodLabels[currentFilter];

            // Theme toggle functionality
            initializeTheme();
        });

        // Theme initialization and toggle functionality
        function initializeTheme() {
            const themeToggleBtn = document.getElementById('theme-toggle');
            const themeToggleLightIcon = document.getElementById('theme-toggle-light-icon');
            const themeToggleDarkIcon = document.getElementById('theme-toggle-dark-icon');

            // Check for saved theme preference or use system preference
            if (localStorage.getItem('color-theme') === 'dark' ||
                (!localStorage.getItem('color-theme') && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                // Show light icon if dark mode
                themeToggleLightIcon.classList.remove('hidden');
                themeToggleDarkIcon.classList.add('hidden');
                document.documentElement.classList.add('dark');
            } else {
                // Show dark icon if light mode
                themeToggleDarkIcon.classList.remove('hidden');
                themeToggleLightIcon.classList.add('hidden');
                document.documentElement.classList.remove('dark');
            }

            // Toggle theme on click
            themeToggleBtn.addEventListener('click', function () {
                // Toggle icons
                themeToggleDarkIcon.classList.toggle('hidden');
                themeToggleLightIcon.classList.toggle('hidden');

                // Toggle theme class
                if (document.documentElement.classList.contains('dark')) {
                    document.documentElement.classList.remove('dark');
                    localStorage.setItem('color-theme', 'light');
                } else {
                    document.documentElement.classList.add('dark');
                    localStorage.setItem('color-theme', 'dark');
                }
            });
        }

        // Filter function with server-side logic
        function setFilter(period, dashboardRoute) {
            // Update UI first
            const periodLabels = {
                'today': 'Today',
                'last3days': 'Last 3 Days',
                'lifetime': 'Lifetime'
            };

            document.getElementById('sales-period').textContent = periodLabels[period];
            document.getElementById('orders-period').textContent = periodLabels[period];
            document.getElementById('performance-period').textContent = periodLabels[period];

            // Update active button state
            const buttons = document.querySelectorAll('.filter-btn');
            buttons.forEach(btn => {
                btn.classList.remove('active', 'bg-blue-600', 'text-white');
            });
            document.getElementById(`btn-${period}`).classList.add('active', 'bg-blue-600', 'text-white');

            // Redirect to filter the data server-side
            const currentProductPage = new URLSearchParams(window.location.search).get('product_page') || 1;
            const currentConsumablePage = new URLSearchParams(window.location.search).get('consumable_page') || 1;

            window.location.href = `${dashboardRoute}?filter=${period}&product_page=${currentProductPage}&consumable_page=${currentConsumablePage}`;
        }
    </script>

    <style>
        /* Additional styles */
        .filter-btn.active {
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        /* Smooth transitions */
        .transition-all {
            transition-property: all;
            transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
            transition-duration: 150ms;
        }

        /* Dark mode transition */
        html.dark {
            color-scheme: dark;
        }

        /* Dark mode toggle button effects */
        #theme-toggle:hover {
            background-color: rgba(75, 85, 99, 0.3);
        }
    </style>
</x-app-layout>