<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

    <!-- reCAPTCHA -->
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>

    <!-- Styles / Scripts -->
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
    @endif
</head>

<x-auth-session-status class="mb-4" :status="session('status')" />


<body class="relative min-h-screen flex items-center justify-center p-4">
    <div class="fixed inset-0 bg-cover bg-center z-0"
        style="background-image: url('{{ asset('storage/background_image.png') }}');">
        <div class="w-full h-full bg-black/50"></div>
    </div>

    <header class="w-full lg:max-w-4xl max-w-[335px] text-sm mb-6 not-has-[nav]:hidden">
        @auth
            <a href="{{ url('/dashboard') }}"
                class="inline-block px-5 py-1.5 text-[#EDEDEC] border-[#19140035] hover:border-[#1915014a] border-[#3E3E3A] hover:border-[#62605b] rounded-sm text-sm leading-normal">
                Record Sales
            </a>
        @else
            <div class="flex items-center justify-center bg-gray-100 p-4">
                <div
                    class="bg-white shadow-2xl rounded-2xl max-w-md w-full overflow-hidden transform transition-all duration-300 hover:scale-105">
                    <!-- Login Form -->
                    <div class="px-8 py-10">
                        <div class="flex justify-center">
                            <img src="storage/logo.png" alt="App Logo" class="max-h-20 max-w-20 text-blue-500">
                        </div>

                        <h2 class="text-center text-2xl font-bold text-gray-800 mb-6">Login</h2>
                        <form method="POST" action="{{ route('login') }}" class="space-y-6">
                            @csrf
                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email
                                    address</label>
                                <input id="email" type="email" name="email" required autofocus autocomplete="email" value="{{ old('email') }}"
                                    class="w-full px-4 border border-gray-500 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 transition duration-300" />
                                <x-input-error :messages="$errors->get('email') && !str_contains($errors->first('email'), 'seconds') ? $errors->get('email') : []" class="mt-2" />
                            </div>

                            <div>
                                <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                                <div class="relative">
                                    <input id="password" type="password" name="password" required
                                        autocomplete="current-password"
                                        class="w-full px-4 pr-10 border border-gray-500 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 transition duration-300" />
                                    <button type="button" onclick="togglePassword()"
                                        class="absolute inset-y-0 right-0 flex items-center px-3 text-gray-500 hover:text-gray-700">
                                        <svg id="eyeIcon" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                    </button>
                                </div>
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

                            <!-- reCAPTCHA widget -->
                            <div class="flex justify-center">
                                {!! NoCaptcha::display() !!}
                            </div>
 
                            {{-- Show validation error for captcha --}}
                            @error('g-recaptcha-response')
                                <p class="text-red-500 text-sm text-center -mt-2">{{ $message }}</p>
                            @enderror

                            <button id="loginBtn" type="submit"
                                class="w-full mt-4 bg-blue-600 text-white font-semibold py-3 rounded-lg hover:bg-blue-700 transition duration-300 transform hover:scale-102 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50">
                                Log In
                            </button>

                            <p id="lockoutMessage" class="text-red-500 text-sm text-center mt-2"></p>
                        </form>
                    </div>
                </div>
            </div>
        @endauth
    </header>

    <script>
        function togglePassword() {
            const input = document.getElementById('password');
            const icon = document.getElementById('eyeIcon');

            if (input.type === 'password') {
                input.type = 'text';
                icon.innerHTML = `
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.542 7a10.025 10.025 0 01-4.132 4.411m0 0L21 21" />
                `;
            } else {
                input.type = 'password';
                icon.innerHTML = `
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                `;
            }
        }
    </script>

    @if ($errors->has('email') && str_contains($errors->first('email'), 'seconds'))
    <script>
        document.addEventListener("DOMContentLoaded", function () {

            let message = @json($errors->first('email'));

            // Extract seconds from message
            let seconds = message.match(/\d+/);
            seconds = seconds ? parseInt(seconds[0]) : 0;

            if (seconds > 0) {

                let loginBtn = document.getElementById("loginBtn");
                let lockoutMsg = document.getElementById("lockoutMessage");

                loginBtn.disabled = true;
                loginBtn.classList.add("opacity-50", "cursor-not-allowed");

                let interval = setInterval(function () {

                    lockoutMsg.innerText = "Too many login attempts. Try again in " + seconds + " seconds.";

                    seconds--;

                    if (seconds < 0) {
                        clearInterval(interval);

                        loginBtn.disabled = false;
                        loginBtn.classList.remove("opacity-50", "cursor-not-allowed");

                        lockoutMsg.innerText = "";
                    }

                }, 1000);
            }

        });
    </script>
    @endif
</body>

</html>