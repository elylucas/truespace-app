@props(['disabled' => false])

<input {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge(['class' => 'border-gray-300 focus:border-ts-teal focus:ring-ts-teal rounded-md shadow-sm']) !!}>
