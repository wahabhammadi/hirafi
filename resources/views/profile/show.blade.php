<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('الملف الشخصي') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            @if (session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <span class="block sm:inline">{{ session('error') }}</span>
                </div>
            @endif

            @if ($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <ul class="list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- صورة الملف الشخصي والمعلومات الأساسية -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6" id="profile-form">
                        @csrf
                        @method('PATCH')

                        <!-- صورة الملف الشخصي -->
                        <div class="flex flex-col items-center mb-6">
                            <div class="w-32 h-32 rounded-full overflow-hidden mb-4 border-4 border-blue-500">
                                @if(auth()->user()->avatar)
                                    <img id="avatar-preview" src="{{ Storage::url(auth()->user()->avatar) }}" 
                                         alt="صورة الملف الشخصي" class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full bg-gray-200 flex items-center justify-center">
                                        <svg class="h-16 w-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                  d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                        </svg>
                                    </div>
                                @endif
                            </div>
                            <div class="flex items-center justify-center space-x-4 rtl:space-x-reverse">
                                <label class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg cursor-pointer">
                                    <span>تغيير الصورة</span>
                                    <input type="file" name="avatar" id="avatar-input" class="hidden" accept="image/*">
                                </label>
                                @if(auth()->user()->avatar)
                                    <button type="button" id="remove-avatar-button" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg">
                                        حذف الصورة
                                    </button>
                                @endif
                            </div>
                            <input type="hidden" name="remove_avatar" id="remove-avatar-input" value="0">
                            @error('avatar')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- المعلومات الشخصية -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            @if(auth()->user()->role == 'craftsman' && $participant)
                            <!-- الاسم -->
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">الاسم</label>
                                <input type="text" name="name" id="name" value="{{ old('name', $participant['first_name']) }}" 
                                       class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                <p class="text-xs text-gray-500 mt-1">{{ __('(من بيانات المشارك)') }}</p>
                                @error('name')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- اللقب -->
                            <div>
                                <label for="surname" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">اللقب</label>
                                <input type="text" name="surname" id="surname" value="{{ old('surname', $participant['last_name']) }}" 
                                       class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                <p class="text-xs text-gray-500 mt-1">{{ __('(من بيانات المشارك)') }}</p>
                                @error('surname')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- البريد الإلكتروني -->
                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">البريد الإلكتروني</label>
                                <input type="email" name="email" id="email" value="{{ old('email', $participant['email']) }}" 
                                       class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                <p class="text-xs text-gray-500 mt-1">{{ __('(من بيانات المشارك)') }}</p>
                                @error('email')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- رقم الهاتف -->
                            <div>
                                <label for="phone" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">رقم الهاتف</label>
                                <input type="tel" name="phone" id="phone" value="{{ old('phone', $participant['phone']) }}" 
                                       class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                <p class="text-xs text-gray-500 mt-1">{{ __('(من بيانات المشارك)') }}</p>
                                @error('phone')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- رقم الهاتف الثانوي -->
                            <div>
                                <label for="phone_secondary" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">رقم الهاتف الثانوي</label>
                                <input type="tel" name="phone_secondary" id="phone_secondary" value="{{ old('phone_secondary', $craftsman->phone_secondary) }}" 
                                       class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                @error('phone_secondary')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- تاريخ الميلاد -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">تاريخ الميلاد</label>
                                <input type="text" disabled readonly value="{{ \Carbon\Carbon::parse($participant['birth_date'])->format('Y-m-d') }}" 
                                       class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm bg-gray-50">
                            </div>

                            <!-- العنوان -->
                            <div class="md:col-span-2">
                                <label for="address" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">العنوان</label>
                                <textarea name="address" id="address" rows="3" 
                                          class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">{{ old('address', $participant['address']) }}</textarea>
                                <p class="text-xs text-gray-500 mt-1">{{ __('(من بيانات المشارك)') }}</p>
                                @error('address')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- التخصص -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">التخصص</label>
                                <input type="text" disabled readonly value="{{ $participant['specialization_name'] }}" 
                                       class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm bg-gray-50">
                                <p class="text-xs text-gray-500 mt-1">{{ __('(يتم عرض المشاريع التي تتناسب مع هذا التخصص في لوحة التحكم الخاصة بك)') }}</p>
                            </div>

                            <!-- المؤسسة -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">المؤسسة</label>
                                <input type="text" disabled readonly value="{{ $participant['institution_name'] }}" 
                                       class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm bg-gray-50">
                            </div>

                            <!-- نوع الحساب -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">نوع الحساب</label>
                                <div class="mt-1 p-2 bg-gray-100 dark:bg-gray-700 rounded-md">
                                    <span class="text-blue-600 dark:text-blue-400">حرفي</span>
                                </div>
                            </div>

                            <!-- تاريخ التسجيل -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">تاريخ التسجيل</label>
                                <div class="mt-1 p-2 bg-gray-100 dark:bg-gray-700 rounded-md">
                                    {{ auth()->user()->created_at->format('Y/m/d') }}
                                </div>
                            </div>
                            
                            @else
                            <!-- الاسم -->
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">الاسم</label>
                                <input type="text" name="name" id="name" value="{{ old('name', auth()->user()->name) }}" 
                                       class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                @error('name')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- اللقب -->
                            <div>
                                <label for="surname" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">اللقب</label>
                                <input type="text" name="surname" id="surname" value="{{ old('surname', auth()->user()->surname) }}" 
                                       class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                @error('surname')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- البريد الإلكتروني -->
                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">البريد الإلكتروني</label>
                                <input type="email" name="email" id="email" value="{{ old('email', auth()->user()->email) }}" 
                                       class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                @error('email')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- رقم الهاتف -->
                            <div>
                                <label for="phone" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">رقم الهاتف</label>
                                <input type="tel" name="phone" id="phone" value="{{ old('phone', auth()->user()->phone) }}" 
                                       class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                @error('phone')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- العنوان -->
                            <div class="md:col-span-2">
                                <label for="address" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">العنوان</label>
                                <textarea name="address" id="address" rows="3" 
                                          class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">{{ old('address', auth()->user()->address) }}</textarea>
                                @error('address')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- نوع الحساب -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">نوع الحساب</label>
                                <div class="mt-1 p-2 bg-gray-100 dark:bg-gray-700 rounded-md">
                                    @if(auth()->user()->role == 'craftsman')
                                        <span class="text-blue-600 dark:text-blue-400">حرفي</span>
                                    @elseif(auth()->user()->role == 'client')
                                        <span class="text-green-600 dark:text-green-400">عميل</span>
                                    @else
                                        <span class="text-purple-600 dark:text-purple-400">مدير</span>
                                    @endif
                                </div>
                            </div>

                            <!-- تاريخ التسجيل -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">تاريخ التسجيل</label>
                                <div class="mt-1 p-2 bg-gray-100 dark:bg-gray-700 rounded-md">
                                    {{ auth()->user()->created_at->format('Y/m/d') }}
                                </div>
                            </div>
                            @endif
                        </div>
                        
                        <!-- إحصائيات الحرفي -->
                        @if(auth()->user()->role == 'craftsman' && $craftsman)
                        <div class="mt-8 pt-6 border-t border-gray-200 dark:border-gray-700">
                            <h3 class="text-lg font-semibold mb-4">{{ __('معلومات الحرفي الإضافية') }}</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                                    <h5 class="text-sm font-medium text-gray-600 dark:text-gray-400 mb-3">{{ __('الخبرات والمهارات') }}</h5>
                                    <p class="text-gray-700 dark:text-gray-300">
                                        {{ __('للحرفي مهارات متميزة في تخصصه') }}
                                        @if($participant)
                                            {{__('في مجال')}} {{ $participant['specialization_name'] }}
                                        @endif
                                    </p>
                                </div>
                                <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                                    <h5 class="text-sm font-medium text-gray-600 dark:text-gray-400 mb-3">{{ __('التقييم') }}</h5>
                                    <div class="flex items-center">
                                        <p class="text-lg font-bold text-yellow-500">{{ $craftsman->rating }}</p>
                                        <svg class="w-5 h-5 text-yellow-400 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                        </svg>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif
                        
                        <div class="flex justify-end mt-6">
                            <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-2 rounded-lg" id="save-button">
                                حفظ التغييرات
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        // معاينة الصورة قبل التحميل
        document.getElementById('avatar-input').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('avatar-preview').src = e.target.result;
                }
                reader.readAsDataURL(file);
            }
        });

        // معالجة حذف الصورة
        const removeButton = document.getElementById('remove-avatar-button');
        if (removeButton) {
            removeButton.addEventListener('click', function() {
                if (confirm('هل أنت متأكد من حذف الصورة؟')) {
                    document.getElementById('remove-avatar-input').value = '1';
                    document.getElementById('profile-form').submit();
                }
            });
        }

        // تأكيد قبل حفظ التغييرات
        document.getElementById('profile-form').addEventListener('submit', function(e) {
            if (!e.submitter || e.submitter.id !== 'remove-avatar-button') {
                e.preventDefault();
                
                const formData = new FormData(this);
                console.log('Form data before submit:', Object.fromEntries(formData));
                
                if (confirm('هل أنت متأكد من حفظ التغييرات؟')) {
                    this.submit();
                }
            }
        });
    </script>
    @endpush
</x-app-layout> 