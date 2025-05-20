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
    <div class="fixed inset-0 bg-cover bg-center z-0"
        style="background-image: url('{{ asset('storage/logo/background_image.jpg') }}');">
        <div class="w-full h-full bg-black/50"></div>
    </div>

    <header class="w-full lg:max-w-4xl max-w-[335px] text-sm mb-6 not-has-[nav]:hidden">
        @auth
            <a href="{{ url('/dashboard') }}"
                class="inline-block px-5 py-1.5 text-[#EDEDEC] border-[#19140035] hover:border-[#1915014a] border-[#3E3E3A] hover:border-[#62605b] rounded-sm text-sm leading-normal">
                Record Sales
            </a>
        @else
            <div class="min-h-screen flex items-center justify-center bg-gray-100 p-4">
                <div
                    class="bg-white shadow-2xl rounded-2xl max-w-md w-full overflow-hidden transform transition-all duration-300 hover:scale-105">
                    <!-- Login Form -->
                    <div class="px-8 py-10">
                        <div class="flex justify-center">
                            <img src="storage/logo/logo.png" alt="App Logo" class="max-h-20 max-w-20 text-blue-500">
                        </div>

                        <h2 class="text-center text-2xl font-bold text-gray-800 mb-6">Login</h2>
                        <form method="POST" action="{{ route('login') }}" class="space-y-6">
                            @csrf
                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email
                                    address</label>
                                <input id="email" type="email" name="email" required autofocus
                                    class="w-full px-4 py-3 border border-gray-500 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 transition duration-300" />
                                <x-input-error :messages="$errors->get('email')" class="mt-2" />
                            </div>

                            <div>
                                <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                                <input id="password" type="password" name="password" required
                                    autocomplete="current-password"
                                    class="w-full px-4 py-3 border border-gray-500 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 transition duration-300" />
                                <x-input-error :messages="$errors->get('password')" class="mt-2" />
                            </div>

                            <div class="flex justify-between items-center text-sm">
                                @if (Route::has('password.request'))
                                    <a href="{{ route('password.request') }}"
                                        class="text-blue-600 hover:underline focus:outline-none focus:ring-2 focus:ring-blue-500 rounded">
                                        Forgot your password?
                                    </a>
                                @endif
                            </div>

                            <button type="submit"
                                class="w-full mt-4 bg-blue-600 text-white font-semibold py-3 rounded-lg hover:bg-blue-700 transition duration-300 transform hover:scale-102 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50">
                                Log In
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @endauth
    </header>
</body>

</html>