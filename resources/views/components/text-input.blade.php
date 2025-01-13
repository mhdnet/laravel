@props(['disabled' => false])

<input {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge(['class' => ' border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 disabled:bg-gray-100 rounded-md px-3 py-2 mt-2']) !!}>

