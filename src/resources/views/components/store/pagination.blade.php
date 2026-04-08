@props([
  'currentPage' => 1,
  'totalPages' => 1,
])

@php
  $baseUrl = url()->current();
  $query   = request()->except('page');
  $pageUrl = fn($p) => $baseUrl . '?' . http_build_query(array_merge($query, ['page' => $p]));
@endphp

<div class="flex items-center justify-center gap-1">
  @if ($currentPage > 1)
    <a href="{{ $pageUrl($currentPage - 1) }}" class="w-9 h-9 flex items-center justify-center border border-gray-300 text-sm hover:border-brand-dark transition-colors">&lsaquo;</a>
  @endif

  @for ($i = 1; $i <= min(3, $totalPages); $i++)
    <a
      href="{{ $pageUrl($i) }}"
      class="w-9 h-9 flex items-center justify-center text-sm {{ $i === $currentPage ? 'bg-brand-dark text-white font-bold' : 'border border-gray-300 hover:border-brand-dark transition-colors' }}"
    >{{ $i }}</a>
  @endfor

  @if ($totalPages > 4)
    <span class="px-1 text-gray-400 text-sm">...</span>
  @endif

  @if ($totalPages > 3)
    <a href="{{ $pageUrl($totalPages) }}" class="w-9 h-9 flex items-center justify-center border border-gray-300 text-sm {{ $currentPage === $totalPages ? 'bg-brand-dark text-white font-bold' : 'hover:border-brand-dark transition-colors' }}">{{ $totalPages }}</a>
  @endif

  @if ($currentPage < $totalPages)
    <a href="{{ $pageUrl($currentPage + 1) }}" class="w-9 h-9 flex items-center justify-center border border-gray-300 text-sm hover:border-brand-dark transition-colors">&rsaquo;</a>
  @endif
</div>
