@props([
  'quantity' => 1,
  'compact' => false,
])

@if ($compact)
  {{-- Compact variant used in cart items --}}
  <div class="flex items-center border border-gray-300">
    <button class="px-2.5 py-1 hover:bg-gray-100 transition-colors text-sm">&minus;</button>
    <span class="px-3 py-1 border-x border-gray-300 text-sm min-w-[2rem] text-center">{{ $quantity }}</span>
    <button class="px-2.5 py-1 hover:bg-gray-100 transition-colors text-sm">+</button>
  </div>
@else
  {{-- Full variant used on product detail --}}
  <div class="flex items-center">
    <div class="w-10 h-10 border border-gray-300 flex items-center justify-center text-lg font-light select-none">&minus;</div>
    <div class="w-14 h-10 border-t border-b border-gray-300 flex items-center justify-center text-sm font-semibold">{{ $quantity }}</div>
    <div class="w-10 h-10 border border-gray-300 flex items-center justify-center text-lg font-light select-none">+</div>
  </div>
@endif
