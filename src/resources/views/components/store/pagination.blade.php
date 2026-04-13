@props([
  'currentPage' => 1,
  'totalPages' => 1,
])

@if ($totalPages <= 1)
  @php return; @endphp
@endif

@php
  $baseUrl = url()->current();
  $query   = request()->except('page');
  $pageUrl = fn($p) => $baseUrl . '?' . http_build_query(array_merge($query, ['page' => $p]));

  // Always show first, last, current, and one neighbour on each side
  $show = collect([1, $totalPages, $currentPage, $currentPage - 1, $currentPage + 1])
    ->filter(fn($p) => $p >= 1 && $p <= $totalPages)
    ->unique()
    ->sort()
    ->values();
@endphp

<div class="flex items-center justify-center gap-1">
  {{-- Prev --}}
  @if ($currentPage > 1)
    <a href="{{ $pageUrl($currentPage - 1) }}" class="w-9 h-9 flex items-center justify-center border border-gray-300 text-sm hover:border-brand-dark transition-colors">&lsaquo;</a>
  @endif

  @foreach ($show as $index => $p)
    {{-- Gap before this page? --}}
    @if ($index > 0 && $p - $show[$index - 1] > 1)
      <span class="px-1 text-gray-400 text-sm">…</span>
    @endif

    <a
      href="{{ $pageUrl($p) }}"
      class="w-9 h-9 flex items-center justify-center text-sm {{ $p === $currentPage ? 'bg-brand-dark text-white font-bold' : 'border border-gray-300 hover:border-brand-dark transition-colors' }}"
    >{{ $p }}</a>
  @endforeach

  {{-- Next --}}
  @if ($currentPage < $totalPages)
    <a href="{{ $pageUrl($currentPage + 1) }}" class="w-9 h-9 flex items-center justify-center border border-gray-300 text-sm hover:border-brand-dark transition-colors">&rsaquo;</a>
  @endif
</div>
