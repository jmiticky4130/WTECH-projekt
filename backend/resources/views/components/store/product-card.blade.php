@props([
  'href' => null,
  'image' => '',
  'alt' => '',
  'brand' => '',
  'name' => '',
  'sizes' => '',
  'price' => '',
])

<a href="{{ $href ?? route('store.product') }}" class="group block border border-gray-200 bg-white hover:shadow-sm transition-shadow">
  <div class="aspect-[8/9] bg-gray-100 relative overflow-hidden">
    @if ($image)
      <img src="{{ asset($image) }}" class="w-full absolute top-1/2 -translate-y-1/2" alt="{{ $alt ?: $name }}">
    @endif
  </div>
  <div class="p-3 border-t border-gray-100">
    <p class="text-xs text-gray-400 mb-0.5">{{ $brand }}</p>
    <p class="text-sm font-semibold group-hover:underline">{{ $name }}</p>
    @if ($sizes)
      <p class="text-xs text-gray-500 mt-0.5">Veľkosti: {{ $sizes }}</p>
    @endif
    <p class="text-sm font-bold mt-1.5">{{ $price }}</p>
  </div>
</a>
