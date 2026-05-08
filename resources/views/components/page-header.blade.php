@props([
'title' => '',
'description' => '',
])

<div class="flex flex-col lg:flex-row lg:items-center lg:justify-between lg:gap-4 gap-2 mb-6">

    {{-- LEFT --}}
    <div>
        <h1 class="text-2xl lg:text-3xl font-bold text-primary mb-2">
            {{ $title }}
        </h1>

        <p class="lg:text-md text-sm text-neutral">
            {{ $description }}
        </p>
    </div>

    {{-- RIGHT --}}
    <div class="flex items-center gap-2">
        {{ $action ?? '' }}
    </div>

</div>