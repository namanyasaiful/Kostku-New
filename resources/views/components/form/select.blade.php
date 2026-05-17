@props([
'label' => '',
'name',
'placeholder' => 'Pilih opsi',
])

<div class="space-y-2">

    {{-- LABEL --}}
    @if($label)
    <label
        for="{{ $name }}"
        class="block text-sm font-medium text-neutral">

        {{ $label }}

    </label>
    @endif

    {{-- SELECT --}}
    <select
        id="{{ $name }}"
        name="{{ $name }}"

        {{ $attributes->merge([
            'class' => '
                w-full
                rounded-xl
                text-neutral
                border
                border-[#888888]
                px-4
                py-3
                bg-white
                focus:outline-none
                focus:ring-1
                focus:ring-primary
                focus:border-primary
            '
        ]) }}>

        {{-- PLACEHOLDER --}}
        @unless($attributes->has('x-model'))

        <option value="" disabled selected hidden>
            {{ $placeholder }}
        </option>

        @endunless

        {{ $slot }}

    </select>

    {{-- ERROR --}}
    @error($name)
    <p class="text-sm text-red-500">
        {{ $message }}
    </p>
    @enderror

</div>