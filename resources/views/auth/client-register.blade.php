<x-guest-layout>
    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100">
        <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg">
            <div class="text-center mb-8">
                <h2 class="text-2xl font-bold text-gray-900">
                    تسجيل حساب جديد
                </h2>
                <p class="mt-2 text-gray-600">
                    قم بإنشاء حساب كصاحب مشاريع
                </p>
            </div>

            <form method="POST" action="{{ route('client.register.submit') }}">
                @csrf

                <!-- Name -->
                <div>
                    <x-input-label for="name" :value="__('الاسم')" />
                    <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
                    <x-input-error :messages="$errors->get('name')" class="mt-2" />
                </div>

                <!-- Surname -->
                <div class="mt-4">
                    <x-input-label for="surname" :value="__('اللقب')" />
                    <x-text-input id="surname" class="block mt-1 w-full" type="text" name="surname" :value="old('surname')" required autocomplete="family-name" />
                    <x-input-error :messages="$errors->get('surname')" class="mt-2" />
                </div>

                <!-- Email Address -->
                <div class="mt-4">
                    <x-input-label for="email" :value="__('البريد الإلكتروني')" />
                    <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" />
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <!-- Phone -->
                <div class="mt-4">
                    <x-input-label for="phone" :value="__('رقم الهاتف')" />
                    <x-text-input id="phone" class="block mt-1 w-full" type="tel" name="phone" :value="old('phone')" required autocomplete="tel" />
                    <x-input-error :messages="$errors->get('phone')" class="mt-2" />
                </div>

                <!-- Address -->
                <div class="mt-4">
                    <x-input-label for="address" :value="__('الولاية')" />
                    <x-province-select name="address" id="address" :selected="old('address')" required />
                    <x-input-error :messages="$errors->get('address')" class="mt-2" />
                </div>

                <!-- Password -->
                <div class="mt-4">
                    <x-input-label for="password" :value="__('كلمة المرور')" />
                    <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="new-password" />
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>

                <!-- Confirm Password -->
                <div class="mt-4">
                    <x-input-label for="password_confirmation" :value="__('تأكيد كلمة المرور')" />
                    <x-text-input id="password_confirmation" class="block mt-1 w-full" type="password" name="password_confirmation" required autocomplete="new-password" />
                    <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                </div>

                <div class="flex items-center justify-end mt-4">
                    <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('login') }}">
                        {{ __('لديك حساب بالفعل؟') }}
                    </a>

                    <x-primary-button class="mr-3">
                        {{ __('تسجيل') }}
                    </x-primary-button>
                </div>
            </form>
        </div>
    </div>
</x-guest-layout> 