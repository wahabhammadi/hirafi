<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('إكمال الملف الشخصي') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    @if($participant)
                        <div class="mb-6">
                            <h3 class="text-lg font-medium mb-2">{{ __('معلومات من قاعدة البيانات') }}</h3>
                            <div class="bg-gray-100 dark:bg-gray-700 p-4 rounded-lg">
                                <p><span class="font-medium">الاسم:</span> {{ $participant['first_name'] }} {{ $participant['last_name'] }}</p>
                                <p><span class="font-medium">رقم الهوية:</span> {{ $participant['id_number'] }}</p>
                                <p><span class="font-medium">البريد الإلكتروني:</span> {{ $participant['email'] }}</p>
                                <p><span class="font-medium">رقم الهاتف:</span> {{ $participant['phone'] }}</p>
                            </div>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('craftsman.complete-profile') }}" enctype="multipart/form-data" class="space-y-6">
                        @csrf

                        <!-- Avatar Upload -->
                        <div>
                            <x-input-label for="avatar" :value="__('الصورة الشخصية')" />
                            <div class="mt-2 flex items-center gap-x-3">
                                @if(isset($craftsman) && $craftsman->avatar)
                                    <img src="{{ Storage::url($craftsman->avatar) }}" alt="Avatar" class="h-12 w-12 rounded-full">
                                @else
                                    <div class="h-12 w-12 rounded-full bg-gray-100 flex items-center justify-center">
                                        <svg class="h-8 w-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                        </svg>
                                    </div>
                                @endif
                                <input type="file" name="avatar" id="avatar" accept="image/*" class="block w-full text-sm text-gray-500
                                    file:mr-4 file:py-2 file:px-4
                                    file:rounded-full file:border-0
                                    file:text-sm file:font-semibold
                                    file:bg-blue-50 file:text-blue-700
                                    hover:file:bg-blue-100">
                            </div>
                            <x-input-error class="mt-2" :messages="$errors->get('avatar')" />
                        </div>

                        <!-- Secondary Phone -->
                        <div>
                            <x-input-label for="phone_secondary" :value="__('رقم الهاتف الثانوي')" />
                            <x-text-input id="phone_secondary" name="phone_secondary" type="text" class="mt-1 block w-full" :value="old('phone_secondary', isset($craftsman) ? $craftsman->phone_secondary : '')" />
                            <x-input-error class="mt-2" :messages="$errors->get('phone_secondary')" />
                        </div>

                        <div class="flex items-center gap-4">
                            <x-primary-button>{{ __('حفظ') }}</x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 