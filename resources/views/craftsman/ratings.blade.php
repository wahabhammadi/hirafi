<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('تقييماتي') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- ملخص التقييمات -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 mb-6">
                <div class="flex flex-col md:flex-row items-center justify-between">
                    <div class="text-center md:text-right mb-4 md:mb-0">
                        <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-2">{{ __('ملخص التقييمات') }}</h3>
                        <p class="text-gray-600 dark:text-gray-400">{{ __('هذه هي التقييمات التي حصلت عليها من العملاء بعد إكمال المشاريع.') }}</p>
                    </div>
                    <div class="text-center">
                        <div class="flex items-center justify-center mb-2">
                            <span class="text-3xl font-bold text-gray-800 dark:text-gray-200 ml-2">{{ number_format($averageRating, 1) }}</span>
                            <div class="flex">
                                @for ($i = 1; $i <= 5; $i++)
                                    @if ($i <= round($averageRating))
                                        <svg class="w-6 h-6 text-yellow-500" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                        </svg>
                                    @else
                                        <svg class="w-6 h-6 text-gray-300 dark:text-gray-600" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                        </svg>
                                    @endif
                                @endfor
                            </div>
                        </div>
                        <p class="text-sm text-gray-600 dark:text-gray-400">{{ __('بناءً على') }} {{ $ratingsCount }} {{ __('تقييم') }}</p>
                    </div>
                </div>
            </div>

            <!-- قائمة التقييمات -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4">{{ __('جميع التقييمات') }}</h3>
                    
                    @if($ratedOffres->count() > 0)
                        <div class="space-y-6">
                            @foreach($ratedOffres as $offre)
                                <div class="border-b border-gray-200 dark:border-gray-700 pb-6 last:border-0 last:pb-0">
                                    <div class="flex flex-col md:flex-row md:justify-between md:items-start">
                                        <div class="mb-4 md:mb-0">
                                            <div class="flex items-center mb-2">
                                                <div class="flex">
                                                    @for ($i = 1; $i <= 5; $i++)
                                                        @if ($i <= $offre->rating)
                                                            <svg class="w-5 h-5 text-yellow-500" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                                            </svg>
                                                        @else
                                                            <svg class="w-5 h-5 text-gray-300 dark:text-gray-600" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                                            </svg>
                                                        @endif
                                                    @endfor
                                                </div>
                                                <span class="text-sm text-gray-600 dark:text-gray-400 mr-2">{{ $offre->rating }}/5</span>
                                            </div>
                                            <h4 class="font-semibold text-gray-800 dark:text-gray-200 mb-2">{{ $offre->commande->titre }}</h4>
                                            @if($offre->review)
                                                <p class="text-gray-700 dark:text-gray-300 mb-2 whitespace-pre-line">{{ $offre->review }}</p>
                                            @else
                                                <p class="text-gray-500 dark:text-gray-400 italic">{{ __('لم يترك العميل تعليقًا.') }}</p>
                                            @endif
                                        </div>
                                        <div class="text-sm text-gray-500 dark:text-gray-400 md:text-left">
                                            <p>{{ __('رقم المشروع') }}: {{ $offre->commande_id }}</p>
                                            <p>{{ __('تاريخ التقييم') }}: {{ $offre->updated_at->format('Y-m-d') }}</p>
                                            <p>{{ __('سعر العرض') }}: {{ $offre->price }} {{ __('ريال') }}</p>
                                            <a href="{{ route('craftsman.offres.show', $offre) }}" class="text-blue-600 dark:text-blue-400 hover:underline mt-2 inline-block">
                                                {{ __('عرض تفاصيل العرض') }}
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        
                        <div class="mt-6">
                            {{ $ratedOffres->links() }}
                        </div>
                    @else
                        <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-6 text-center">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <h3 class="mt-2 text-sm font-semibold text-gray-700 dark:text-gray-300">{{ __('لا توجد تقييمات بعد') }}</h3>
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{ __('عندما يقوم العملاء بتقييم عملك بعد إكمال المشاريع، ستظهر التقييمات هنا.') }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 