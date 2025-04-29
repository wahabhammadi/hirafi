<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('إضافة مشروع جديد') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <form method="POST" action="{{ route('commandes.store') }}" enctype="multipart/form-data" class="space-y-6">
                        @csrf

                        <!-- عنوان المشروع -->
                        <div>
                            <x-input-label for="titre" :value="__('عنوان المشروع')" />
                            <x-text-input id="titre" class="block mt-1 w-full" type="text" name="titre" :value="old('titre')" required autofocus />
                            <x-input-error :messages="$errors->get('titre')" class="mt-2" />
                        </div>

                        <!-- وصف المشروع -->
                        <div>
                            <x-input-label for="description" :value="__('وصف المشروع')" />
                            <textarea id="description" name="description" rows="5" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" required>{{ old('description') }}</textarea>
                            <x-input-error :messages="$errors->get('description')" class="mt-2" />
                        </div>

                        <!-- التخصص المطلوب -->
                        <div>
                            <x-input-label for="specialist" :value="__('التخصص المطلوب')" />
                            <select id="specialist" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" name="specialist" required>
                                <option value="" selected disabled>اختر التخصص المطلوب</option>
                                <option value="الهندسة الكهربائية" {{ old('specialist') == 'الهندسة الكهربائية' ? 'selected' : '' }}>الهندسة الكهربائية</option>
                                <option value="النجارة العامة" {{ old('specialist') == 'النجارة العامة' ? 'selected' : '' }}>النجارة العامة</option>
                                <option value="الحدادة الفنية" {{ old('specialist') == 'الحدادة الفنية' ? 'selected' : '' }}>الحدادة الفنية</option>
                                <option value="الميكانيك العام" {{ old('specialist') == 'الميكانيك العام' ? 'selected' : '' }}>الميكانيك العام</option>
                                <option value="السباكة والتدفئة" {{ old('specialist') == 'السباكة والتدفئة' ? 'selected' : '' }}>السباكة والتدفئة</option>
                                <option value="الخياطة وتصميم الأزياء" {{ old('specialist') == 'الخياطة وتصميم الأزياء' ? 'selected' : '' }}>الخياطة وتصميم الأزياء</option>
                                <option value="تكييف الهواء" {{ old('specialist') == 'تكييف الهواء' ? 'selected' : '' }}>تكييف الهواء</option>
                                <option value="البناء" {{ old('specialist') == 'البناء' ? 'selected' : '' }}>البناء</option>
                                <option value="التركيبات الصحية والغاز" {{ old('specialist') == 'التركيبات الصحية والغاز' ? 'selected' : '' }}>التركيبات الصحية والغاز</option>
                                <option value="ميكانيك زراعي" {{ old('specialist') == 'ميكانيك زراعي' ? 'selected' : '' }}>ميكانيك زراعي</option>
                            </select>
                            <x-input-error :messages="$errors->get('specialist')" class="mt-2" />
                        </div>

                        <!-- الميزانية -->
                        <div>
                            <x-input-label for="budget" :value="__('الميزانية (بالدينار الجزائري)')" />
                            <x-text-input id="budget" class="block mt-1 w-full" type="number" step="0.01" min="0" name="budget" :value="old('budget')" required />
                            <x-input-error :messages="$errors->get('budget')" class="mt-2" />
                        </div>

                        <!-- الولاية -->
                        <div>
                            <x-input-label for="address" :value="__('الولاية')" />
                            <x-province-select name="address" id="address" :selected="old('address')" required />
                        </div>

                        <!-- الوسائط -->
                        <div>
                            <x-input-label for="media" :value="__('صور أو فيديوهات توضيحية')" />
                            <div class="mt-1">
                                <input type="file" name="media[]" id="media" multiple class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100" />
                                <p class="text-xs text-gray-500 mt-1">{{ __('يمكنك تحميل عدة ملفات (يدعم JPG, PNG, GIF, MP4, AVI, MOV). الحد الأقصى 100 ميجابايت لكل ملف.') }}</p>
                            </div>
                            <x-input-error :messages="$errors->get('media.*')" class="mt-2" />
                        </div>

                        <!-- روابط توضيحية -->
                        <div>
                            <x-input-label for="media_url" :value="__('روابط توضيحية (اختياري)')" />
                            <div class="mt-1 space-y-2" id="media-urls-container">
                                <div class="flex items-center">
                                    <x-text-input class="block w-full" type="url" name="media_url[]" placeholder="https://..." />
                                    <button type="button" class="add-url-button ms-2 px-3 py-2 bg-green-500 text-white rounded hover:bg-green-600">+</button>
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <a href="{{ route('commandes.index') }}" class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800 me-3">
                                {{ __('إلغاء') }}
                            </a>
                            <x-primary-button>
                                {{ __('إضافة المشروع') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript to add more URL fields -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const container = document.getElementById('media-urls-container');
            const addButton = document.querySelector('.add-url-button');
            
            addButton.addEventListener('click', function() {
                const div = document.createElement('div');
                div.className = 'flex items-center mt-2';
                div.innerHTML = `
                    <input type="url" name="media_url[]" class="block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" placeholder="https://...">
                    <button type="button" class="remove-url-button ms-2 px-3 py-2 bg-red-500 text-white rounded hover:bg-red-600">-</button>
                `;
                
                container.appendChild(div);
                
                // Add event listener to the remove button
                div.querySelector('.remove-url-button').addEventListener('click', function() {
                    container.removeChild(div);
                });
            });
        });
    </script>
</x-app-layout> 