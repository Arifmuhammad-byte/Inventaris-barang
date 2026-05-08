@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Pagination Navigation" class="flex justify-center mt-8">

        <ul class="inline-flex items-center -space-x-px text-sm">

            {{-- Previous --}}
            @if ($paginator->onFirstPage())
                <li>
                    <span class="px-4 py-2 text-gray-400 bg-gray-100 border border-gray-200 cursor-not-allowed">
                        ‹
                    </span>
                </li>
            @else
                <li>
                    <button wire:click="previousPage" 
                        class="px-4 py-2 text-gray-600 bg-white border border-gray-200 
                               hover:bg-[#09637E] hover:text-white transition">
                        ‹
                    </button>
                </li>
            @endif

            {{-- Page Numbers --}}
            @foreach ($elements as $element)
                @if (is_string($element))
                    <li>
                        <span class="px-4 py-2 text-gray-400 border border-gray-200">
                            {{ $element }}
                        </span>
                    </li>
                @endif

                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        <li>
                            @if ($page == $paginator->currentPage())
                                <span class="px-4 py-2 text-white bg-[#09637E] border border-[#09637E] font-semibold">
                                    {{ $page }}
                                </span>
                            @else
                                <button wire:click="gotoPage({{ $page }})"
                                    class="px-4 py-2 text-gray-600 bg-white border border-gray-200
                                           hover:bg-[#09637E] hover:text-white transition">
                                    {{ $page }}
                                </button>
                            @endif
                        </li>
                    @endforeach
                @endif
            @endforeach

            {{-- Next --}}
            @if ($paginator->hasMorePages())
                <li>
                    <button wire:click="nextPage"
                        class="px-4 py-2 text-gray-600 bg-white border border-gray-200
                               hover:bg-[#09637E] hover:text-white transition">
                        ›
                    </button>
                </li>
            @else
                <li>
                    <span class="px-4 py-2 text-gray-400 bg-gray-100 border border-gray-200 cursor-not-allowed">
                        ›
                    </span>
                </li>
            @endif

        </ul>

    </nav>
@endif