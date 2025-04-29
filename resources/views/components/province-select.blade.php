<select 
    name="{{ $name }}" 
    id="{{ $id }}" 
    @if($required) required @endif
    {{ $attributes->merge(['class' => 'mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 dark:focus:border-blue-500 dark:focus:ring-blue-500 ' . $class]) }}
>
    <option value="">-- {{ __('اختر الولاية') }} --</option>
    @foreach($provinces() as $code => $province)
        <option value="{{ $code }}" @selected($selected == $code)>
            {{ $province }} ({{ $code }})
        </option>
    @endforeach
</select> 