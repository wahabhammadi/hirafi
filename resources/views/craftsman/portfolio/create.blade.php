<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('إضافة عمل جديد') }}
            </h2>
            <a href="{{ route('craftsman.portfolio.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                {{ __('العودة للقائمة') }}
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <form action="{{ route('craftsman.portfolio.store') }}" method="POST" enctype="multipart/form-data" x-data="{ 
                        imageCount: 1,
                        videoCount: 1,
                        linkCount: 1,
                        addImageField() { this.imageCount++; },
                        addVideoField() { this.videoCount++; },
                        addLinkField() { this.linkCount++; },
                        removeImageField(index) { 
                            document.getElementById('image-group-' + index).remove();
                        },
                        removeVideoField(index) { 
                            document.getElementById('video-group-' + index).remove();
                        },
                        removeLinkField(index) { 
                            document.getElementById('link-group-' + index).remove();
                        }
                    }">
                        @csrf
                        
                        <!-- معلومات أساسية -->
                        <div class="mb-6">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">
                                {{ __('المعلومات الأساسية') }}
                            </h3>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <x-input-label for="title" :value="__('عنوان العمل')" />
                                    <x-text-input id="title" name="title" type="text" class="mt-1 block w-full" :value="old('title')" required autofocus />
                                    <x-input-error class="mt-2" :messages="$errors->get('title')" />
                                </div>
                                
                                <div>
                                    <x-input-label for="completion_date" :value="__('تاريخ الإنجاز')" />
                                    <x-text-input id="completion_date" name="completion_date" type="date" class="mt-1 block w-full" :value="old('completion_date')" required />
                                    <x-input-error class="mt-2" :messages="$errors->get('completion_date')" />
                                </div>
                            </div>
                            
                            <div class="mt-4">
                                <x-input-label for="description" :value="__('وصف العمل')" />
                                <textarea id="description" name="description" rows="4" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" required>{{ old('description') }}</textarea>
                                <x-input-error class="mt-2" :messages="$errors->get('description')" />
                            </div>
                            
                            <div class="mt-4 flex items-center">
                                <input id="is_featured" name="is_featured" type="checkbox" class="rounded dark:bg-gray-900 border-gray-300 dark:border-gray-700 text-indigo-600 shadow-sm focus:ring-indigo-500 dark:focus:ring-indigo-600 dark:focus:ring-offset-gray-800" {{ old('is_featured') ? 'checked' : '' }}>
                                <label for="is_featured" class="mr-2 text-sm text-gray-600 dark:text-gray-400">{{ __('عرض كعمل مميز') }}</label>
                            </div>
                        </div>
                        
                        <!-- الصور -->
                        <div class="mb-6 border-t pt-6 border-gray-200 dark:border-gray-700">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">
                                {{ __('صور العمل') }}
                            </h3>
                            
                            <div class="mb-2">
                                <p class="text-sm text-gray-500 dark:text-gray-400">
                                    {{ __('يمكنك إضافة صور متعددة لإظهار العمل بشكل أفضل (الحد الأقصى لحجم الصورة: 5 ميجابايت)') }}
                                </p>
                            </div>
                            
                            <template x-for="i in imageCount" :key="i">
                                <div :id="'image-group-' + i" class="mb-4 bg-gray-50 dark:bg-gray-700 p-4 rounded-lg relative">
                                    <button 
                                        type="button" 
                                        class="absolute top-2 left-2 text-red-500 hover:text-red-700"
                                        x-show="imageCount > 1"
                                        @click="removeImageField(i)">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                        </svg>
                                    </button>
                                    
                                    <label :for="'images_' + i" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        {{ __('صورة') }} <span x-text="i"></span>
                                    </label>
                                    <input 
                                        :id="'images_' + i" 
                                        name="images[]" 
                                        type="file" 
                                        accept="image/*"
                                        class="mt-1 block w-full text-sm text-gray-900 dark:text-gray-100 border border-gray-300 dark:border-gray-700 rounded-lg cursor-pointer bg-gray-50 dark:bg-gray-800 focus:outline-none">
                                </div>
                            </template>
                            
                            <div class="mt-2">
                                <button 
                                    type="button" 
                                    @click="addImageField"
                                    class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs leading-4 font-medium rounded text-blue-700 bg-blue-100 hover:bg-blue-200 dark:bg-blue-900 dark:text-blue-200 dark:hover:bg-blue-800 focus:outline-none transition ease-in-out duration-150">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                                    </svg>
                                    {{ __('إضافة صورة أخرى') }}
                                </button>
                            </div>
                        </div>
                        
                        <!-- الفيديوهات -->
                        <div class="mb-6 border-t pt-6 border-gray-200 dark:border-gray-700">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">
                                {{ __('فيديوهات العمل') }} ({{ __('اختياري') }})
                            </h3>
                            
                            <div class="mb-2">
                                <p class="text-sm text-gray-500 dark:text-gray-400">
                                    {{ __('يمكنك إضافة فيديوهات لإظهار العمل بشكل أفضل (الحد الأقصى لحجم الفيديو: 20 ميجابايت)') }}
                                </p>
                            </div>
                            
                            <template x-for="i in videoCount" :key="i">
                                <div :id="'video-group-' + i" class="mb-4 bg-gray-50 dark:bg-gray-700 p-4 rounded-lg relative">
                                    <button 
                                        type="button" 
                                        class="absolute top-2 left-2 text-red-500 hover:text-red-700"
                                        x-show="videoCount > 1"
                                        @click="removeVideoField(i)">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                        </svg>
                                    </button>
                                    
                                    <label :for="'videos_' + i" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        {{ __('فيديو') }} <span x-text="i"></span>
                                    </label>
                                    <input 
                                        :id="'videos_' + i" 
                                        name="videos[]" 
                                        type="file" 
                                        accept="video/*"
                                        class="mt-1 block w-full text-sm text-gray-900 dark:text-gray-100 border border-gray-300 dark:border-gray-700 rounded-lg cursor-pointer bg-gray-50 dark:bg-gray-800 focus:outline-none">
                                </div>
                            </template>
                            
                            <div class="mt-2">
                                <button 
                                    type="button" 
                                    @click="addVideoField"
                                    class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs leading-4 font-medium rounded text-blue-700 bg-blue-100 hover:bg-blue-200 dark:bg-blue-900 dark:text-blue-200 dark:hover:bg-blue-800 focus:outline-none transition ease-in-out duration-150">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                                    </svg>
                                    {{ __('إضافة فيديو آخر') }}
                                </button>
                            </div>
                        </div>
                        
                        <!-- الروابط -->
                        <div class="mb-6 border-t pt-6 border-gray-200 dark:border-gray-700">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">
                                {{ __('روابط إضافية') }} ({{ __('اختياري') }})
                            </h3>
                            
                            <div class="mb-2">
                                <p class="text-sm text-gray-500 dark:text-gray-400">
                                    {{ __('يمكنك إضافة روابط خارجية ذات صلة بالعمل (مثل رابط موقع العمل، فيديو على يوتيوب، إلخ)') }}
                                </p>
                            </div>
                            
                            <template x-for="i in linkCount" :key="i">
                                <div :id="'link-group-' + i" class="mb-4 bg-gray-50 dark:bg-gray-700 p-4 rounded-lg relative">
                                    <button 
                                        type="button" 
                                        class="absolute top-2 left-2 text-red-500 hover:text-red-700"
                                        x-show="linkCount > 1"
                                        @click="removeLinkField(i)">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                        </svg>
                                    </button>
                                    
                                    <label :for="'links_' + i" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        {{ __('رابط') }} <span x-text="i"></span>
                                    </label>
                                    <input 
                                        :id="'links_' + i" 
                                        name="links[]" 
                                        type="url" 
                                        placeholder="https://"
                                        class="mt-1 block w-full text-sm text-gray-900 dark:text-gray-100 border border-gray-300 dark:border-gray-700 rounded-lg bg-white dark:bg-gray-800 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600">
                                </div>
                            </template>
                            
                            <div class="mt-2">
                                <button 
                                    type="button" 
                                    @click="addLinkField"
                                    class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs leading-4 font-medium rounded text-blue-700 bg-blue-100 hover:bg-blue-200 dark:bg-blue-900 dark:text-blue-200 dark:hover:bg-blue-800 focus:outline-none transition ease-in-out duration-150">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                                    </svg>
                                    {{ __('إضافة رابط آخر') }}
                                </button>
                            </div>
                        </div>
                        
                        <div class="flex items-center justify-end mt-8">
                            <x-primary-button>
                                {{ __('إضافة العمل') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 