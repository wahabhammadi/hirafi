<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('إعدادات العملة') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4" role="alert">
                    <p>{{ session('success') }}</p>
                </div>
            @endif

            @if (session('error'))
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4" role="alert">
                    <p>{{ session('error') }}</p>
                </div>
            @endif

            @if (session('output'))
                <div class="bg-gray-100 border-l-4 border-gray-500 text-gray-700 p-4 mb-4 overflow-x-auto" role="alert">
                    <h3 class="font-bold mb-2">{{ __('نتائج تنفيذ البرنامج النصي:') }}</h3>
                    <pre class="whitespace-pre-wrap">{{ session('output') }}</pre>
                </div>
            @endif

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-medium mb-4">{{ __('معلومات العملة الحالية') }}</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <p class="mb-2"><span class="font-medium">{{ __('العملة الافتراضية:') }}</span> 
                                {{ $defaultCurrency === 'DZD' ? 'الدينار الجزائري (DZD)' : 'الريال السعودي (SAR)' }}
                            </p>
                            <p class="mb-2"><span class="font-medium">{{ __('سعر تحويل الريال السعودي إلى الدينار الجزائري:') }}</span> 
                                {{ $sarToDzdRate }}
                            </p>
                            <p class="mb-2"><span class="font-medium">{{ __('سعر تحويل الدينار الجزائري إلى الريال السعودي:') }}</span> 
                                {{ $dzdToSarRate }}
                            </p>
                        </div>
                        <div>
                            <p class="mb-2 text-amber-600 dark:text-amber-400">
                                <span class="font-medium">{{ __('ملاحظة:') }}</span> 
                                {{ __('تغيير قيم أسعار الصرف يتطلب تعديل الكود المصدري وإعادة نشر التطبيق.') }}
                            </p>
                            <p class="mb-2 text-amber-600 dark:text-amber-400">
                                {{ __('يمكنك تحديث القيم المخزنة مؤقتًا في ذاكرة التخزين المؤقت أدناه.') }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-medium mb-4">{{ __('تحديث سعر الصرف المؤقت') }}</h3>
                    
                    <form method="POST" action="{{ route('admin.currency.update-rate') }}">
                        @csrf
                        
                        <div class="mb-4">
                            <x-input-label for="sar_to_dzd_rate" :value="__('سعر تحويل الريال السعودي إلى الدينار الجزائري')" />
                            <x-text-input id="sar_to_dzd_rate" class="block mt-1 w-full" type="number" name="sar_to_dzd_rate" :value="old('sar_to_dzd_rate', $sarToDzdRate)" required min="0.01" step="0.01" />
                            <x-input-error :messages="$errors->get('sar_to_dzd_rate')" class="mt-2" />
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                                {{ __('سيتم حساب السعر العكسي (من الدينار الجزائري إلى الريال السعودي) تلقائيًا.') }}
                            </p>
                        </div>
                        
                        <div class="flex justify-end">
                            <x-primary-button>
                                {{ __('تحديث سعر الصرف') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-medium mb-4">{{ __('تحويل العروض الحالية') }}</h3>
                    
                    <div class="mb-4">
                        <p class="mb-4">
                            {{ __('هذه العملية ستقوم بتحويل أسعار العروض القائمة من الريال السعودي إلى الدينار الجزائري.') }}
                        </p>
                        <p class="mb-4 text-red-600 dark:text-red-400 font-medium">
                            {{ __('تحذير: يوصى بعمل نسخة احتياطية لقاعدة البيانات قبل تنفيذ هذه العملية.') }}
                        </p>
                    </div>
                    
                    <form method="POST" action="{{ route('admin.currency.run-script') }}" onsubmit="return confirm('هل أنت متأكد من رغبتك في تنفيذ عملية تحويل العملة؟ هذه العملية لا يمكن التراجع عنها.');">
                        @csrf
                        
                        <div class="flex justify-end">
                            <x-danger-button>
                                {{ __('تنفيذ عملية التحويل') }}
                            </x-danger-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 