<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('الملف الشخصي للعميل') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            @if(session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4 rounded-md shadow-sm" role="alert">
                    <p>{{ session('success') }}</p>
                </div>
            @endif

            @if(session('error'))
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4 rounded-md shadow-sm" role="alert">
                    <p>{{ session('error') }}</p>
                </div>
            @endif

            <!-- معلومات الملف الشخصي للعميل -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-6 border-b pb-2 border-gray-200 dark:border-gray-600">{{ __('معلومات الملف الشخصي') }}</h3>
                    
                    <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="space-y-6">
                        @csrf
                        @method('patch')
                        
                        <div class="flex flex-col md:flex-row gap-8">
                            <!-- القسم الأيمن - الصورة الشخصية -->
                            <div class="md:w-1/3">
                                <div class="bg-gray-50 dark:bg-gray-700 p-6 rounded-lg shadow-sm flex flex-col items-center">
                                    <h4 class="text-md font-medium text-gray-900 dark:text-gray-100 mb-4 self-start">{{ __('الصورة الشخصية') }}</h4>
                                    <div class="mb-6 relative group">
                                        @if($user->avatar)
                                            <img src="{{ asset('storage/' . $user->avatar) }}" alt="Avatar" class="h-40 w-40 rounded-full object-cover border-4 border-indigo-500 shadow-md mx-auto">
                                            <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-30 rounded-full flex items-center justify-center transition-all duration-300 cursor-pointer">
                                                <span class="text-white opacity-0 group-hover:opacity-100 font-medium">تغيير الصورة</span>
                                            </div>
                                        @else
                                            <div class="h-40 w-40 rounded-full bg-gray-200 dark:bg-gray-600 flex items-center justify-center border-4 border-gray-300 dark:border-gray-500 shadow-md mx-auto">
                                                <svg class="h-24 w-24 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                                </svg>
                                            </div>
                                        @endif
                                    </div>
                                    
                                    <input type="file" name="avatar" id="avatar" accept="image/*" class="block w-full text-sm text-gray-500 my-3
                                        file:mr-4 file:py-2 file:px-4
                                        file:rounded-full file:border-0
                                        file:text-sm file:font-semibold
                                        file:bg-blue-50 file:text-blue-700
                                        hover:file:bg-blue-100">
                                    <p class="text-sm text-gray-500 text-center mb-3">صورة مربعة مفضلة. الحد الأقصى: 20 ميجابايت</p>
                                    <x-input-error class="mt-2" :messages="$errors->get('avatar')" />
                                    
                                    @if($user->avatar)
                                    <div class="flex items-center mt-3">
                                        <input id="remove_avatar" name="remove_avatar" type="checkbox" value="1" class="w-4 h-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                                        <label for="remove_avatar" class="mr-2 block text-sm text-gray-700 dark:text-gray-300">{{ __('حذف الصورة الحالية') }}</label>
                                    </div>
                                    @endif
                                </div>
                            </div>
                            
                            <!-- القسم الأيسر - البيانات الشخصية -->
                            <div class="md:w-2/3">
                                <div class="bg-gray-50 dark:bg-gray-700 p-6 rounded-lg shadow-sm space-y-6">
                                    <div>
                                        <x-input-label for="name" :value="__('الاسم الكامل')" />
                                        <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $user->name)" required autofocus autocomplete="name" />
                                        <x-input-error class="mt-2" :messages="$errors->get('name')" />
                                    </div>
                                    
                                    <div>
                                        <x-input-label for="email" :value="__('البريد الإلكتروني')" />
                                        <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $user->email)" required autocomplete="username" />
                                        <x-input-error class="mt-2" :messages="$errors->get('email')" />
                                        
                                        @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                                            <div>
                                                <p class="text-sm mt-2 text-gray-800 dark:text-gray-200">
                                                    {{ __('لم يتم التحقق من بريدك الإلكتروني.') }}
                                                    
                                                    <button form="send-verification" class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800">
                                                        {{ __('اضغط هنا لإعادة إرسال رسالة التحقق.') }}
                                                    </button>
                                                </p>
                                                
                                                @if (session('status') === 'verification-link-sent')
                                                    <p class="mt-2 font-medium text-sm text-green-600 dark:text-green-400">
                                                        {{ __('تم إرسال رابط تحقق جديد إلى بريدك الإلكتروني.') }}
                                                    </p>
                                                @endif
                                            </div>
                                        @endif
                                    </div>
                                    
                                    <div>
                                        <x-input-label for="phone" :value="__('رقم الهاتف')" />
                                        <div class="mt-1 relative rounded-md shadow-sm">
                                            <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-500">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                    <path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z" />
                                                </svg>
                                            </span>
                                            <x-text-input id="phone" name="phone" type="text" class="block w-full pl-10 pr-4" :value="old('phone', $user->phone)" placeholder="05xxxxxxxx" />
                                        </div>
                                        <x-input-error class="mt-2" :messages="$errors->get('phone')" />
                                    </div>
                                    
                                    <div>
                                        <x-input-label for="address" :value="__('الولاية')" />
                                        <x-province-select name="address" id="address" :selected="old('address', $user->address)" />
                                        <x-input-error class="mt-2" :messages="$errors->get('address')" />
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="flex justify-end">
                            <x-primary-button>{{ __('حفظ التغييرات') }}</x-primary-button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- تغيير كلمة المرور -->
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="max-w-xl">
                    <section>
                        <header>
                            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                                {{ __('تغيير كلمة المرور') }}
                            </h2>

                            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                                {{ __('تأكد من استخدام كلمة مرور طويلة وعشوائية للحفاظ على أمان حسابك.') }}
                            </p>
                        </header>

                        <form method="post" action="{{ route('password.update') }}" class="mt-6 space-y-6">
                            @csrf
                            @method('put')

                            <div>
                                <x-input-label for="current_password" :value="__('كلمة المرور الحالية')" />
                                <x-text-input id="current_password" name="current_password" type="password" class="mt-1 block w-full" autocomplete="current-password" />
                                <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="password" :value="__('كلمة المرور الجديدة')" />
                                <x-text-input id="password" name="password" type="password" class="mt-1 block w-full" autocomplete="new-password" />
                                <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="password_confirmation" :value="__('تأكيد كلمة المرور')" />
                                <x-text-input id="password_confirmation" name="password_confirmation" type="password" class="mt-1 block w-full" autocomplete="new-password" />
                                <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2" />
                            </div>

                            <div class="flex items-center gap-4">
                                <x-primary-button>{{ __('حفظ') }}</x-primary-button>
                            </div>
                        </form>
                    </section>
                </div>
            </div>

            <!-- حذف الحساب -->
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="max-w-xl">
                    <section class="space-y-6">
                        <header>
                            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                                {{ __('حذف الحساب') }}
                            </h2>

                            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                                {{ __('بمجرد حذف حسابك، سيتم حذف جميع موارده وبياناته نهائيًا. قبل حذف حسابك، يرجى تنزيل أي بيانات أو معلومات ترغب في الاحتفاظ بها.') }}
                            </p>
                        </header>

                        <x-danger-button
                            x-data=""
                            x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
                        >{{ __('حذف الحساب') }}</x-danger-button>

                        <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
                            <form method="post" action="{{ route('profile.destroy') }}" class="p-6">
                                @csrf
                                @method('delete')

                                <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                                    {{ __('هل أنت متأكد أنك تريد حذف حسابك؟') }}
                                </h2>

                                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                                    {{ __('بمجرد حذف حسابك، سيتم حذف جميع موارده وبياناته نهائيًا. الرجاء إدخال كلمة المرور الخاصة بك لتأكيد رغبتك في حذف حسابك نهائيًا.') }}
                                </p>

                                <div class="mt-6">
                                    <x-input-label for="password" value="{{ __('كلمة المرور') }}" class="sr-only" />

                                    <x-text-input
                                        id="password"
                                        name="password"
                                        type="password"
                                        class="mt-1 block w-3/4"
                                        placeholder="{{ __('كلمة المرور') }}"
                                    />

                                    <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-2" />
                                </div>

                                <div class="mt-6 flex justify-end">
                                    <x-secondary-button x-on:click="$dispatch('close')">
                                        {{ __('إلغاء') }}
                                    </x-secondary-button>

                                    <x-danger-button class="mr-3">
                                        {{ __('حذف الحساب') }}
                                    </x-danger-button>
                                </div>
                            </form>
                        </x-modal>
                    </section>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 