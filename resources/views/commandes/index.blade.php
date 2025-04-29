<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('المشاريع') }}
            </h2>
            <a href="{{ route('commandes.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                {{ __('إضافة مشروع جديد') }}
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-center">{{ __('إجمالي المشاريع') }}</h3>
                        <p class="text-3xl font-bold text-center mt-2">{{ $totalCommandes }}</p>
                    </div>
                </div>
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-center">{{ __('قيد الانتظار') }}</h3>
                        <p class="text-3xl font-bold text-center mt-2 text-yellow-500">{{ $pendingCommandes }}</p>
                    </div>
                </div>
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-center">{{ __('قيد التنفيذ') }}</h3>
                        <p class="text-3xl font-bold text-center mt-2 text-blue-500">{{ $inProgressCommandes }}</p>
                    </div>
                </div>
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-center">{{ __('مكتملة') }}</h3>
                        <p class="text-3xl font-bold text-center mt-2 text-green-500">{{ $completedCommandes }}</p>
                    </div>
                </div>
            </div>

            <!-- قائمة المشاريع -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    @if(session('success'))
                        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4" role="alert">
                            <p>{{ session('success') }}</p>
                        </div>
                    @endif
                    
                    <h2 class="text-xl font-semibold mb-6">{{ __('مشاريعي') }}</h2>
                    
                    @if($commandes->isEmpty())
                        <div class="text-center py-8 text-gray-500 dark:text-gray-400">
                            <p>{{ __('لا توجد مشاريع بعد. قم بإضافة مشروع جديد!') }}</p>
                        </div>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead class="bg-gray-50 dark:bg-gray-700">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            {{ __('عنوان المشروع') }}
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            {{ __('التخصص') }}
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            {{ __('الميزانية') }}
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            {{ __('الحالة') }}
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            {{ __('تاريخ الإنشاء') }}
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            {{ __('الإجراءات') }}
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                    @foreach($commandes as $commande)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-right">
                                                <div class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                                    {{ $commande->titre }}
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-right">
                                                <div class="text-sm text-gray-600 dark:text-gray-400">
                                                    {{ $commande->specialist }}
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-right">
                                                <div class="text-sm text-gray-600 dark:text-gray-400">
                                                    {{ $commande->budget }} SAR
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-right">
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
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
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-right">
                                                <div class="text-sm text-gray-600 dark:text-gray-400">
                                                    {{ $commande->created_at->format('Y-m-d') }}
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                                <div class="flex space-x-2 rtl:space-x-reverse justify-center">
                                                    <a href="{{ route('commandes.show', $commande) }}" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300">
                                                        {{ __('عرض') }}
                                                    </a>
                                                    <a href="{{ route('commandes.edit', $commande) }}" class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300">
                                                        {{ __('تعديل') }}
                                                    </a>
                                                    <form action="{{ route('commandes.destroy', $commande) }}" method="POST" class="inline-block" onsubmit="return confirm('هل أنت متأكد من حذف هذا المشروع؟');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300 mr-2">
                                                            {{ __('حذف') }}
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        <!-- التنقل بين الصفحات -->
                        <div class="mt-6">
                            {{ $commandes->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 