<header class="border-b border-gray-200">
  <div class="max-w-7xl mx-auto px-4 py-1 flex items-center md:relative">
    <a href="{{ url('/') }}" class="shrink-0 -ml-px">
      <img src="{{ asset('images/brand-logo.png') }}" alt="Bellura" class="h-12">
    </a>

    <div class="hidden md:block absolute left-1/2 -translate-x-1/2">
      <div class="flex w-[28rem]">
        <input
          type="text"
          placeholder="Hľadať produkty..."
          class="w-full border border-gray-300 border-r-0 px-4 py-2.5 text-sm focus:outline-none focus:border-brand-dark focus:placeholder-transparent"
        />
        <button class="bg-brand-dark hover:bg-brand-accent text-white px-4 transition-colors">
          <img src="{{ asset('icons/search-icon.svg') }}" class="w-5 h-5" alt="Search" />
        </button>
      </div>
    </div>

    <div class="flex items-center gap-6 min-[1000px]:gap-6 shrink-0 ml-auto">
      <a href="{{ route('store.product') }}" class="flex items-center gap-2 text-sm text-gray-600 hover:text-brand-dark transition-colors">
        <img src="{{ asset('icons/profile-circle.svg') }}" class="w-7 h-7 brightness-0" alt="Profile" />
        <span class="hidden min-[1000px]:inline">Prihlásenie</span>
      </a>
      <a href="{{ route('store.cart') }}" class="flex items-center gap-2 text-sm text-gray-600 hover:text-brand-dark transition-colors">
        <img src="{{ asset('icons/shopping-cart.svg') }}" class="w-7 h-7 brightness-0" alt="Cart" />
        <span class="hidden min-[1000px]:inline">Košík (0)</span>
      </a>
    </div>
  </div>

  <div class="md:hidden px-4 pb-1">
    <div class="flex">
      <input
        type="text"
        placeholder="Hľadať produkty..."
        class="w-full border border-gray-300 border-r-0 px-4 py-1 text-sm focus:outline-none focus:placeholder-transparent"
      />
      <button class="bg-brand-dark hover:bg-brand-accent text-white px-4 transition-colors">
        <img src="{{ asset('icons/search-icon.svg') }}" class="w-5 h-5" alt="Search" />
      </button>
    </div>
  </div>
</header>
