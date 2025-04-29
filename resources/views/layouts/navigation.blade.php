<div class="flex h-screen bg-gray-100 dark:bg-gray-900" dir="rtl">
    <!-- Sidebar Navigation -->
    <aside x-data="{ open: true }" class="relative bg-white dark:bg-gray-800 shadow-lg border-l border-gray-200 dark:border-gray-700">
        <!-- Sidebar toggle (mobile) -->
        <div class="absolute left-0 top-4 -ml-8 flex items-center lg:hidden">
            <button @click="open = !open" class="p-2 rounded-md text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 focus:outline-none">
                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path x-show="!open" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    <path x-show="open" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        <!-- Sidebar content -->
        <div :class="{'w-64': open, 'w-0 lg:w-64': !open}" class="h-full transition-all duration-300 ease-in-out overflow-y-auto">
            <!-- Logo -->
            <div class="flex items-center justify-center py-6">
                <a href="{{ route('dashboard') }}">
                    <x-application-logo class="block h-12 w-auto fill-current text-gray-800 dark:text-gray-200" />
                </a>
            </div>

            <!-- User info -->
            <div class="px-4 py-4 border-b border-gray-200 dark:border-gray-700">
                <div class="flex items-center space-x-4 space-x-reverse">
                    <div class="flex-shrink-0">
                        <div class="h-10 w-10 rounded-full bg-gray-300 dark:bg-gray-700 flex items-center justify-center">
                            <span class="text-lg font-medium text-gray-700 dark:text-gray-300">{{ substr(Auth::user()->name, 0, 1) }}</span>
                        </div>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-gray-900 dark:text-gray-100 truncate">
                            {{ Auth::user()->name }}
                        </p>
                        <p class="text-xs text-gray-500 dark:text-gray-400 truncate">
                            {{ Auth::user()->email }}
                        </p>
                    </div>
                </div>
            </div>
            
            <!-- Navigation Links -->
            <nav class="mt-4 px-2 space-y-1">
                <x-sidebar-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                    <svg class="me-3 flex-shrink-0 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                    </svg>
                    {{ __('لوحة التحكم') }}
                </x-sidebar-link>

                @if(auth()->user()->role !== 'craftsman')
                <x-sidebar-link :href="route('commandes.index')" :active="request()->routeIs('commandes.*')">
                    <svg class="me-3 flex-shrink-0 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                    </svg>
                    {{ __('المشاريع') }}
                </x-sidebar-link>
                @endif

                @if(auth()->user()->role === 'admin')
                <x-sidebar-link :href="route('admin.offres.index')" :active="request()->routeIs('admin.offres.*')">
                    <svg class="me-3 flex-shrink-0 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    {{ __('إدارة العروض') }}
                </x-sidebar-link>
                @endif

                @if(auth()->user()->role === 'craftsman')
                <x-sidebar-link :href="route('craftsman.offres.index')" :active="request()->routeIs('craftsman.offres.*')">
                    <svg class="me-3 flex-shrink-0 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    {{ __('عروضي') }}
                </x-sidebar-link>

                <x-sidebar-link :href="route('craftsman.portfolio.index')" :active="request()->routeIs('craftsman.portfolio.*')">
                    <svg class="me-3 flex-shrink-0 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    {{ __('ملف الأعمال') }}
                </x-sidebar-link>

                <x-sidebar-link :href="route('craftsman.ratings')" :active="request()->routeIs('craftsman.ratings')">
                    <svg class="me-3 flex-shrink-0 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
                    </svg>
                    {{ __('تقييماتي') }}
                </x-sidebar-link>
                @endif

                @if(auth()->user()->role === 'client')
                <x-sidebar-link :href="route('client.offres.index')" :active="request()->routeIs('client.offres.*')">
                    <svg class="me-3 flex-shrink-0 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                    </svg>
                    {{ __('العروض الواردة') }}
                </x-sidebar-link>
                @endif
            </nav>

            <!-- Profile Link -->
            <div class="mt-4 px-2">
                <x-sidebar-link :href="route('profile.edit')" :active="request()->routeIs('profile.edit')">
                    <svg class="me-3 flex-shrink-0 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                    {{ __('الملف الشخصي') }}
                </x-sidebar-link>
            </div>

            <!-- Logout -->
            <div class="mt-auto px-2 py-4 border-t border-gray-200 dark:border-gray-700">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" onclick="event.preventDefault(); this.closest('form').submit();" 
                        class="group flex items-center w-full px-2 py-2 text-sm font-medium rounded-md text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20">
                        <svg class="me-3 flex-shrink-0 h-5 w-5 text-red-500 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                        </svg>
                        {{ __('تسجيل الخروج') }}
                    </button>
                </form>
            </div>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="flex-1 overflow-y-auto focus:outline-none">
        <div class="py-6">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                {{ $slot ?? '' }}
            </div>
        </div>
    </main>
</div>
