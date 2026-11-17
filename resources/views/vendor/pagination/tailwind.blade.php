@if ($paginator->hasPages())
    <nav role="navigation" class="flex flex-wrap items-center justify-between gap-3">
        <p class="text-sm text-slate-400">
            Menampilkan {{ $paginator->firstItem() ?? 0 }} – {{ $paginator->lastItem() ?? 0 }} dari {{ $paginator->total() }} data
        </p>

        <div class="flex items-center gap-1">
            @if ($paginator->onFirstPage())
                <span class="h-9 w-9 rounded-lg border border-slate-200 flex items-center justify-center text-slate-300">&laquo;</span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="h-9 w-9 rounded-lg border border-slate-200 flex items-center justify-center text-slate-500 hover:bg-slate-50 transition">&laquo;</a>
            @endif

            @foreach ($elements as $element)
                @if (is_string($element))
                    <span class="h-9 w-9 flex items-center justify-center text-slate-300">{{ $element }}</span>
                @endif
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <span aria-current="page" class="h-9 w-9 rounded-lg bg-navy-700 text-white flex items-center justify-center text-sm font-semibold">{{ $page }}</span>
                        @else
                            <a href="{{ $url }}" class="h-9 w-9 rounded-lg border border-slate-200 flex items-center justify-center text-sm text-slate-600 hover:bg-slate-50 transition">{{ $page }}</a>
                        @endif
                    @endforeach
                @endif
            @endforeach

            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="h-9 w-9 rounded-lg border border-slate-200 flex items-center justify-center text-slate-500 hover:bg-slate-50 transition">&raquo;</a>
            @else
                <span class="h-9 w-9 rounded-lg border border-slate-200 flex items-center justify-center text-slate-300">&raquo;</span>
            @endif
        </div>
    </nav>
@endif
