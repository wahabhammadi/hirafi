<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('تفاصيل العرض') }} #{{ $offre->id }}
            </h2>
            <a href="{{ route('admin.offres.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
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
                        
                        <!-- تحديث حالة العرض -->
                        <div class="flex items-center">
                            <span class="ml-2 text-sm text-gray-600 dark:text-gray-400">{{ __('تحديث الحالة:') }}</span>
                            <form action="{{ route('admin.offres.update-status', $offre) }}" method="POST" class="inline-block mr-2">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="status" value="pending">
                                <button type="submit" class="px-3 py-1 bg-yellow-500 text-white rounded-md text-xs hover:bg-yellow-600 @if($offre->status == 'pending') opacity-50 cursor-not-allowed @endif" @if($offre->status == 'pending') disabled @endif>
                                    {{ __('قيد الانتظار') }}
                                </button>
                            </form>
                            
                            <form action="{{ route('admin.offres.update-status', $offre) }}" method="POST" class="inline-block mr-2">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="status" value="accepted">
                                <button type="submit" class="px-3 py-1 bg-green-500 text-white rounded-md text-xs hover:bg-green-600 @if($offre->status == 'accepted') opacity-50 cursor-not-allowed @endif" @if($offre->status == 'accepted') disabled @endif>
                                    {{ __('قبول') }}
                                </button>
                            </form>
                            
                            <form action="{{ route('admin.offres.update-status', $offre) }}" method="POST" class="inline-block">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="status" value="rejected">
                                <button type="submit" class="px-3 py-1 bg-red-500 text-white rounded-md text-xs hover:bg-red-600 @if($offre->status == 'rejected') opacity-50 cursor-not-allowed @endif" @if($offre->status == 'rejected') disabled @endif>
                                    {{ __('رفض') }}
                                </button>
                            </form>
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
                                    <span class="block text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('رقم المشروع') }}</span>
                                    <span class="block mt-1 text-gray-800 dark:text-gray-200">
                                        <a href="{{ route('commandes.show', $offre->commande_id) }}" class="text-blue-600 hover:underline dark:text-blue-400">
                                            #{{ $offre->commande_id }} - {{ $offre->commande->titre }}
                                        </a>
                                    </span>
                                </div>
                                
                                <div class="mb-3">
                                    <span class="block text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('الحرفي') }}</span>
                                    <span class="block mt-1 text-gray-800 dark:text-gray-200">{{ $offre->user->name }}</span>
                                </div>
                                
                                <div class="mb-3">
                                    <span class="block text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('السعر المقترح') }}</span>
                                    <span class="block mt-1 text-gray-800 dark:text-gray-200">{{ $offre->price }} دج</span>
                                </div>
                                
                                <div class="mb-3">
                                    <span class="block text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('تاريخ التسليم المتوقع') }}</span>
                                    <span class="block mt-1 text-gray-800 dark:text-gray-200">{{ $offre->delivery_date->format('Y-m-d') }}</span>
                                </div>
                                
                                <div>
                                    <span class="block text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('حالة العرض') }}</span>
                                    <span class="mt-1 px-2 inline-flex text-xs leading-5 font-semibold rounded-full
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
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 