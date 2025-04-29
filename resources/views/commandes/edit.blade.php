<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('تعديل المشروع') }}: {{ $commande->titre }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <form method="POST" action="{{ route('commandes.update', $commande) }}" enctype="multipart/form-data" class="space-y-6">
                        @csrf
                        @method('PUT')

                        <!-- عنوان المشروع -->
                        <div>
                            <x-input-label for="titre" :value="__('عنوان المشروع')" />
                            <x-text-input id="titre" class="block mt-1 w-full" type="text" name="titre" :value="old('titre', $commande->titre)" required autofocus />
                            <x-input-error :messages="$errors->get('titre')" class="mt-2" />
                        </div>

                        <!-- وصف المشروع -->
                        <div>
                            <x-input-label for="description" :value="__('وصف المشروع')" />
                            <textarea id="description" name="description" rows="5" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" required>{{ old('description', $commande->description) }}</textarea>
                            <x-input-error :messages="$errors->get('description')" class="mt-2" />
                        </div>

                        <!-- التخصص المطلوب -->
                        <div>
                            <x-input-label for="specialist" :value="__('التخصص المطلوب')" />
                            <select id="specialist" name="specialist" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                                <option value="" disabled>{{ __('اختر التخصص المطلوب') }}</option>
                                <option value="الهندسة الكهربائية" {{ old('specialist', $commande->specialist) == 'الهندسة الكهربائية' ? 'selected' : '' }}>الهندسة الكهربائية</option>
                                <option value="النجارة العامة" {{ old('specialist', $commande->specialist) == 'النجارة العامة' ? 'selected' : '' }}>النجارة العامة</option>
                                <option value="الحدادة الفنية" {{ old('specialist', $commande->specialist) == 'الحدادة الفنية' ? 'selected' : '' }}>الحدادة الفنية</option>
                                <option value="السباكة وشبكات المياه" {{ old('specialist', $commande->specialist) == 'السباكة وشبكات المياه' ? 'selected' : '' }}>السباكة وشبكات المياه</option>
                                <option value="البناء والمقاولات" {{ old('specialist', $commande->specialist) == 'البناء والمقاولات' ? 'selected' : '' }}>البناء والمقاولات</option>
                                <option value="الدهان والديكور" {{ old('specialist', $commande->specialist) == 'الدهان والديكور' ? 'selected' : '' }}>الدهان والديكور</option>
                                <option value="التكييف والتبريد" {{ old('specialist', $commande->specialist) == 'التكييف والتبريد' ? 'selected' : '' }}>التكييف والتبريد</option>
                                <option value="تبليط وسيراميك" {{ old('specialist', $commande->specialist) == 'تبليط وسيراميك' ? 'selected' : '' }}>تبليط وسيراميك</option>
                                <option value="الألمنيوم والزجاج" {{ old('specialist', $commande->specialist) == 'الألمنيوم والزجاج' ? 'selected' : '' }}>الألمنيوم والزجاج</option>
                                <option value="أعمال حدائق" {{ old('specialist', $commande->specialist) == 'أعمال حدائق' ? 'selected' : '' }}>أعمال حدائق</option>
                                <option value="تنظيف مباني" {{ old('specialist', $commande->specialist) == 'تنظيف مباني' ? 'selected' : '' }}>تنظيف مباني</option>
                                <option value="صيانة عامة" {{ old('specialist', $commande->specialist) == 'صيانة عامة' ? 'selected' : '' }}>صيانة عامة</option>
                                <option value="أخرى" {{ old('specialist', $commande->specialist) == 'أخرى' ? 'selected' : '' }}>أخرى</option>
                            </select>
                            <x-input-error :messages="$errors->get('specialist')" class="mt-2" />
                        </div>

                        <!-- الميزانية -->
                        <div>
                            <x-input-label for="budget" :value="__('الميزانية (بالدينار الجزائري)')" />
                            <x-text-input id="budget" class="block mt-1 w-full" type="number" step="0.01" min="0" name="budget" :value="old('budget', $commande->budget)" required />
                            <x-input-error :messages="$errors->get('budget')" class="mt-2" />
                        </div>

                        <!-- العنوان -->
                        <div>
                            <x-input-label for="address" :value="__('الولاية')" />
                            <x-province-select name="address" id="address" :selected="old('address', $commande->address)" required />
                            <x-input-error :messages="$errors->get('address')" class="mt-2" />
                        </div>

                        <!-- الوسائط الحالية -->
                        @if($commande->media && count($commande->media) > 0)
                            <div>
                                <x-input-label :value="__('الوسائط الحالية')" />
                                <div class="mt-2 grid grid-cols-1 md:grid-cols-3 gap-4">
                                    @foreach($commande->media as $index => $media)
                                        <div class="bg-gray-50 dark:bg-gray-700 p-2 rounded-lg relative">
                                            <div class="absolute top-2 right-2 z-10">
                                                <input type="checkbox" name="deleted_media[]" value="{{ $index }}" id="delete-media-{{ $index }}" class="delete-media-checkbox">
                                                <label for="delete-media-{{ $index }}" class="bg-red-500 text-white rounded-full w-8 h-8 flex items-center justify-center cursor-pointer hover:bg-red-600 shadow-md delete-media-label">
                                                    &times;
                                                </label>
                                            </div>
                                            
                                            @if(isset($media['type']) && strpos($media['type'], 'image') !== false)
                                                <img src="{{ asset('storage/' . $media['path']) }}" class="w-full h-32 object-cover rounded" alt="{{ $media['name'] ?? 'صورة' }}">
                                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1 truncate">{{ $media['name'] ?? 'صورة' }}</p>
                                            @elseif(isset($media['type']) && strpos($media['type'], 'video') !== false)
                                                <video class="w-full h-32 object-cover rounded">
                                                    <source src="{{ asset('storage/' . $media['path']) }}" type="{{ $media['type'] }}">
                                                </video>
                                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1 truncate">{{ $media['name'] ?? 'فيديو' }}</p>
                                            @elseif(isset($media['url']))
                                                <div class="p-2 h-32 flex items-center justify-center">
                                                    <a href="{{ $media['url'] }}" target="_blank" class="text-blue-600 dark:text-blue-400 hover:underline text-sm break-all">
                                                        {{ $media['url'] }}
                                                    </a>
                                                </div>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                                <p class="text-sm text-gray-500 mt-2">{{ __('انقر على زر X الأحمر للإشارة إلى الوسائط التي تريد حذفها. ستتحول إلى علامة صح خضراء عند تحديدها للحذف.') }}</p>
                            </div>
                        @endif

                        <!-- إضافة وسائط جديدة -->
                        <div>
                            <x-input-label for="media" :value="__('إضافة صور أو فيديوهات جديدة')" />
                            <div class="mt-1">
                                <input type="file" name="media[]" id="media" multiple class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100" />
                                <p class="text-xs text-gray-500 mt-1">{{ __('يمكنك تحميل عدة ملفات (يدعم JPG, PNG, GIF, MP4, AVI, MOV). الحد الأقصى 100 ميجابايت لكل ملف.') }}</p>
                            </div>
                            <x-input-error :messages="$errors->get('media.*')" class="mt-2" />
                        </div>

                        <!-- روابط توضيحية -->
                        <div>
                            <x-input-label for="media_url" :value="__('إضافة روابط توضيحية جديدة (اختياري)')" />
                            <div class="mt-1 space-y-2" id="media-urls-container">
                                <div class="flex items-center">
                                    <x-text-input class="block w-full" type="url" name="media_url[]" placeholder="https://..." />
                                    <button type="button" class="add-url-button ms-2 px-3 py-2 bg-green-500 text-white rounded hover:bg-green-600">+</button>
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <a href="{{ route('commandes.show', $commande) }}" class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800 me-3">
                                {{ __('إلغاء') }}
                            </a>
                            <x-primary-button>
                                {{ __('حفظ التعديلات') }}
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
            
            // Manejo de botones para añadir URLs
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
            
            // تعديل التعامل مع حذف الوسائط
            const deleteCheckboxes = document.querySelectorAll('.delete-media-checkbox');
            const deleteLabels = document.querySelectorAll('.delete-media-label');
            
            // إضافة مستمع للأحداث لكل زر حذف
            deleteLabels.forEach((label, index) => {
                label.addEventListener('click', function(e) {
                    // الحصول على الـcheckbox المرتبط وتغيير حالته
                    const checkbox = deleteCheckboxes[index];
                    checkbox.checked = !checkbox.checked;
                    
                    // تغيير مظهر الزر والعنصر المحدد للحذف
                    const mediaCard = this.closest('.bg-gray-50, .dark\\:bg-gray-700');
                    if (checkbox.checked) {
                        // إضافة طبقة شفافة للإشارة إلى أنه سيتم حذفه
                        mediaCard.style.opacity = '0.5';
                        this.innerHTML = '✓'; // تغيير X إلى علامة صح
                        this.classList.remove('bg-red-500');
                        this.classList.add('bg-green-500');
                    } else {
                        // إعادة المظهر الطبيعي
                        mediaCard.style.opacity = '1';
                        this.innerHTML = '&times;'; // إعادة X
                        this.classList.remove('bg-green-500');
                        this.classList.add('bg-red-500');
                    }
                    
                    // طباعة حالة الـcheckbox في وحدة التحكم للتحقق
                    console.log('Checkbox checked:', checkbox.checked, 'Value:', checkbox.value);
                    
                    // منع السلوك الافتراضي
                    e.preventDefault();
                });
            });
            
            // التحقق من نموذج الإرسال قبل الإرسال
            const form = document.querySelector('form');
            form.addEventListener('submit', function(e) {
                // التحقق من الـcheckbox المحددة قبل الإرسال
                const checkedBoxes = document.querySelectorAll('.delete-media-checkbox:checked');
                console.log('Submitting form with', checkedBoxes.length, 'items to delete');
                checkedBoxes.forEach(box => {
                    console.log('Will delete item at index:', box.value);
                });
                
                // استمرار بعملية الإرسال
                return true;
            });
        });
    </script>
</x-app-layout> 