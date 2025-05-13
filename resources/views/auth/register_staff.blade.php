<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            {{ __('Register Staff') }}
        </h2>
    </x-slot>

    @include('components.toast')

    <div class="max-w-7xl mx-auto grid grid-cols-3 gap-4 mt-10">
        <div class=" px-6 py-4 bg-gray-800 shadow-md overflow-hidden sm:rounded-lg">
            <form method="POST" action="{{ route('admin.register.staff') }}">
                @csrf

                <!-- Name -->
                <div>
                    <x-input-label for="name" :value="__('Name')" />
                    <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')"
                        required autofocus autocomplete="name" />
                    <x-input-error :messages="$errors->get('name')" class="mt-2" />
                </div>

                <!-- Email Address -->
                <div class="mt-4">
                    <x-input-label for="username" :value="__('Username')" />
                    <x-text-input id="username" class="block mt-1 w-full" type="text" name="username" :value="old('username')"
                        required autocomplete="username" />
                    <x-input-error :messages="$errors->get('username')" class="mt-2" />
                </div>

                <!-- Password -->
                <div class="mt-4">
                    <x-input-label for="password" :value="__('Password')" />

                    <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required
                        autocomplete="new-password" />

                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>

                <!-- Confirm Password -->
                <div class="mt-4">
                    <x-input-label for="password_confirmation" :value="__('Confirm Password')" />

                    <x-text-input id="password_confirmation" class="block mt-1 w-full" type="password"
                        name="password_confirmation" required autocomplete="new-password" />

                    <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                </div>

                <div class="flex items-center justify-end mt-4">
                    <x-primary-button class="ms-4">
                        {{ __('Register') }}
                    </x-primary-button>
                </div>
            </form>
        </div>

        <div class="bg-gray-800 text-white p-4 rounded-lg shadow col-span-2">
            <h1 class="text-white text-2xl font-bold">Record of Staff</h1>

            <div class="border-t border-gray-200 my-4"></div>

            <table class="min-w-full divide-y divide-gray-700">
                <thead>
                    <tr>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">
                            Staff ID
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">
                            Staff Name
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">
                            Username
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-right text-xs font-medium text-gray-300 uppercase tracking-wider">
                            Password
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-700">
                    <tr class="hover:bg-gray-800 transition-colors duration-150">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-white font-medium">

                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-white font-medium">

                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-white font-medium">

                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-white font-medium">

                        </td>

                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>