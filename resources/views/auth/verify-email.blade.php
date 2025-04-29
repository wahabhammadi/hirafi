<x-guest-layout>
    <div class="mb-4 text-sm text-gray-600 dark:text-gray-400">
        <h2 class="text-xl font-bold mb-4 text-center">{{ __('التحقق من البريد الإلكتروني') }}</h2>
        <p class="text-center">
            {{ __('لقد أرسلنا رمز تحقق مكون من 6 أرقام إلى بريدك الإلكتروني.') }}
            <br>
            {{ __('يرجى إدخال الرمز أدناه للمتابعة.') }}
        </p>
    </div>

    @if (session('success'))
        <div class="mb-4 font-medium text-sm text-green-600 dark:text-green-400">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="mb-4 font-medium text-sm text-red-600 dark:text-red-400">
            {{ session('error') }}
        </div>
    @endif

    @if (session('resend_success'))
        <div class="mb-4 font-medium text-sm text-green-600 dark:text-green-400">
            {{ session('resend_success') }}
        </div>
    @endif

    @if (session('resend_error'))
        <div class="mb-4 font-medium text-sm text-red-600 dark:text-red-400">
            {{ session('resend_error') }}
        </div>
    @endif

    <form method="POST" action="{{ route('verification.verify-code') }}" class="text-center">
        @csrf

        <!-- إدخال الرمز -->
        <div class="mt-4">
            <x-input-label for="code" :value="__('رمز التحقق')" class="text-right" />

            <div class="mt-2 max-w-sm mx-auto">
                <div class="flex justify-center" dir="ltr">
                    <x-text-input
                        id="code"
                        class="block mt-1 w-full text-center text-xl tracking-widest"
                        type="text"
                        name="code"
                        maxlength="6"
                        autofocus
                        inputmode="numeric"
                        pattern="[0-9]*"
                    />
                </div>
            </div>

            <x-input-error :messages="$errors->get('code')" class="mt-2" />
        </div>

        <div class="flex items-center justify-between mt-6">
            <a 
                href="{{ route('verification.resend') }}"
                class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100"
            >
                {{ __('إعادة إرسال الرمز') }}
            </a>

            <x-primary-button>
                {{ __('تحقق') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
