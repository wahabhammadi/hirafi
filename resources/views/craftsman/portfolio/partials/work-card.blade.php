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
                <div class="relative w-full h-full">
                    <img src="{{ asset('images/video-placeholder.jpg') }}" class="w-full h-full object-cover">
                    <div class="absolute inset-0 flex items-center justify-center bg-black bg-opacity-50">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
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
        <h3 class="text-lg font-semibold mb-2 text-gray-900 dark:text-gray-100">{{ $work->title }}</h3>
        <p class="text-sm text-gray-600 dark:text-gray-300 mb-4 line-clamp-2">{{ $work->description }}</p>
        
        <div class="flex items-center text-sm text-gray-500 dark:text-gray-400 mb-4">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
            </svg>
            <span>{{ __('تاريخ الإنجاز') }}: {{ $work->completion_date->format('Y-m-d') }}</span>
        </div>
        
        <div class="flex justify-end">
            <button type="button" class="text-blue-600 dark:text-blue-400 hover:underline" onclick="showWorkDetails('{{ $work->id }}', '{{ $work->title }}', '{{ $work->description }}', '{{ $work->completion_date->format('Y-m-d') }}')">
                {{ __('عرض التفاصيل') }}
            </button>
        </div>
    </div>
</div>

@once
<div id="workDetailsModal" class="fixed inset-0 z-50 flex items-center justify-center hidden">
    <div class="absolute inset-0 bg-black bg-opacity-50" onclick="closeWorkDetails()"></div>
    <div class="relative bg-white dark:bg-gray-800 p-6 rounded-lg shadow-lg max-w-3xl w-full max-h-[90vh] overflow-y-auto">
        <button type="button" class="absolute top-3 left-3 text-gray-400 hover:text-gray-500" onclick="closeWorkDetails()">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
        
        <h2 id="modalTitle" class="text-xl font-bold text-gray-900 dark:text-gray-100 mb-4"></h2>
        
        <div class="mb-4">
            <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">{{ __('تاريخ الإنجاز') }}</h4>
            <p id="modalDate" class="text-gray-900 dark:text-gray-100"></p>
        </div>
        
        <div class="mb-4">
            <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">{{ __('وصف العمل') }}</h4>
            <div id="modalDescription" class="text-gray-900 dark:text-gray-100 prose dark:prose-invert max-w-none"></div>
        </div>
        
        <div id="modalMedia" class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-6">
            <!-- Media will be inserted here via JavaScript -->
        </div>
    </div>
</div>

<script>
    function showWorkDetails(workId, title, description, date) {
        // Set modal content
        document.getElementById('modalTitle').textContent = title;
        document.getElementById('modalDate').textContent = date;
        document.getElementById('modalDescription').textContent = description;
        
        // Load media for this work
        const mediaContainer = document.getElementById('modalMedia');
        mediaContainer.innerHTML = ''; // Clear previous media
        
        // Show modal
        document.getElementById('workDetailsModal').classList.remove('hidden');
        document.body.classList.add('overflow-hidden');
        
        // You'd typically fetch media from server here, but for simplicity we'll just display a placeholder
        // In a real implementation, you would make an AJAX call to fetch the work's media
        
        // Prevent scrolling on the background
        document.body.style.overflow = 'hidden';
    }
    
    function closeWorkDetails() {
        document.getElementById('workDetailsModal').classList.add('hidden');
        document.body.classList.remove('overflow-hidden');
        document.body.style.overflow = '';
    }
</script>
@endonce 