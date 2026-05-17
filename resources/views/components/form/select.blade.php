@props([
'label' => '',
'name',
'placeholder' => 'Pilih opsi',
])

<div class="space-y-2">

    @if($label)
    <label
        for="{{ $name }}"
        class="block text-sm font-medium text-neutral">
        {{ $label }}
    </label>
    @endif

    <select
        id="{{ $name }}"
        name="{{ $name }}"

        {{ $attributes->merge([
            'class' => '
                w-full
                rounded-md
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
        <option value="" disabled selected hidden>
            {{ $placeholder }}
        </option>

        {{ $slot }}

    </select>

    @error($name)
    <p class="text-sm text-red-500">
        {{ $message }}
    </p>
    @enderror

</div>