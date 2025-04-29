<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('تفاصيل العرض') }} #{{ $offre->id }}
            </h2>
            <a href="{{ route('craftsman.offres.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                {{ __('العودة إلى قائمة العروض') }}
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">{{ __('معلومات العرض') }}</h3>
                        
                        <!-- حالة العرض -->
                        <div>
                            <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full 
                                @if($offre->status == 'pending') bg-yellow-100 text-yellow-800 @endif
                                @if($offre->status == 'accepted') bg-green-100 text-green-800 @endif
                                @if($offre->status == 'rejected') bg-red-100 text-red-800 @endif
                            ">
                                @if($offre->status == 'pending')
                                    {{ __('قيد الانتظار') }}
                                @elseif($offre->status == 'accepted')
                                    {{ __('مقبول') }}
                                @elseif($offre->status == 'rejected')
                                    {{ __('مرفوض') }}
                                @endif
                            </span>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                                <h4 class="text-md font-semibold text-gray-700 dark:text-gray-300 mb-4">{{ __('معلومات أساسية') }}</h4>
                                
                                <div class="mb-3">
                                    <span class="block text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('تاريخ تقديم العرض') }}</span>
                                    <span class="block mt-1 text-gray-800 dark:text-gray-200">{{ $offre->created_at->format('Y-m-d H:i') }}</span>
                                </div>
                                
                                <div class="mb-3">
                                    <span class="block text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('المشروع') }}</span>
                                    <span class="block mt-1 text-gray-800 dark:text-gray-200">
                                        <a href="{{ route('commandes.show', $offre->commande_id) }}" class="text-blue-600 hover:underline dark:text-blue-400">
                                            {{ $offre->commande->titre }}
                                        </a>
                                    </span>
                                </div>
                                
                                <div class="mb-3">
                                    <span class="block text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('السعر المقترح') }}</span>
                                    <span class="block mt-1 text-gray-800 dark:text-gray-200">{{ $offre->price }} دج</span>
                                </div>
                                
                                <div class="mb-3">
                                    <span class="block text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('تاريخ التسليم المتوقع') }}</span>
                                    <span class="block mt-1 text-gray-800 dark:text-gray-200">{{ $offre->delivery_date->format('Y-m-d') }}</span>
                                </div>
                            </div>
                        </div>
                        
                        <div>
                            <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                                <h4 class="text-md font-semibold text-gray-700 dark:text-gray-300 mb-4">{{ __('وصف طريقة العمل') }}</h4>
                                <div class="text-gray-800 dark:text-gray-200 whitespace-pre-line">{{ $offre->description }}</div>
                            </div>
                        </div>
                    </div>
                    
                    @if(isset($offre->media) && count($offre->media) > 0)
                    <div class="mt-6">
                        <h4 class="text-md font-semibold text-gray-700 dark:text-gray-300 mb-4">{{ __('الوسائط المرفقة') }}</h4>
                        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                            @foreach($offre->media as $media)
                                <div class="rounded-lg overflow-hidden shadow">
                                    @if(isset($media['type']) && str_contains($media['type'], 'image'))
                                        <a href="{{ isset($media['path']) ? asset('storage/' . $media['path']) : $media['url'] }}" target="_blank">
                                            <img src="{{ isset($media['path']) ? asset('storage/' . $media['path']) : $media['url'] }}" 
                                                alt="{{ $media['name'] ?? 'صورة العرض' }}" 
                                                class="h-40 w-full object-cover">
                                        </a>
                                    @elseif(isset($media['type']) && str_contains($media['type'], 'video'))
                                        <video class="h-40 w-full object-cover" controls>
                                            <source src="{{ isset($media['path']) ? asset('storage/' . $media['path']) : $media['url'] }}" type="{{ $media['type'] }}">
                                            {{ __('متصفحك لا يدعم عرض الفيديو') }}
                                        </video>
                                    @elseif(isset($media['type']) && $media['type'] == 'url')
                                        <div class="h-40 p-4 flex items-center justify-center bg-gray-100 dark:bg-gray-800">
                                            <a href="{{ $media['url'] }}" target="_blank" class="text-blue-600 dark:text-blue-400 hover:underline break-all">
                                                {{ $media['url'] }}
                                            </a>
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    <!-- خيارات -->
                    @if($offre->status == 'pending')
                    <div class="mt-6 flex justify-end">
                        <a href="{{ route('offres.edit', $offre) }}" class="inline-flex items-center px-4 py-2 bg-yellow-600 border border-transparent rounded-md font-semibold text-xs text-white tracking-widest hover:bg-yellow-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500 transition ease-in-out duration-150 ml-3">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1 -mt-1" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                            </svg>
                            {{ __('تعديل العرض') }}
                        </a>
                        
                        <form action="{{ route('offres.destroy', $offre) }}" method="POST" class="inline-block">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white tracking-widest hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition ease-in-out duration-150" onclick="return confirm('{{ __('هل أنت متأكد من حذف هذا العرض؟') }}');">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1 -mt-1" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                                </svg>
                                {{ __('حذف العرض') }}
                            </button>
                        </form>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 