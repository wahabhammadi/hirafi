<x-guest-layout>
    <div class="mb-4 text-sm text-gray-600 dark:text-gray-400">
        {{ __('التحقق من رمز OTP') }}
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    @if(session('debug_otp'))
        <div class="mb-4 p-4 bg-yellow-100 text-yellow-800 rounded-lg">
            <p class="font-medium">معلومات التصحيح:</p>
            <p>رمز التحقق: {{ session('debug_otp') }}</p>
            <p class="text-xs mt-1">(هذه المعلومات تظهر فقط في بيئة التطوير)</p>
        </div>
    @endif

    <form method="POST" action="{{ route('craftsman.verify-otp') }}">
        @csrf

        <!-- OTP -->
        <div>
            <x-input-label for="otp" :value="__('رمز التحقق')" />
            <x-text-input id="otp" class="block mt-1 w-full" type="text" name="otp" :value="old('otp')" required autofocus />
            <x-input-error :messages="$errors->get('otp')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <a class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800" href="{{ route('craftsman.register') }}">
                {{ __('العودة للخلف') }}
            </a>

            <x-primary-button class="ms-3">
                {{ __('تحقق') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout> 