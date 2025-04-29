<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('ملف أعمال') }} {{ $user->name }}
            </h2>
            <a href="{{ url()->previous() }}" class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                {{ __('العودة') }}
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- معلومات الحرفي -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <div class="flex flex-col md:flex-row md:items-center">
                        <div class="flex-shrink-0 mb-4 md:mb-0 md:ml-6">
                            <img src="{{ $user->avatar ? asset('storage/' . $user->avatar) : asset('images/default-avatar.png') }}" alt="{{ $user->name }}" class="h-32 w-32 rounded-full object-cover shadow-md">
                        </div>
                        
                        <div class="flex-grow">
                            <h2 class="text-2xl font-bold text-gray-900 dark:text-gray-100 mb-2">{{ $user->name }}</h2>
                            
                            @if(isset($craftsman))
                            <div class="mb-3">
                                <span class="inline-flex items-center px-3 py-1 bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200 rounded-full text-sm font-medium">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M11.3 1.046A1 1 0 0112 2v5h4a1 1 0 01.82 1.573l-7 10A1 1 0 018 18v-5H4a1 1 0 01-.82-1.573l7-10a1 1 0 011.12-.38z" clip-rule="evenodd" />
                                    </svg>
                                    {{ $craftsman->craft ?? __('حرفي') }}
                                </span>
                            </div>
                            @endif
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">{{ __('معلومات التواصل') }}</h3>
                                    
                                    <div class="flex items-center mb-1">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-500 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                        </svg>
                                        <span class="text-gray-900 dark:text-gray-100">{{ $user->email }}</span>
                                    </div>
                                    
                                    <div class="flex items-center mb-1">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-500 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                        </svg>
                                        <span class="text-gray-900 dark:text-gray-100">{{ $user->phone ?: __('غير متوفر') }}</span>
                                    </div>
                                    
                                    @if(isset($craftsman) && $craftsman->phone_secondary)
                                    <div class="flex items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-500 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                        </svg>
                                        <span class="text-gray-900 dark:text-gray-100">{{ $craftsman->phone_secondary }} ({{ __('ثانوي') }})</span>
                                    </div>
                                    @endif
                                </div>
                                
                                @if(isset($craftsman))
                                <div>
                                    <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">{{ __('التقييم') }}</h3>
                                    
                                    <div class="flex items-center">
                                        @if(isset($craftsman->rating))
                                            <x-star-rating :rating="$craftsman->rating" size="md" />
                                            <span class="mr-2 text-gray-900 dark:text-gray-100 text-sm font-medium">{{ number_format($craftsman->rating, 1) }}/5</span>
                                        @else
                                            <span class="text-gray-600 dark:text-gray-400">{{ __('لا يوجد تقييم بعد') }}</span>
                                        @endif
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- الأعمال المميزة -->
            @php
                $featuredWorks = $works->where('is_featured', true);
            @endphp
            
            @if(count($featuredWorks) > 0)
            <div class="mb-8">
                <h3 class="text-xl font-bold text-gray-900 dark:text-gray-100 mb-4 flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-1 text-yellow-500" viewBox="0 0 20 20" fill="currentColor">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                    </svg>
                    {{ __('الأعمال المميزة') }}
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($featuredWorks as $work)
                        @include('craftsman.portfolio.partials.work-card', ['work' => $work])
                    @endforeach
                </div>
            </div>
            @endif

            <!-- جميع الأعمال -->
            <div>
                <h3 class="text-xl font-bold text-gray-900 dark:text-gray-100 mb-4">
                    {{ __('جميع الأعمال') }} ({{ count($works) }})
                </h3>
                
                @if(count($works) > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($works as $work)
                            @include('craftsman.portfolio.partials.work-card', ['work' => $work])
                        @endforeach
                    </div>
                @else
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-8 text-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-gray-400 mx-auto mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        <p class="text-gray-600 dark:text-gray-400">{{ __('لا توجد أعمال لعرضها حتى الآن') }}</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout> 