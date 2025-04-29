<x-guest-layout>
    <div class="mb-4 text-sm text-gray-600 dark:text-gray-400">
        {{ __('تسجيل كحرفي') }}
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('craftsman.verify-id') }}">
        @csrf

        <!-- ID Number -->
        <div>
            <x-input-label for="id_number" :value="__('رقم الهوية')" />
            <x-text-input id="id_number" class="block mt-1 w-full" type="text" name="id_number" :value="old('id_number')" required autofocus />
            <x-input-error :messages="$errors->get('id_number')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <a class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800" href="{{ route('login') }}">
                {{ __('العودة لتسجيل الدخول') }}
            </a>

            <x-primary-button class="ms-3">
                {{ __('تحقق') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout> 