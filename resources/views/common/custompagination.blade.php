@if ($paginator->hasPages())
    <ul class="pager pagNav ml-auto float-right row">
        {{-- Previous Page Link --}}
        @if ($paginator->onFirstPage())
            <li class="disabled link"><span>Previous</span></li>
        @else
        <li class="link"><a href="{{ $paginator->previousPageUrl() }}" rel="prev">Previous</a></li>
        @endif
        {{-- Pagination Elements --}}
        @foreach ($elements as $element)
            {{-- "Three Dots" Separator --}}
            @if (is_string($element))
                <li class="disabled link"><span>{{ $element }}</span></li>
            @endif
            {{-- Array Of Links --}}
            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <li class="active my-active link2"><span>{{ $page }}</span></li>
                    @else
                    <li class="link2"><a href="{{ $url }}"><span>{{ $page }}</span></a></li>
                    @endif
                @endforeach
            @endif
        @endforeach
        {{-- Next Page Link --}}
        @if ($paginator->hasMorePages())
        <li class="link"><a href="{{ $paginator->nextPageUrl() }}" rel="next">Next</a></li>
        @else
            <li class="disabled link"><span>Next</span></li>
        @endif
    </ul>
@endif