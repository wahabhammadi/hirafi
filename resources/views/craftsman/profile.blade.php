<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('الملف الشخصي') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex flex-col items-center md:flex-row md:items-start space-y-4 md:space-y-0 md:space-x-6 rtl:space-x-reverse">
                        <!-- Avatar Section -->
                        <div class="flex flex-col items-center space-y-4">
                            @if($craftsman->avatar)
                                <img src="{{ Storage::url($craftsman->avatar) }}" alt="صورة الملف الشخصي" 
                                     class="h-48 w-48 rounded-full object-cover border-4 border-blue-500">
                            @else
                                <div class="h-48 w-48 rounded-full bg-gray-200 flex items-center justify-center border-4 border-blue-500">
                                    <svg class="h-24 w-24 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                              d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                </div>
                            @endif
                        </div>

                        <!-- Information Section -->
                        <div class="flex-1 space-y-6 text-right">
                            <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-6 space-y-4">
                                <div class="border-b pb-4">
                                    <h3 class="text-2xl font-bold text-gray-900 dark:text-gray-100">
                                        {{ $user->name }}
                                    </h3>
                                    <p class="text-blue-600 dark:text-blue-400">{{ $user->email }}</p>
                                </div>

                                <div class="space-y-3">
                                    <div class="flex justify-between items-center">
                                        <span class="text-gray-600 dark:text-gray-400">رقم الهاتف الرئيسي:</span>
                                        <span class="font-semibold">{{ $craftsman->phone }}</span>
                                    </div>

                                    @if($craftsman->phone_secondary)
                                    <div class="flex justify-between items-center">
                                        <span class="text-gray-600 dark:text-gray-400">رقم الهاتف الثانوي:</span>
                                        <span class="font-semibold">{{ $craftsman->phone_secondary }}</span>
                                    </div>
                                    @endif

                                    <div class="flex justify-between items-center">
                                        <span class="text-gray-600 dark:text-gray-400">عدد المشاريع:</span>
                                        <span class="font-semibold">{{ $craftsman->num_projects }}</span>
                                    </div>

                                    <div class="flex justify-between items-center">
                                        <span class="text-gray-600 dark:text-gray-400">عدد العملاء:</span>
                                        <span class="font-semibold">{{ $craftsman->num_clients }}</span>
                                    </div>

                                    <div class="flex justify-between items-center">
                                        <span class="text-gray-600 dark:text-gray-400">التقييم:</span>
                                        <div class="flex items-center">
                                            <span class="font-semibold ml-1">{{ $craftsman->rating }}</span>
                                            <svg class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                            </svg>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Rating Details Section -->
                            <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-6 mt-4">
                                <div class="flex justify-between items-center mb-4">
                                    <a href="{{ route('craftsman.ratings') }}" class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 transition">
                                        عرض جميع التقييمات
                                    </a>
                                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">تقييمات العملاء</h3>
                                </div>
                                <div class="flex items-center justify-center space-x-2 rtl:space-x-reverse">
                                    @for($i = 1; $i <= 5; $i++)
                                        <svg class="w-8 h-8 {{ $i <= round($craftsman->rating) ? 'text-yellow-400' : 'text-gray-300' }}" 
                                             fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                        </svg>
                                    @endfor
                                </div>
                                <p class="text-center mt-2 text-gray-600 dark:text-gray-400">
                                    متوسط التقييم: <span class="font-bold">{{ number_format($craftsman->rating, 1) }}</span> من 5
                                    @if($ratingsCount ?? 0 > 0)
                                    ({{ $ratingsCount }} تقييم)
                                    @else
                                    (لا توجد تقييمات)
                                    @endif
                                </p>
                            </div>

                            <!-- Edit Profile Button -->
                            <div class="flex justify-end">
                                <a href="{{ route('craftsman.edit-profile') }}" 
                                   class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                    تعديل الملف الشخصي
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 