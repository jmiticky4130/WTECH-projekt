@props([
  'href' => null,
  'image' => '',
  'alt' => '',
  'label' => '',
])

@php
  $resolvedImage = \App\Support\ProductImageUrl::resolve($image);
@endphp

<a href="{{ $href ?? route('store.category') }}" class="group block overflow-hidden">
  <div class="aspect-[4/4] bg-gray-200 relative overflow-hidden">
    @if ($resolvedImage)
      <img src="{{ $resolvedImage }}" class="absolute inset-0 w-full h-full object-cover object-center" alt="{{ $alt ?: $label }}">
    @endif
  </div>
  <div class="bg-brand-dark group-hover:bg-brand-accent text-white text-center py-2.5 text-sm font-semibold tracking-wider transition-colors uppercase">
    {{ $label }}
  </div>
</a>
