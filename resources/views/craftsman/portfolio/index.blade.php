<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('ملف الأعمال') }}
            </h2>
            <a href="{{ route('craftsman.portfolio.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:outline-none focus:border-blue-700 focus:ring focus:ring-blue-200 active:bg-blue-700 transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                </svg>
                {{ __('إضافة عمل جديد') }}
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('status'))
                <div class="mb-6 bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded" role="alert">
                    <p>{{ session('status') }}</p>
                </div>
            @endif

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    @if(count($works) > 0)
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            @foreach($works as $work)
                                <div class="bg-white dark:bg-gray-700 rounded-lg shadow overflow-hidden">
                                    <div class="relative h-48 bg-gray-200 dark:bg-gray-600">
                                        @if(isset($work->media) && count($work->media) > 0)
                                            @php
                                                $firstMedia = collect($work->media)->first();
                                            @endphp
                                            
                                            @if(isset($firstMedia['type']) && str_contains($firstMedia['type'], 'image'))
                                                <img src="{{ isset($firstMedia['path']) ? asset('storage/' . $firstMedia['path']) : $firstMedia['url'] }}" 
                                                     alt="{{ $work->title }}" 
                                                     class="w-full h-full object-cover">
                                            @elseif(isset($firstMedia['type']) && str_contains($firstMedia['type'], 'video'))
                                                <video class="w-full h-full object-cover" controls>
                                                    <source src="{{ isset($firstMedia['path']) ? asset('storage/' . $firstMedia['path']) : $firstMedia['url'] }}" type="{{ $firstMedia['type'] }}">
                                                </video>
                                            @elseif(isset($firstMedia['type']) && $firstMedia['type'] == 'url')
                                                <div class="w-full h-full flex items-center justify-center bg-gray-100 dark:bg-gray-700 p-4">
                                                    <a href="{{ $firstMedia['url'] }}" target="_blank" class="text-blue-600 dark:text-blue-400 hover:underline truncate">
                                                        {{ $firstMedia['url'] }}
                                                    </a>
                                                </div>
                                            @else
                                                <div class="w-full h-full flex items-center justify-center bg-gray-100 dark:bg-gray-700">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                    </svg>
                                                </div>
                                            @endif
                                        @else
                                            <div class="w-full h-full flex items-center justify-center bg-gray-100 dark:bg-gray-700">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                </svg>
                                            </div>
                                        @endif

                                        @if($work->is_featured)
                                            <div class="absolute top-2 right-2 bg-yellow-400 text-yellow-800 text-xs font-medium px-2.5 py-0.5 rounded">
                                                {{ __('مميز') }}
                                            </div>
                                        @endif
                                    </div>
                                    
                                    <div class="p-4">
                                        <h3 class="text-lg font-semibold mb-2">{{ $work->title }}</h3>
                                        <p class="text-sm text-gray-600 dark:text-gray-300 mb-4 line-clamp-2">{{ $work->description }}</p>
                                        
                                        <div class="flex items-center text-sm text-gray-500 dark:text-gray-400 mb-4">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                            <span>{{ __('تاريخ الإنجاز') }}: {{ $work->completion_date->format('Y-m-d') }}</span>
                                        </div>
                                        
                                        <div class="flex justify-between items-center">
                                            <a href="{{ route('craftsman.portfolio.show', $work) }}" class="text-blue-600 dark:text-blue-400 hover:underline">
                                                {{ __('عرض التفاصيل') }}
                                            </a>
                                            
                                            <div class="flex space-x-2 rtl:space-x-reverse">
                                                <a href="{{ route('craftsman.portfolio.edit', $work) }}" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                        <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                                                    </svg>
                                                </a>
                                                
                                                <form action="{{ route('craftsman.portfolio.destroy', $work) }}" method="POST" onsubmit="return confirm('{{ __('هل أنت متأكد من حذف هذا العمل؟') }}');" class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                            <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                                                        </svg>
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-gray-400 mx-auto mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-2">{{ __('ليس لديك أي أعمال بعد') }}</h3>
                            <p class="text-gray-600 dark:text-gray-400 mb-6">{{ __('أضف أعمالك لعرضها للعملاء وزيادة فرصك في الحصول على مشاريع') }}</p>
                            <a href="{{ route('craftsman.portfolio.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:outline-none focus:border-blue-700 focus:ring focus:ring-blue-200 active:bg-blue-700 transition">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                                </svg>
                                {{ __('إضافة عمل جديد') }}
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 