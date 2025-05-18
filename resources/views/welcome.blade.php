<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

    <!-- Styles / Scripts -->
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
    @endif
</head>

<x-auth-session-status class="mb-4" :status="session('status')" />


<body class="relative min-h-screen flex items-center justify-center p-4">
        <div class="absolute inset-0 bg-cover bg-center z-0" style="background-image: url('{{ asset('storage/logo/background_image.jpg') }}');">
        <div class="w-full h-full bg-black/50"></div>
    </div>

    <header class="w-full lg:max-w-4xl max-w-[335px] text-sm mb-6 not-has-[nav]:hidden">
        <nav class="flex items-center justify-end gap-4">
            @auth
                <a href="{{ url('/dashboard') }}"
                    class="inline-block px-5 py-1.5 text-[#EDEDEC] border-[#19140035] hover:border-[#1915014a] border-[#3E3E3A] hover:border-[#62605b] rounded-sm text-sm leading-normal">
                    Record Sales
                </a>
            @else
                <div
                    class="bg-white shadow-2xl rounded-2xl w-full max-w-4xl overflow-hidden transform transition-all duration-300 hover:scale-[1.01]">
                    <div class="flex justify-center py-6 bg-gray-50 border-b">
                        <div class="relative flex w-[200px] h-12 bg-gray-200 rounded-full p-1 overflow-hidden">
                            <!-- Animated Toggle Indicator -->
                            <div id="toggleIndicator"
                                class="absolute top-1 left-1 h-10 w-1/2 bg-blue-700 rounded-full transition-transform duration-500 ease-in-out z-0">
                            </div>

                            <!-- Staff Radio -->
                            <input type="radio" name="login-type" id="staffLogin-radio" value="staff" class="hidden"
                                checked>
                            <label for="staffLogin-radio" id="staffLogin-label"
                                class="flex-1 text-center z-10 relative cursor-pointer flex items-center justify-center text-sm font-medium text-gray-700 transition-colors duration-300">
                                Staff
                            </label>

                            <!-- Admin Radio -->
                            <input type="radio" name="login-type" id="adminLogin-radio" value="admin" class="hidden">
                            <label for="adminLogin-radio" id="adminLogin-label"
                                class="flex-1 text-center z-10 relative cursor-pointer flex items-center justify-center text-sm font-medium text-gray-700 transition-colors duration-300">
                                Admin
                            </label>
                        </div>
                    </div>

                    <!-- Login Container -->
                    <div class="flex relative">
                        <!-- Staff Login -->
                        <div id="staffLogin" class="w-1/2 px-8 py-10 transition-all duration-300 relative">
                            <div class="absolute top-4 left-4 text-blue-500">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                    <circle cx="12" cy="7" r="4"></circle>
                                </svg>
                            </div>
                            <h2 class="text-2xl font-bold mb-6 text-center text-gray-800">Staff Login</h2>
                            <form method="POST" action="{{ route('staff.login') }}" class="space-y-4">
                                @csrf
                                <div>
                                    <label class="block text-gray-700 text-sm font-bold mb-2">Username</label>
                                    <input type="text" name="username"
                                        class="w-full px-4 py-3 border border-gray-700 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 transition duration-300">
                                    <x-input-error :messages="$errors->get('username')" class="mt-2" />
                                </div>
                                <div>
                                    <label class="block text-gray-700 text-sm font-bold mb-2">Password</label>
                                    <input type="password" name="password"
                                        class="w-full px-4 py-3 border border-gray-700 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 transition duration-300">
                                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                                </div>
                                <button type="submit"
                                    class="w-full bg-blue-500 text-white py-3 rounded-lg hover:bg-blue-600 transition duration-300 transform hover:scale-[1.02] focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50">
                                    Login
                                </button>
                            </form>
                        </div>

                        <!-- Vertical Divider -->
                        <div class="w-px bg-gray-600 my-4"></div>

                        <!-- Admin Login -->
                        <div id="adminLogin"
                            class="w-1/2 px-8 py-10 pointer-events-none transition-all duration-300 relative">
                            <div class="absolute top-4 left-4 text-green-500">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                                    <circle cx="8.5" cy="7" r="4"></circle>
                                    <path d="M20 8v6"></path>
                                    <path d="M23 11h-6"></path>
                                </svg>
                            </div>
                            <h2 class="text-2xl font-bold mb-6 text-center text-gray-800">Admin Login</h2>
                            <form method="POST" action="{{ route('admin.login') }}" class="space-y-4">
                                @csrf
                                <div>
                                    <label class="block text-gray-700 text-sm font-bold mb-2">Email</label>
                                    <input id="email" type="text" name="email"
                                        class="w-full px-4 py-3 border border-gray-700 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 transition duration-300">
                                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                                </div>
                                <div>
                                    <label class="block text-gray-700 text-sm font-bold mb-2">Password</label>
                                    <input id="password" type="password" name="password"
                                        class="w-full px-4 py-3 border border-gray-700 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 transition duration-300"
                                        autocomplete="current-password">
                                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                                </div>

                                <div class="flex mt-4 justify-between">
                                    @if (Route::has('password.request'))
                                        <a class="underline text-sm text-gray-700 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 focus:ring-offset-gray-800"
                                            href="{{ route('password.request') }}">
                                            {{ __('Forgot your password?') }}
                                        </a>
                                    @endif
                                </div>

                                <div class="flex items-center justify-end mt-4">

                                    <button type="submit"
                                        class="w-full bg-green-500 text-white py-3 rounded-lg hover:bg-green-600 transition duration-300 transform hover:scale-[1.02] focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-opacity-50">
                                        Login
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            @endauth
        </nav>
    </header>
</body>

<script>
    const staffRadio = document.getElementById('staffLogin-radio');
    const adminRadio = document.getElementById('adminLogin-radio');
    const staffLabel = document.getElementById('staffLogin-label');
    const adminLabel = document.getElementById('adminLogin-label');
    const toggleIndicator = document.getElementById('toggleIndicator');
    const staffLoginContent = document.getElementById('staffLogin');
    const adminLoginContent = document.getElementById('adminLogin');

    function updateToggle(save = true) {
        if (staffRadio.checked) {
            if (save) localStorage.setItem('loginType', 'staff');

            toggleIndicator.style.transform = 'translateX(0%)';
            staffLabel.classList.add('text-white');
            adminLabel.classList.remove('text-white');

            staffLoginContent.classList.remove('opacity-50', 'pointer-events-none');
            adminLoginContent.classList.add('opacity-50', 'pointer-events-none');
        } else {
            if (save) localStorage.setItem('loginType', 'admin');

            toggleIndicator.style.transform = 'translateX(95%)';
            staffLabel.classList.remove('text-white');
            adminLabel.classList.add('text-white');

            staffLoginContent.classList.add('opacity-50', 'pointer-events-none');
            adminLoginContent.classList.remove('opacity-50', 'pointer-events-none');
        }
    }

    // Restore from localStorage on page load
    window.addEventListener('DOMContentLoaded', () => {
        const saved = localStorage.getItem('loginType');
        if (saved === 'admin') {
            adminRadio.checked = true;
        } else {
            staffRadio.checked = true;
        }
        updateToggle(false); // Don't save again during load
    });

    staffRadio.addEventListener('change', updateToggle);
    adminRadio.addEventListener('change', updateToggle);
</script>

</html>