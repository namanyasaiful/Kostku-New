{{-- resources/views/components/pagination.blade.php --}}

@props([
'currentPage' => 1,
'totalPages' => 5,
])

<div class="flex justify-center">

    <nav aria-label="Pagination">

        <ul class="flex items-center -space-x-px text-xs lg:text-sm">

            {{-- PREVIOUS --}}
            <li>

                <a
                    href="#"
                    class="
                        flex items-center justify-center
                        w-8 h-8 lg:w-9 lg:h-9
                        border border-gray-300
                        rounded-s-lg
                        text-primary bg-transparent
                        hover:bg-gray-100
                    ">

                    <svg
                        class="w-3 h-3"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24">

                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M15 19l-7-7 7-7" />

                    </svg>

                </a>

            </li>

            {{-- PAGE NUMBERS --}}
            @for ($i = 1; $i <= $totalPages; $i++)

                <li>

                <a
                    href="#"
                    class="
                        flex items-center justify-center
                        w-8 h-8 lg:w-9 lg:h-9
                        border border-gray-300

                        {{ $currentPage == $i
                            ? 'bg-primary text-white'
                            : 'text-gray-500 bg-transparent hover:bg-gray-100'
                        }}
                    ">

                    {{ $i }}

                </a>

                </li>

                @endfor

                {{-- NEXT --}}
                <li>

                    <a
                        href="#"
                        class="
                        flex items-center justify-center
                        w-8 h-8 lg:w-9 lg:h-9
                        border border-gray-300
                        rounded-e-lg
                        text-primary bg-transparent
                        hover:bg-gray-100
                    ">

                        <svg
                            class="w-3 h-3"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24">

                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M9 5l7 7-7 7" />

                        </svg>

                    </a>

                </li>

        </ul>

    </nav>

</div>