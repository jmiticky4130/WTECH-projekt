@props(['active' => 'products'])

<aside class="hidden md:flex flex-col w-64 shrink-0 bg-brand-dark text-white min-h-screen">
  <div class="px-6 py-5 border-b border-gray-600">
    <a href="{{ route('admin.products') }}" class="font-bold text-lg tracking-wider">
      <span class="inline-block bg-white rounded-lg px-2 py-1">
        <img src="{{ asset('images/brand-logo.png') }}" alt="Bellura" class="h-10">
      </span>
    </a>
    <p class="text-xs text-gray-400 mt-0.5">Administrácia</p>
  </div>

  <nav class="flex-1 px-3 py-4 space-y-1 text-sm font-medium">
    <a href="{{ route('admin.products') }}" @class([
      'flex items-center gap-3 px-3 py-2.5 rounded transition-colors',
      'bg-brand-accent' => $active === 'products',
      'hover:bg-brand-accent' => $active !== 'products',
    ])>
      <img src="{{ asset('icons/products.svg') }}" class="w-4 h-4 brightness-0 invert" alt="Produkty" />
      Produkty
    </a>
    <a href="{{ route('admin.orders') }}" @class([
      'flex items-center gap-3 px-3 py-2.5 rounded transition-colors',
      'bg-brand-accent' => $active === 'orders',
      'hover:bg-brand-accent' => $active !== 'orders',
    ])>
      <img src="{{ asset('icons/orders.svg') }}" class="w-4 h-4 brightness-0 invert" alt="Objednávky" />
      Objednávky
    </a>
    <a href="{{ route('admin.settings') }}" @class([
      'flex items-center gap-3 px-3 py-2.5 rounded transition-colors',
      'bg-brand-accent' => $active === 'settings',
      'hover:bg-brand-accent' => $active !== 'settings',
    ])>
      <img src="{{ asset('icons/settings.svg') }}" class="w-4 h-4 brightness-0 invert" alt="Nastavenia" />
      Nastavenia
    </a>
  </nav>

  <div class="px-3 py-4 border-t border-gray-600">
    <a href="{{ route('admin.login') }}" class="flex items-center gap-3 px-3 py-2.5 text-sm font-medium hover:bg-brand-accent rounded transition-colors w-full">
      <img src="{{ asset('icons/logout.svg') }}" class="w-4 h-4 brightness-0 invert" alt="Odhlásiť sa" />
      Odhlásiť sa
    </a>
  </div>
</aside>
