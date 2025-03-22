@if ($paginator->hasPages())
    <nav aria-label="Page navigation">
        <ul class="pagination">
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <li class="page-item disabled"><span class="page-link">&laquo;</span></li>
            @else
                <li class="page-item"><a class="page-link" href="{{ $paginator->previousPageUrl() }}" rel="prev">&laquo;</a></li>
            @endif

            {{-- Pagination Elements --}}
            @foreach ($elements as $element)
                {{-- "Three Dots" Separator --}}
                @if (is_string($element))
                    <li class="page-item disabled"><span class="page-link">{{ $element }}</span></li>
                @endif

                {{-- Array Of Links --}}
                @if (is_array($element))
                    @php
                        $totalPages = count($element);
                        $current = $paginator->currentPage();
                        $showDots = true;
                    @endphp

                    @foreach ($element as $page => $url)
                        @if ($page == $current || $page <= 3 || $page > $totalPages - 3 || abs($current - $page) <= 1)
                            <li class="page-item {{ ($page == $paginator->currentPage()) ? ' active' : '' }}">
                                <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                            </li>
                            @php $showDots = true; @endphp
                        @elseif($showDots)
                            <li class="page-item disabled"><span class="page-link">...</span></li>
                            @php $showDots = false; @endphp
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <li class="page-item"><a class="page-link" href="{{ $paginator->nextPageUrl() }}" rel="next">&raquo;</a></li>
            @else
                <li class="page-item disabled"><span class="page-link">&raquo;</span></li>
            @endif
        </ul>
    </nav>
@endif