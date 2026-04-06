@php
  $genders = ['zeny', 'muzi', 'deti'];
  $seg2    = request()->segment(2);
  $seg3    = request()->segment(3);

  if (in_array($seg2, $genders)) {
    $gender      = $seg2;
    $activeSlug  = $seg3;
  } else {
    $gender      = null;
    $activeSlug  = $seg2; // e.g. /kategoria/oblecenie
  }

  $items = [
    ['label' => 'Novinky',   'slug' => 'novinky'],
    ['label' => 'Akcie',     'slug' => 'akcie'],
    ['label' => 'Oblečenie', 'slug' => 'oblecenie'],
    ['label' => 'Topánky',   'slug' => 'topanky'],
    ['label' => 'Doplnky',   'slug' => 'doplnky'],
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
          href="{{ url('/kategoria/' . ($gender ? $gender . '/' : '') . $item['slug']) }}"
          @class([
            'py-1 font-medium text-sm transition-colors',
            $i === 0 ? 'pr-4 pl-0' : 'px-4',
            'bg-brand-accent' => $activeSlug === $item['slug'],
            'hover:bg-brand-accent' => $activeSlug !== $item['slug'],
          ])
        >{{ $item['label'] }}</a>
      @endforeach
    </div>
  </details>
  <div class="hidden md:block overflow-x-auto [scrollbar-width:none] [&::-webkit-scrollbar]:hidden">
    <div class="max-w-7xl mx-auto px-4 flex items-center gap-1 text-sm whitespace-nowrap">
      @foreach ($items as $i => $item)
        <a
          href="{{ url('/kategoria/' . ($gender ? $gender . '/' : '') . $item['slug']) }}"
          @class([
            'py-1 font-medium transition-colors',
            $i === 0 ? 'pr-4 pl-0' : 'px-4',
            'bg-brand-accent' => $activeSlug === $item['slug'],
            'hover:bg-brand-accent' => $activeSlug !== $item['slug'],
          ])
        >{{ $item['label'] }}</a>
      @endforeach
    </div>
  </div>
</div>
