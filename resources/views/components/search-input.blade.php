{{-- resources/views/components/search-input.blade.php --}}

@props([
'placeholder' => 'Cari',
'name' => 'search',
])

<div class="bg-white rounded-lg p-3">

    <div class="relative">

        {{-- SEARCH ICON --}}
        <img
            src="{{ asset('assets/icons/search-icon.png') }}"
            alt="Search"
            class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4">

        <input
            type="text"
            name="{{ $name }}"
            placeholder="{{ $placeholder }}"

            {{ $attributes->merge([
                'class' => '
                    w-full pl-10 pr-4 py-2.5
                    rounded-lg bg-[#f8f8f8] border-none
                    text-xs lg:text-sm text-neutral
                    focus:outline-none
                    focus:ring-2
                    focus:ring-primary
                    focus:border-primary
                '
            ]) }}>

    </div>

</div>