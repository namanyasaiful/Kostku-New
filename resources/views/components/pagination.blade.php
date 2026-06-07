{{-- resources/views/components/pagination.blade.php --}}

@props(['paginator'])

<div class="flex justify-center">
    <nav aria-label="Pagination">
        <ul class="flex items-center -space-x-px text-xs lg:text-sm">

            {{-- PREVIOUS --}}
            <li>
                <a href="{{ $paginator->previousPageUrl() ?? '#' }}"
                    class="flex items-center justify-center w-8 h-8 lg:w-9 lg:h-9 border border-gray-300 rounded-s-lg text-primary bg-transparent hover:bg-gray-100
                    {{ $paginator->onFirstPage() ? 'opacity-50 pointer-events-none' : '' }}">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </a>
            </li>

            {{-- PAGE NUMBERS --}}
            @foreach ($paginator->getUrlRange(1, $paginator->lastPage()) as $page => $url)
            <li>
                <a href="{{ $url }}"
                    class="flex items-center justify-center w-8 h-8 lg:w-9 lg:h-9 border border-gray-300
                    {{ $paginator->currentPage() == $page ? 'bg-primary text-white' : 'text-gray-500 bg-transparent hover:bg-gray-100' }}">
                    {{ $page }}
                </a>
            </li>
            @endforeach

            {{-- NEXT --}}
            <li>
                <a href="{{ $paginator->nextPageUrl() ?? '#' }}"
                    class="flex items-center justify-center w-8 h-8 lg:w-9 lg:h-9 border border-gray-300 rounded-e-lg text-primary bg-transparent hover:bg-gray-100
                    {{ $paginator->hasMorePages() ? '' : 'opacity-50 pointer-events-none' }}">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </a>
            </li>

        </ul>
    </nav>
</div>