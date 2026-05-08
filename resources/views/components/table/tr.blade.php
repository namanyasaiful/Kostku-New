<tr {{ $attributes->merge([
    'class' => 'border-b border-default bg-white text-neutral'
]) }}>
    {{ $slot }}
</tr>