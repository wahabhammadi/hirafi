@props(['active'])

@php
$classes = ($active ?? false)
            ? 'flex items-center px-2 py-2 text-sm font-medium rounded-md bg-indigo-50 dark:bg-indigo-900/50 text-indigo-700 dark:text-indigo-300 border-r-4 border-indigo-500 dark:border-indigo-600'
            : 'flex items-center px-2 py-2 text-sm font-medium rounded-md text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 hover:bg-gray-50 dark:hover:bg-gray-700 hover:border-r-4 hover:border-gray-300 dark:hover:border-gray-500';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a> 