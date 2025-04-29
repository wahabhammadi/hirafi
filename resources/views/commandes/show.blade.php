<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('تفاصيل المشروع') }}
            </h2>
            <div class="flex space-x-2 rtl:space-x-reverse">
                <a href="{{ route('commandes.edit', $commande) }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    {{ __('تعديل المشروع') }}
                </a>
                <a href="{{ route('commandes.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    {{ __('العودة إلى المشاريع') }}
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4" role="alert">
                    <p>{{ session('success') }}</p>
                </div>
            @endif

            <!-- بطاقة تفاصيل المشروع -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <div class="mb-6">
                        <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-200 mb-2">{{ $commande->titre }}</h1>
                        <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full 
                            @if($commande->statue == 'pending') bg-yellow-100 text-yellow-800 @endif
                            @if($commande->statue == 'in_progress') bg-blue-100 text-blue-800 @endif
                            @if($commande->statue == 'completed') bg-green-100 text-green-800 @endif
                            @if($commande->statue == 'rejected') bg-red-100 text-red-800 @endif
                        ">
                            @if($commande->statue == 'pending')
                                {{ __('قيد الانتظار') }}
                            @elseif($commande->statue == 'in_progress')
                                {{ __('قيد التنفيذ') }}
                            @elseif($commande->statue == 'completed')
                                {{ __('مكتمل') }}
                            @elseif($commande->statue == 'rejected')
                                {{ __('مرفوض') }}
                            @endif
                        </span>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-300 mb-2">{{ __('معلومات المشروع') }}</h3>
                            <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                                <div class="mb-4">
                                    <span class="block text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('التخصص المطلوب') }}</span>
                                    <span class="block mt-1 text-gray-800 dark:text-gray-200">{{ $commande->specialist }}</span>
                                </div>
                                <div class="mb-4">
                                    <span class="block text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('الميزانية') }}</span>
                                    <span class="block mt-1 text-gray-800 dark:text-gray-200">{{ $commande->budget }} SAR</span>
                                </div>
                                <div class="mb-4">
                                    <span class="block text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('الولاية') }}</span>
                                    <span class="block mt-1 text-gray-800 dark:text-gray-200">{{ $commande->province_name }}</span>
                                </div>
                                <div>
                                    <span class="block text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('تاريخ الإنشاء') }}</span>
                                    <span class="block mt-1 text-gray-800 dark:text-gray-200">{{ $commande->created_at->format('Y-m-d H:i') }}</span>
                                </div>
                            </div>
                        </div>

                        <div>
                            <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-300 mb-2">{{ __('وصف المشروع') }}</h3>
                            <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                                <p class="text-gray-800 dark:text-gray-200 whitespace-pre-line">{{ $commande->description }}</p>
                            </div>
                        </div>
                    </div>

                    @if($commande->media && count($commande->media) > 0)
                    <div class="mt-8">
                        <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-300 mb-4">{{ __('الوسائط والمرفقات') }}</h3>
                        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                            @foreach($commande->media as $media)
                                @if(isset($media['type']) && str_contains($media['type'], 'image'))
                                    <div class="relative group">
                                        <img src="{{ isset($media['path']) ? asset('storage/' . $media['path']) : $media['url'] }}" 
                                            alt="{{ $media['name'] ?? 'صورة المشروع' }}" 
                                            class="h-40 w-full object-cover rounded-lg shadow-sm">
                                        <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-50 transition-opacity duration-300 rounded-lg flex items-center justify-center">
                                            <a href="{{ isset($media['path']) ? asset('storage/' . $media['path']) : $media['url'] }}" 
                                               target="_blank" 
                                               class="text-white opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                </svg>
                                            </a>
                                        </div>
                                    </div>
                                @elseif(isset($media['type']) && str_contains($media['type'], 'video'))
                                    <div class="relative group">
                                        <video class="h-40 w-full object-cover rounded-lg shadow-sm" controls>
                                            <source src="{{ isset($media['path']) ? asset('storage/' . $media['path']) : $media['url'] }}" type="{{ $media['type'] }}">
                                            {{ __('متصفحك لا يدعم عرض الفيديو') }}
                                        </video>
                                    </div>
                                @elseif(isset($media['type']) && $media['type'] == 'url')
                                    <div class="p-4 border border-gray-200 dark:border-gray-700 rounded-lg shadow-sm">
                                        <a href="{{ $media['url'] }}" target="_blank" class="text-blue-600 dark:text-blue-400 hover:underline break-all">
                                            {{ $media['url'] }}
                                        </a>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                    @endif

                    <div class="mt-8 flex justify-between">
                        @if(auth()->user()->role === 'client' && auth()->id() === $commande->user_id)
                        <form action="{{ route('commandes.destroy', $commande) }}" method="POST" class="inline-block" onsubmit="return confirm('هل أنت متأكد من حذف هذا المشروع؟');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white tracking-widest hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition ease-in-out duration-150">
                                {{ __('حذف المشروع') }}
                            </button>
                        </form>
                        @elseif(auth()->user()->role === 'craftsman')
                        <div>
                            @php
                            $existingOffer = \App\Models\Offre::where('user_id', auth()->id())
                                ->where('commande_id', $commande->id)
                                ->first();
                            @endphp
                            
                            @if($existingOffer)
                                <a href="{{ route('offres.edit', $existingOffer) }}" class="inline-flex items-center px-4 py-2 bg-yellow-600 border border-transparent rounded-md font-semibold text-xs text-white tracking-widest hover:bg-yellow-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500 transition ease-in-out duration-150">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1 -mt-1" viewBox="0 0 20 20" fill="currentColor">
                                        <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                                    </svg>
                                    {{ __('تعديل العرض') }}
                                </a>
                                
                                @if($existingOffer->status == 'pending')
                                <form action="{{ route('offres.destroy', $existingOffer) }}" method="POST" class="inline-block mr-2" onsubmit="return confirm('هل أنت متأكد من حذف هذا العرض؟');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white tracking-widest hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition ease-in-out duration-150">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1 -mt-1" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                                        </svg>
                                        {{ __('حذف العرض') }}
                                    </button>
                                </form>
                                @endif
                                
                                <span class="mr-2 px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full 
                                    @if($existingOffer->status == 'pending') bg-yellow-100 text-yellow-800 @endif
                                    @if($existingOffer->status == 'accepted') bg-green-100 text-green-800 @endif
                                    @if($existingOffer->status == 'rejected') bg-red-100 text-red-800 @endif
                                ">
                                    @if($existingOffer->status == 'pending')
                                        {{ __('قيد الانتظار') }}
                                    @elseif($existingOffer->status == 'accepted')
                                        {{ __('مقبول') }}
                                    @elseif($existingOffer->status == 'rejected')
                                        {{ __('مرفوض') }}
                                    @endif
                                </span>
                            @else
                                <a href="{{ route('offres.create', $commande) }}" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white tracking-widest hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition ease-in-out duration-150">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1 -mt-1" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                                    </svg>
                                    {{ __('تقديم عرض') }}
                                </a>
                            @endif
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 