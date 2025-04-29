<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('تفاصيل العمل') }}: {{ $work->title }}
            </h2>
            <div class="flex gap-2">
                <a href="{{ route('craftsman.portfolio.edit', $work) }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:outline-none focus:border-indigo-700 focus:ring focus:ring-indigo-200 active:bg-indigo-800 transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" viewBox="0 0 20 20" fill="currentColor">
                        <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                    </svg>
                    {{ __('تعديل') }}
                </a>
                <a href="{{ route('craftsman.portfolio.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                    {{ __('العودة للقائمة') }}
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                        <!-- العمود الأيمن: معلومات العمل -->
                        <div class="lg:col-span-1">
                            <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-6">
                                @if($work->is_featured)
                                <div class="bg-yellow-100 dark:bg-yellow-900 text-yellow-800 dark:text-yellow-200 px-4 py-2 rounded-md mb-4 inline-flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-2" viewBox="0 0 20 20" fill="currentColor">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                    </svg>
                                    {{ __('عمل مميز') }}
                                </div>
                                @endif
                                
                                <h3 class="text-xl font-bold mb-4">{{ $work->title }}</h3>
                                
                                <div class="mb-4">
                                    <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">{{ __('تاريخ الإنجاز') }}</h4>
                                    <p class="text-gray-900 dark:text-gray-100">{{ $work->completion_date->format('Y-m-d') }}</p>
                                </div>
                                
                                <div class="mb-4">
                                    <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">{{ __('تاريخ الإضافة') }}</h4>
                                    <p class="text-gray-900 dark:text-gray-100">{{ $work->created_at->format('Y-m-d') }}</p>
                                </div>
                                
                                <div>
                                    <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">{{ __('وصف العمل') }}</h4>
                                    <div class="text-gray-900 dark:text-gray-100 prose dark:prose-invert max-w-none">
                                        <p>{{ $work->description }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- العمود الأيسر: الصور والوسائط -->
                        <div class="lg:col-span-2">
                            @if(isset($work->media) && count($work->media) > 0)
                                <!-- معرض الصور -->
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    @foreach($work->media as $media)
                                        <div class="bg-gray-100 dark:bg-gray-700 rounded-lg overflow-hidden shadow">
                                            @if(isset($media['type']) && str_contains($media['type'], 'image'))
                                                <a href="{{ isset($media['path']) ? asset('storage/' . $media['path']) : $media['url'] }}" target="_blank" class="block">
                                                    <img src="{{ isset($media['path']) ? asset('storage/' . $media['path']) : $media['url'] }}" 
                                                         alt="{{ $media['name'] ?? $work->title }}" 
                                                         class="w-full h-64 object-cover">
                                                </a>
                                            @elseif(isset($media['type']) && str_contains($media['type'], 'video'))
                                                <div class="relative h-64">
                                                    <video class="w-full h-full object-cover" controls>
                                                        <source src="{{ isset($media['path']) ? asset('storage/' . $media['path']) : $media['url'] }}" type="{{ $media['type'] }}">
                                                        {{ __('متصفحك لا يدعم تشغيل الفيديو') }}
                                                    </video>
                                                </div>
                                            @elseif(isset($media['type']) && $media['type'] == 'url')
                                                <div class="h-64 flex items-center justify-center bg-gray-50 dark:bg-gray-800 p-4">
                                                    <a href="{{ $media['url'] }}" target="_blank" class="text-blue-600 dark:text-blue-400 hover:underline break-all">
                                                        {{ $media['url'] }}
                                                    </a>
                                                </div>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-8 text-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-gray-400 mx-auto mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    <p class="text-gray-600 dark:text-gray-400">{{ __('لا توجد وسائط مرفقة لهذا العمل') }}</p>
                                </div>
                            @endif
                        </div>
                    </div>
                    
                    <div class="flex justify-between mt-8 pt-6 border-t border-gray-200 dark:border-gray-700">
                        <form action="{{ route('craftsman.portfolio.destroy', $work) }}" method="POST" onsubmit="return confirm('{{ __('هل أنت متأكد من حذف هذا العمل؟') }}');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 active:bg-red-800 focus:outline-none focus:border-red-700 focus:ring focus:ring-red-200 transition">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                                </svg>
                                {{ __('حذف العمل') }}
                            </button>
                        </form>
                        
                        <div class="flex gap-2">
                            <a href="{{ route('craftsman.portfolio.edit', $work) }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:outline-none focus:border-indigo-700 focus:ring focus:ring-indigo-200 active:bg-indigo-800 transition">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" viewBox="0 0 20 20" fill="currentColor">
                                    <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                                </svg>
                                {{ __('تعديل') }}
                            </a>
                            
                            <a href="{{ route('craftsman.portfolio.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:outline-none focus:border-gray-700 focus:ring focus:ring-gray-200 active:bg-gray-800 transition">
                                {{ __('العودة للقائمة') }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 