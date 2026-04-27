<div class="border-b border-gray-200 hidden md:block">
  <div class="max-w-7xl mx-auto px-4 py-0.5 flex items-center gap-6 text-sm text-gray-500">
    <div x-data="{ open: false }" class="relative" @click.outside="open = false">
      <button @click="open = !open" class="flex items-center gap-1 hover:text-brand-dark transition-colors">
        Kontakt
        <img src="{{ asset('icons/chevron-down.svg') }}" class="w-3 h-3 transition-transform brightness-0 opacity-50" :class="open && 'rotate-180'" alt="">
      </button>
      <div
        x-show="open"
        x-cloak
        x-transition:enter="transition ease-out duration-100"
        x-transition:enter-start="opacity-0 -translate-y-1"
        x-transition:enter-end="opacity-100 translate-y-0"
        class="absolute top-full left-0 mt-1 bg-white border border-gray-200 shadow-md z-50 min-w-max"
      >
        <a href="mailto:info@bellura.sk" class="block px-4 py-2 hover:bg-gray-50 hover:text-brand-dark transition-colors">
          info@bellura.sk
        </a>
        <a href="tel:+421900000000" class="block px-4 py-2 hover:bg-gray-50 hover:text-brand-dark transition-colors border-t border-gray-100">
          +421 900 000 000
        </a>
      </div>
    </div>
  </div>
</div>
