@props(['active' => null])

@php
  $items = [
    ['label' => 'Novinky', 'href' => route('store.category')],
    ['label' => 'Akcie', 'href' => route('store.category')],
    ['label' => 'Oblečenie', 'href' => route('store.category')],
    ['label' => 'Topánky', 'href' => route('store.category')],
    ['label' => 'Doplnky', 'href' => route('store.category')],
  ];
@endphp

<div class="bg-brand-dark text-white border-t border-gray-600">
  <details class="md:hidden group">
    <summary class="flex items-center justify-between px-4 py-1 text-sm font-medium cursor-pointer list-none select-none">
      <span>Kategórie</span>
      <img src="{{ asset('icons/chevron-down.svg') }}" class="w-4 h-4 transition-transform group-open:rotate-180 brightness-0 invert" alt="" />
    </summary>
    <div class="flex flex-col border-t border-gray-600">
      @foreach ($items as $i => $item)
        <a
          href="{{ $item['href'] }}"
          class="{{ $i === 0 ? 'pr-4 pl-0' : 'px-4' }} py-1 {{ $active === $item['label'] ? 'bg-brand-accent' : 'hover:bg-brand-accent transition-colors' }} font-medium text-sm"
        >{{ $item['label'] }}</a>
      @endforeach
    </div>
  </details>
  <div class="hidden md:block overflow-x-auto [scrollbar-width:none] [&::-webkit-scrollbar]:hidden">
    <div class="max-w-7xl mx-auto px-4 flex items-center gap-1 text-sm whitespace-nowrap">
      @foreach ($items as $i => $item)
        <a
          href="{{ $item['href'] }}"
          class="{{ $i === 0 ? 'pr-4 pl-0' : 'px-4' }} py-1 {{ $active === $item['label'] ? 'bg-brand-accent' : 'hover:bg-brand-accent transition-colors' }} font-medium"
        >{{ $item['label'] }}</a>
      @endforeach
    </div>
  </div>
</div>
