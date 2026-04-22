@props([
  'image' => '',
  'alt' => '',
  'brand' => '',
  'name' => '',
  'color' => '',
  'size' => '',
  'price' => '',
  'quantity' => 1,
  'total' => '',
])

@php
  $resolvedImage = \App\Support\ProductImageUrl::resolve($image);
@endphp

{{-- Desktop layout --}}
<div class="hidden md:grid grid-cols-[1fr_5rem_6rem_8rem_7rem_5rem] items-center">
  <div class="flex gap-3 items-center">
    <div class="w-16 h-24 bg-gray-200 shrink-0 overflow-hidden relative">
      @if ($resolvedImage)
        <img src="{{ $resolvedImage }}" class="w-full absolute top-1/2 -translate-y-1/2" alt="{{ $alt ?: $name }}">
      @endif
    </div>
    <div>
      <p class="text-xs text-gray-400">{{ $brand }}</p>
      <p class="text-sm font-bold">{{ $name }}</p>
      @if ($color)
        <p class="text-xs text-gray-500">Farba: {{ $color }}</p>
      @endif
    </div>
  </div>
  <span class="text-sm text-center">{{ $size }}</span>
  <span class="text-sm text-center">{{ $price }}</span>
  <div class="flex justify-center">
    <x-store.quantity-selector :quantity="$quantity" :compact="true" />
  </div>
  <span class="text-sm font-bold text-right pr-4">{{ $total }}</span>
  <div class="flex justify-center">
    <button class="hover:opacity-60 transition-opacity">
      <img src="{{ asset('icons/trash.svg') }}" class="w-5 h-5" alt="Odstrániť" />
    </button>
  </div>
</div>

{{-- Mobile layout --}}
<div class="md:hidden flex gap-3">
  <div class="w-20 h-30 bg-gray-200 shrink-0 overflow-hidden relative">
    @if ($resolvedImage)
      <img src="{{ $resolvedImage }}" class="w-full absolute top-1/2 -translate-y-1/2" alt="{{ $alt ?: $name }}">
    @endif
  </div>
  <div class="flex-1 min-w-0">
    <p class="text-xs text-gray-400">{{ $brand }}</p>
    <p class="text-sm font-bold">{{ $name }}</p>
    @if ($color)
      <p class="text-xs text-gray-500 mb-2">Farba: {{ $color }}</p>
    @endif
    <div class="flex items-center justify-between mb-2 text-xs text-gray-500">
      <span>Veľkosť: <span class="text-brand-dark font-medium">{{ $size }}</span></span>
      <span>{{ $price }}</span>
    </div>
    <div class="flex items-center gap-3">
      <x-store.quantity-selector :quantity="$quantity" :compact="true" />
      <span class="text-sm font-bold">{{ $total }}</span>
      <button class="ml-auto hover:opacity-60 transition-opacity">
        <img src="{{ asset('icons/trash.svg') }}" class="w-5 h-5" alt="Odstrániť" />
      </button>
    </div>
  </div>
</div>
