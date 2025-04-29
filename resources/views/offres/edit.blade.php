<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('تعديل العرض') }}
            </h2>
            <a href="{{ route('commandes.show', $commande) }}" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                {{ __('العودة إلى تفاصيل المشروع') }}
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">{{ __('معلومات المشروع') }}</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <p class="text-gray-600 dark:text-gray-400 mb-2"><span class="font-medium">{{ __('عنوان المشروع:') }}</span> {{ $commande->titre }}</p>
                            <p class="text-gray-600 dark:text-gray-400 mb-2"><span class="font-medium">{{ __('التخصص:') }}</span> {{ $commande->specialist }}</p>
                            <p class="text-gray-600 dark:text-gray-400 mb-2"><span class="font-medium">{{ __('الميزانية:') }}</span> {{ $commande->budget }} دج</p>
                        </div>
                        <div>
                            <p class="text-gray-600 dark:text-gray-400 mb-2"><span class="font-medium">{{ __('العنوان:') }}</span> {{ $commande->address }}</p>
                            <p class="text-gray-600 dark:text-gray-400"><span class="font-medium">{{ __('تاريخ النشر:') }}</span> {{ $commande->created_at->format('Y-m-d') }}</p>
                        </div>
                    </div>
                    <div class="mt-4">
                        <p class="text-gray-600 dark:text-gray-400"><span class="font-medium">{{ __('الوصف:') }}</span></p>
                        <p class="mt-1 text-gray-600 dark:text-gray-400 whitespace-pre-line">{{ $commande->description }}</p>
                    </div>
                </div>
                
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">{{ __('تعديل العرض') }}</h3>
                    
                    <!-- رسالة حالة العرض -->
                    @if($offre->status !== 'pending')
                    <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4 mb-6" role="alert">
                        <p>
                            @if($offre->status === 'accepted')
                                {{ __('تم قبول هذا العرض. لا يمكنك تعديله.') }}
                            @elseif($offre->status === 'rejected')
                                {{ __('تم رفض هذا العرض. لا يمكنك تعديله.') }}
                            @endif
                        </p>
                    </div>
                    @endif
                    
                    <form method="POST" action="{{ route('offres.update', $offre) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- السعر المقترح -->
                            <div>
                                <x-input-label for="price" :value="__('السعر المقترح (بالدينار الجزائري)')" />
                                <x-text-input id="price" class="block mt-1 w-full" type="number" min="0" step="0.01" name="price" :value="old('price', $offre->price)" required autofocus />
                                <x-input-error :messages="$errors->get('price')" class="mt-2" />
                            </div>
                            
                            <!-- تاريخ التسليم المتوقع -->
                            <div>
                                <x-input-label for="delivery_date" :value="__('تاريخ التسليم المتوقع')" />
                                <x-text-input id="delivery_date" class="block mt-1 w-full" type="date" 
                                   name="delivery_date" 
                                   :value="old('delivery_date', $offre->delivery_date->format('Y-m-d'))" 
                                   min="{{ date('Y-m-d', strtotime('+1 day')) }}"
                                   required />
                                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{ __('يجب أن يكون تاريخ التسليم مستقبليًا ولا يمكن أن يكون اليوم أو تاريخًا سابقًا') }}</p>
                                <x-input-error :messages="$errors->get('delivery_date')" class="mt-2" />
                            </div>
                        </div>
                        
                        <!-- وصف طريقة العمل -->
                        <div class="mt-4">
                            <x-input-label for="description" :value="__('وصف طريقة العمل')" />
                            <textarea id="description" name="description" rows="6" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" required>{{ old('description', $offre->description) }}</textarea>
                            <x-input-error :messages="$errors->get('description')" class="mt-2" />
                        </div>
                        
                        <!-- الوسائط الحالية -->
                        @if(isset($offre->media) && count($offre->media) > 0)
                        <div class="mt-6">
                            <h4 class="font-medium text-gray-700 dark:text-gray-300 mb-3">{{ __('الوسائط المرفقة حالياً') }}</h4>
                            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                                @foreach($offre->media as $index => $media)
                                    <div class="relative group" id="media-item-{{ $index }}">
                                        @if(isset($media['type']) && str_contains($media['type'], 'image'))
                                            <img src="{{ isset($media['path']) ? asset('storage/' . $media['path']) : $media['url'] }}" 
                                                alt="{{ $media['name'] ?? 'صورة العرض' }}" 
                                                class="h-32 w-full object-cover rounded-lg shadow-sm">
                                        @elseif(isset($media['type']) && str_contains($media['type'], 'video'))
                                            <video class="h-32 w-full object-cover rounded-lg shadow-sm" controls>
                                                <source src="{{ isset($media['path']) ? asset('storage/' . $media['path']) : $media['url'] }}" type="{{ $media['type'] }}">
                                                {{ __('متصفحك لا يدعم عرض الفيديو') }}
                                            </video>
                                        @elseif(isset($media['type']) && $media['type'] == 'url')
                                            <div class="h-32 p-4 flex items-center justify-center border border-gray-200 dark:border-gray-700 rounded-lg shadow-sm bg-gray-50 dark:bg-gray-700">
                                                <a href="{{ $media['url'] }}" target="_blank" class="text-blue-600 dark:text-blue-400 hover:underline break-all">
                                                    {{ $media['url'] }}
                                                </a>
                                            </div>
                                        @endif
                                        
                                        @if($offre->status === 'pending')
                                        <div class="absolute top-2 right-2">
                                            <input type="checkbox" id="delete_media_{{ $index }}" name="deleted_media[]" value="{{ $index }}" class="hidden delete-media-checkbox">
                                            <label for="delete_media_{{ $index }}" class="delete-media-btn bg-red-500 hover:bg-red-600 text-white rounded-full p-1 cursor-pointer">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                                                </svg>
                                            </label>
                                        </div>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        @endif
                        
                        @if($offre->status === 'pending')
                        <!-- رفع الصور والفيديوهات جديدة -->
                        <div class="mt-6">
                            <x-input-label :value="__('إضافة صور أو فيديوهات توضيحية جديدة (اختياري)')" />
                            <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 dark:border-gray-700 border-dashed rounded-md">
                                <div class="space-y-1 text-center">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                                        <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                    <div class="flex justify-center text-sm text-gray-600 dark:text-gray-400">
                                        <label for="media" class="relative cursor-pointer bg-white dark:bg-gray-800 rounded-md font-medium text-indigo-600 dark:text-indigo-400 hover:text-indigo-500 dark:hover:text-indigo-300 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-indigo-500">
                                            <span>{{ __('اختر ملفات') }}</span>
                                            <input id="media" name="media[]" type="file" class="sr-only" multiple accept="image/*,video/*">
                                        </label>
                                        <p class="pr-1">{{ __('أو اسحب وأفلت الملفات هنا') }}</p>
                                    </div>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">
                                        {{ __('صور (PNG, JPG, GIF) أو فيديوهات (MP4, AVI, MOV) - الحد الأقصى 10 ميجابايت') }}
                                    </p>
                                </div>
                            </div>
                            <div class="mt-2" id="fileList"></div>
                            <x-input-error :messages="$errors->get('media.*')" class="mt-2" />
                        </div>

                        <!-- إضافة روابط جديدة -->
                        <div class="mt-6">
                            <x-input-label :value="__('إضافة روابط توضيحية جديدة (اختياري)')" />
                            <div id="urls-container">
                                <div class="flex mt-1">
                                    <x-text-input type="url" name="media_url[]" class="block w-full" placeholder="https://example.com" />
                                    <button type="button" class="add-url-btn px-3 py-2 mr-2 text-white bg-blue-600 rounded-md hover:bg-blue-700">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                            <x-input-error :messages="$errors->get('media_url.*')" class="mt-2" />
                        </div>
                        
                        <div class="flex justify-end mt-6">
                            <x-primary-button>
                                {{ __('تحديث العرض') }}
                            </x-primary-button>
                        </div>
                        @endif
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // التحقق من تاريخ التسليم
            const deliveryDateInput = document.getElementById('delivery_date');
            if (deliveryDateInput && !deliveryDateInput.disabled) {
                const tomorrow = new Date();
                tomorrow.setDate(tomorrow.getDate() + 1);
                const tomorrowStr = tomorrow.toISOString().split('T')[0];
                
                deliveryDateInput.addEventListener('change', function() {
                    if (this.value < tomorrowStr) {
                        this.setCustomValidity('يجب أن يكون تاريخ التسليم المتوقع في المستقبل');
                        // إضافة حدود حمراء للإشارة إلى الخطأ
                        this.classList.add('border-red-500');
                    } else {
                        this.setCustomValidity('');
                        // إزالة حدود الخطأ
                        this.classList.remove('border-red-500');
                    }
                });
            }

            // عرض أسماء الملفات المختارة
            const mediaInput = document.getElementById('media');
            const fileList = document.getElementById('fileList');
            
            if (mediaInput) {
                mediaInput.addEventListener('change', function() {
                    fileList.innerHTML = '';
                    for (let i = 0; i < this.files.length; i++) {
                        const file = this.files[i];
                        const fileItem = document.createElement('div');
                        fileItem.className = 'text-sm text-gray-600 dark:text-gray-400 mt-1';
                        fileItem.textContent = file.name + ' (' + (file.size / 1024 / 1024).toFixed(2) + ' MB)';
                        fileList.appendChild(fileItem);
                    }
                });
            }
            
            // إضافة حقول روابط جديدة
            const urlsContainer = document.getElementById('urls-container');
            if (urlsContainer) {
                document.querySelectorAll('.add-url-btn').forEach(btn => {
                    btn.addEventListener('click', addUrlField);
                });
            }
            
            function addUrlField() {
                const urlField = document.createElement('div');
                urlField.className = 'flex mt-2';
                urlField.innerHTML = `
                    <x-text-input type="url" name="media_url[]" class="block w-full" placeholder="https://example.com" />
                    <button type="button" class="remove-url-btn px-3 py-2 mr-2 text-white bg-red-600 rounded-md hover:bg-red-700">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM7 9a1 1 0 000 2h6a1 1 0 100-2H7z" clip-rule="evenodd" />
                        </svg>
                    </button>
                `;
                urlsContainer.appendChild(urlField);
                
                urlField.querySelector('.remove-url-btn').addEventListener('click', function() {
                    urlField.remove();
                });
            }
            
            // تأثير بصري عند اختيار حذف الوسائط
            document.querySelectorAll('.delete-media-checkbox').forEach(checkbox => {
                checkbox.addEventListener('change', function() {
                    const mediaItem = document.getElementById('media-item-' + this.value);
                    if (this.checked) {
                        mediaItem.classList.add('opacity-50');
                    } else {
                        mediaItem.classList.remove('opacity-50');
                    }
                });
            });
        });
    </script>
</x-app-layout> 