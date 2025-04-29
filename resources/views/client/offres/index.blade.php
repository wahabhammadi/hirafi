<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('العروض الواردة') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- رسائل النجاح -->
            @if(session('status'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4" role="alert">
                    <p>{{ session('status') }}</p>
                </div>
            @endif
            
            <!-- إحصائيات العروض -->
            <div class="grid grid-cols-1 md:grid-cols-5 gap-4 mb-6">
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-2">{{ __('إجمالي العروض') }}</h3>
                    <p class="text-3xl font-bold text-blue-600 dark:text-blue-400">{{ $totalCount }}</p>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-2">{{ __('قيد الانتظار') }}</h3>
                    <p class="text-3xl font-bold text-yellow-600 dark:text-yellow-400">{{ $pendingCount }}</p>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-2">{{ __('مقبولة') }}</h3>
                    <p class="text-3xl font-bold text-green-600 dark:text-green-400">{{ $acceptedCount }}</p>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-2">{{ __('مرفوضة') }}</h3>
                    <p class="text-3xl font-bold text-red-600 dark:text-red-400">{{ $rejectedCount }}</p>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-2">{{ __('مكتملة') }}</h3>
                    <p class="text-3xl font-bold text-purple-600 dark:text-purple-400">{{ $completedCount }}</p>
                </div>
            </div>

            <!-- فلاتر البحث -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow mb-6">
                <div class="p-4">
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4">{{ __('بحث وتصفية') }}</h3>
                    <form action="{{ route('client.offres.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <x-input-label for="status" :value="__('الحالة')" />
                            <select id="status" name="status" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 shadow-sm">
                                <option value="all" {{ request('status') == 'all' ? 'selected' : '' }}>{{ __('جميع الحالات') }}</option>
                                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>{{ __('قيد الانتظار') }}</option>
                                <option value="accepted" {{ request('status') == 'accepted' ? 'selected' : '' }}>{{ __('مقبولة') }}</option>
                                <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>{{ __('مرفوضة') }}</option>
                                <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>{{ __('مكتملة') }}</option>
                            </select>
                        </div>
                        <div>
                            <x-input-label for="commande_id" :value="__('المشروع')" />
                            <select id="commande_id" name="commande_id" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 shadow-sm">
                                <option value="">{{ __('جميع المشاريع') }}</option>
                                @foreach($commandes as $commande)
                                    <option value="{{ $commande->id }}" {{ request('commande_id') == $commande->id ? 'selected' : '' }}>
                                        {{ $commande->titre }} ({{ $commande->id }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="md:col-span-1 flex items-end">
                            <x-primary-button class="h-10">
                                {{ __('بحث') }}
                            </x-primary-button>
                            @if(request()->anyFilled(['status', 'commande_id']))
                                <a href="{{ route('client.offres.index') }}" class="mr-2 inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                    {{ __('إعادة ضبط') }}
                                </a>
                            @endif
                        </div>
                    </form>
                </div>
            </div>

            <!-- جدول العروض -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow">
                <div class="p-4">
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4">{{ __('العروض الواردة لمشاريعك') }}</h3>
                    
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-800">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        {{ __('رقم العرض') }}
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        {{ __('المشروع') }}
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        {{ __('الحرفي') }}
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        {{ __('السعر المقترح') }}
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        {{ __('تاريخ التسليم') }}
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        {{ __('تاريخ التقديم') }}
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        {{ __('الحالة') }}
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        {{ __('خيارات') }}
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-900 divide-y divide-gray-200 dark:divide-gray-700">
                                @forelse($offres as $offre)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $offre->id }}</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm text-gray-900 dark:text-gray-100">{{ $offre->commande->titre }}</div>
                                        <div class="text-xs text-gray-500 dark:text-gray-400">{{ __('رقم المشروع') }}: {{ $offre->commande_id }}</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm text-gray-900 dark:text-gray-100">{{ $offre->user->name }}</div>
                                        @if(isset($offre->user->craftsman) && $offre->user->craftsman->rating)
                                            <div class="mt-1">
                                                <x-star-rating :rating="$offre->user->craftsman->rating" size="small" />
                                            </div>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $offre->price }} دج</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900 dark:text-gray-100">{{ $offre->delivery_date->format('Y-m-d') }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900 dark:text-gray-100">{{ $offre->created_at->format('Y-m-d') }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                            @if($offre->status == 'pending') bg-yellow-100 text-yellow-800 @endif
                                            @if($offre->status == 'accepted') bg-green-100 text-green-800 @endif
                                            @if($offre->status == 'rejected') bg-red-100 text-red-800 @endif
                                            @if($offre->status == 'completed') bg-purple-100 text-purple-800 @endif
                                        ">
                                            @if($offre->status == 'pending')
                                                {{ __('قيد الانتظار') }}
                                            @elseif($offre->status == 'accepted')
                                                {{ __('مقبول') }}
                                            @elseif($offre->status == 'rejected')
                                                {{ __('مرفوض') }}
                                            @elseif($offre->status == 'completed')
                                                {{ __('مكتمل') }}
                                            @endif
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <div class="flex space-x-2 rtl:space-x-reverse justify-end">
                                            <a href="{{ route('client.offres.show', $offre) }}" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300">
                                                {{ __('عرض التفاصيل') }}
                                            </a>
                                            
                                            @if($offre->status != 'accepted')
                                                <form action="{{ route('client.offres.destroy', $offre) }}" method="POST" onsubmit="return confirm('{{ __('هل أنت متأكد من حذف هذا العرض؟') }}');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300">
                                                        {{ __('حذف') }}
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                                        {{ __('لم يتم تقديم أي عروض لمشاريعك حتى الآن.') }}
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="mt-4">
                        {{ $offres->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 