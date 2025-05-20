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
                    <x-input-label for="email" :value="__('Email')" />
                    <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" required
                        autocomplete="email" />
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
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
                            Email
                        </th>
                        <th scope="col" class="text-center px-6 py-3 text-xs font-medium text-gray-300 uppercase">
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-700">
                    @forelse($staffs as $staff)
                        <tr class="hover:bg-gray-800 transition-colors duration-150">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-white font-medium">
                                {{ $staff->id }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-white font-medium">
                                {{ $staff->name }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-white font-medium">
                                {{ $staff->email }}
                            </td>
                            <td class="text-center text-sm text-white font-medium">
                                <button type="button" onclick="openConfirmModal({{ $staff->id }})"
                                    class="p-0 m-0 bg-transparent border-none hover:opacity-75">
                                    <img src="{{ asset('storage/logo/deactivate_account.png') }}" alt="Deactivate"
                                        class="w-6 h-6">
                                </button>

                                <form id="deactivateForm-{{ $staff->id }}" method="POST"
                                    action="{{ route('staff.deactivate', $staff->id) }}">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="is_active" value="0">
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-4 text-center text-gray-400">No staff found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <!-- Confirmation Modal -->
            <div id="confirmModal" class="items-center justify-center z-50 fixed inset-0 bg-black bg-opacity-50 hidden">
                <div>
                    <div class="bg-white rounded-lg shadow-lg max-w-sm w-full p-6">
                        <h2 class="text-lg font-semibold text-gray-800 mb-4">Confirm Deactivation</h2>
                        <p class="text-gray-700 mb-6">Are you sure you want to deactivate this account?</p>
                        <div class="flex justify-end space-x-3">
                            <button onclick="closeConfirmModal()"
                                class="px-4 py-2 text-sm text-gray-700 bg-gray-200 rounded hover:bg-gray-300">Cancel</button>
                            <button id="confirmDeactivateBtn"
                                class="px-4 py-2 text-sm text-white bg-red-600 rounded hover:bg-red-700">Deactivate</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        let selectedId = null;

        function openConfirmModal(stockId) {
            selectedId = stockId;
            const modal = document.getElementById('confirmModal');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function closeConfirmModal() {
            selectedId = null;
            const modal = document.getElementById('confirmModal');
            modal.classList.remove('flex');
            modal.classList.add('hidden');
        }

        document.getElementById('confirmBtn').addEventListener('click', function () {
            if (selectedId) {
                document.getElementById('removeForm-' + selectedId).submit();
            }
        });
    </script>
</x-app-layout>