@props([
'show' => false,
'maxWidth' => 'md',
])

@php
$maxWidthClass = match($maxWidth) {
'sm' => 'max-w-sm',
'md' => 'max-w-md',
'lg' => 'max-w-lg',
'xl' => 'max-w-xl',
default => $maxWidth,
};
@endphp

<div
    x-show="{{ $show }}"
    x-transition.opacity
    class="fixed inset-0 w-screen h-screen z-[999] flex items-center justify-center bg-black/40 p-4"
    style="display: none;">

    <x-card
        x-transition
        class="w-full {{ $maxWidthClass }} relative">

        {{-- HEADER --}}
        @isset($header)
        <div class="mb-4">
            {{ $header }}
        </div>
        @endisset

        {{-- BODY --}}
        <div class="mb-4">
            {{ $slot }}
        </div>

        {{-- FOOTER --}}
        @isset($footer)
        <div class="mt-4">
            {{ $footer }}
        </div>
        @endisset

    </x-card>

</div>