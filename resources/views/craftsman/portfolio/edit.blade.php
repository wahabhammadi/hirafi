<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('تعديل العمل') }}: {{ $work->title }}
            </h2>
            <a href="{{ route('craftsman.portfolio.show', $work) }}" class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                {{ __('العودة للتفاصيل') }}
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <form action="{{ route('craftsman.portfolio.update', $work) }}" method="POST" enctype="multipart/form-data" x-data="{ 
                        imageCount: 1,
                        videoCount: 1,
                        linkCount: 1,
                        removedMedia: [],
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
                        },
                        removeMedia(index) {
                            this.removedMedia.push(index);
                            document.getElementById('media-' + index).classList.add('opacity-50');
                            document.getElementById('media-' + index).classList.add('grayscale');
                            document.getElementById('remove-btn-' + index).classList.add('hidden');
                            document.getElementById('restore-btn-' + index).classList.remove('hidden');
                        },
                        restoreMedia(index) {
                            this.removedMedia = this.removedMedia.filter(item => item !== index);
                            document.getElementById('media-' + index).classList.remove('opacity-50');
                            document.getElementById('media-' + index).classList.remove('grayscale');
                            document.getElementById('remove-btn-' + index).classList.remove('hidden');
                            document.getElementById('restore-btn-' + index).classList.add('hidden');
                        }
                    }">
                        @csrf
                        @method('PUT')
                        
                        <input type="hidden" name="removed_media" x-bind:value="JSON.stringify(removedMedia)">
                        
                        <!-- معلومات أساسية -->
                        <div class="mb-6">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">
                                {{ __('المعلومات الأساسية') }}
                            </h3>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <x-input-label for="title" :value="__('عنوان العمل')" />
                                    <x-text-input id="title" name="title" type="text" class="mt-1 block w-full" :value="old('title', $work->title)" required autofocus />
                                    <x-input-error class="mt-2" :messages="$errors->get('title')" />
                                </div>
                                
                                <div>
                                    <x-input-label for="completion_date" :value="__('تاريخ الإنجاز')" />
                                    <x-text-input id="completion_date" name="completion_date" type="date" class="mt-1 block w-full" :value="old('completion_date', $work->completion_date->format('Y-m-d'))" required />
                                    <x-input-error class="mt-2" :messages="$errors->get('completion_date')" />
                                </div>
                            </div>
                            
                            <div class="mt-4">
                                <x-input-label for="description" :value="__('وصف العمل')" />
                                <textarea id="description" name="description" rows="4" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" required>{{ old('description', $work->description) }}</textarea>
                                <x-input-error class="mt-2" :messages="$errors->get('description')" />
                            </div>
                            
                            <div class="mt-4 flex items-center">
                                <input id="is_featured" name="is_featured" type="checkbox" class="rounded dark:bg-gray-900 border-gray-300 dark:border-gray-700 text-indigo-600 shadow-sm focus:ring-indigo-500 dark:focus:ring-indigo-600 dark:focus:ring-offset-gray-800" {{ old('is_featured', $work->is_featured) ? 'checked' : '' }}>
                                <label for="is_featured" class="mr-2 text-sm text-gray-600 dark:text-gray-400">{{ __('عرض كعمل مميز') }}</label>
                            </div>
                        </div>
                        
                        <!-- الوسائط الحالية -->
                        @if(isset($work->media) && count($work->media) > 0)
                        <div class="mb-6 border-t pt-6 border-gray-200 dark:border-gray-700">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">
                                {{ __('الوسائط الحالية') }}
                            </h3>
                            
                            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                                @foreach($work->media as $index => $media)
                                <div id="media-{{ $index }}" class="bg-white dark:bg-gray-700 rounded-lg shadow overflow-hidden transition-all">
                                    <div class="relative h-40">
                                        @if(isset($media['type']) && str_contains($media['type'], 'image'))
                                            <img src="{{ isset($media['path']) ? asset('storage/' . $media['path']) : $media['url'] }}" 
                                                 alt="{{ $media['name'] ?? $work->title }}" 
                                                 class="w-full h-full object-cover">
                                        @elseif(isset($media['type']) && str_contains($media['type'], 'video'))
                                            <video class="w-full h-full object-cover">
                                                <source src="{{ isset($media['path']) ? asset('storage/' . $media['path']) : $media['url'] }}" type="{{ $media['type'] }}">
                                            </video>
                                            <div class="absolute inset-0 flex items-center justify-center bg-black bg-opacity-50 text-white">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                            </div>
                                        @elseif(isset($media['type']) && $media['type'] == 'url')
                                            <div class="w-full h-full flex items-center justify-center bg-gray-100 dark:bg-gray-800 p-4">
                                                <a href="{{ $media['url'] }}" target="_blank" class="text-blue-600 dark:text-blue-400 hover:underline break-all">
                                                    {{ $media['url'] }}
                                                </a>
                                            </div>
                                        @endif
                                    </div>
                                    
                                    <div class="p-3 flex justify-between items-center">
                                        <span class="text-xs text-gray-600 dark:text-gray-300 truncate">
                                            @if(isset($media['name']))
                                                {{ $media['name'] }}
                                            @elseif(isset($media['url']))
                                                {{ Str::limit($media['url'], 20) }}
                                            @else
                                                {{ __('وسائط') }} #{{ $index + 1 }}
                                            @endif
                                        </span>
                                        
                                        <div>
                                            <button 
                                                type="button" 
                                                id="remove-btn-{{ $index }}"
                                                @click="removeMedia({{ $index }})" 
                                                class="text-red-600 hover:text-red-800 dark:text-red-500 dark:hover:text-red-400">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                                                </svg>
                                            </button>
                                            <button 
                                                type="button" 
                                                id="restore-btn-{{ $index }}"
                                                @click="restoreMedia({{ $index }})" 
                                                class="text-green-600 hover:text-green-800 dark:text-green-500 dark:hover:text-green-400 hidden">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd" d="M4 2a1 1 0 011 1v2.101a7.002 7.002 0 0111.601 2.566 1 1 0 11-1.885.666A5.002 5.002 0 005.999 7H9a1 1 0 010 2H4a1 1 0 01-1-1V3a1 1 0 011-1zm.008 9.057a1 1 0 011.276.61A5.002 5.002 0 0014.001 13H11a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0v-2.101a7.002 7.002 0 01-11.601-2.566 1 1 0 01.61-1.276z" clip-rule="evenodd" />
                                                </svg>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        @endif
                        
                        <!-- إضافة صور جديدة -->
                        <div class="mb-6 border-t pt-6 border-gray-200 dark:border-gray-700">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">
                                {{ __('إضافة صور جديدة') }} ({{ __('اختياري') }})
                            </h3>
                            
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
                        
                        <!-- إضافة فيديوهات جديدة -->
                        <div class="mb-6 border-t pt-6 border-gray-200 dark:border-gray-700">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">
                                {{ __('إضافة فيديوهات جديدة') }} ({{ __('اختياري') }})
                            </h3>
                            
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
                        
                        <!-- إضافة روابط جديدة -->
                        <div class="mb-6 border-t pt-6 border-gray-200 dark:border-gray-700">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">
                                {{ __('إضافة روابط جديدة') }} ({{ __('اختياري') }})
                            </h3>
                            
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
                                {{ __('حفظ التغييرات') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 