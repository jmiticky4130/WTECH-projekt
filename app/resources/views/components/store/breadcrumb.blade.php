@props(['items' => []])

<div class="border-b border-gray-200">
  <div class="max-w-7xl mx-auto px-4 py-2.5 text-sm text-gray-500 flex items-center gap-1.5">
    @foreach ($items as $i => $item)
      @if ($i > 0)
        <span class="text-gray-300">&rsaquo;</span>
      @endif

      @if ($loop->last)
        <span class="text-brand-dark font-medium">{{ $item['label'] }}</span>
      @else
        <a href="{{ $item['href'] ?? '#' }}" class="hover:text-brand-dark transition-colors">{{ $item['label'] }}</a>
      @endif
    @endforeach
  </div>
</div>
