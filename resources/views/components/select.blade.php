@props(['id', 'name', 'class' => ''])

<select id="{{ $id }}" name="{{ $name }}" class="{{ $class }}">
    {{ $slot }}
</select>