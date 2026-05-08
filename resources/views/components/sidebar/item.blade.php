@props([
'title',
'icon',
'route' => '#',
'active' => false
])

<a
    href="{{ $route }}"
    class="
        group
        flex items-center gap-3
        px-4 py-3
        rounded-lg
        transition-all duration-200

        {{ $active
            ? 'bg-secondary text-primary'
            : 'text-black hover:bg-[#F5F6FA] hover:text-primary'
        }}
    ">

    {{-- ICON --}}
    <div
        class="
            {{ $active
                ? 'bg-secondary'
                : 'group-hover:bg-[#F5F6FA]'
            }}
        ">

        {{-- DEFAULT ICON --}}
        <img
            src="{{ asset('assets/icons/' . $icon . '.png') }}"
            class="
                w-5 h-5

                {{ $active
                    ? 'hidden'
                    : 'block group-hover:hidden'
                }}
            ">

        {{-- ACTIVE ICON --}}
        <img
            src="{{ asset('assets/icons/' . $icon . '-active.png') }}"
            class="
                w-5 h-5

                {{ $active
                    ? 'block'
                    : 'hidden group-hover:block'
                }}
            ">

    </div>

    {{-- TITLE --}}
    <span>
        {{ $title }}
    </span>

</a>