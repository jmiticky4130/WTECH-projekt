@props(['active' => 'products'])

<details class="md:hidden bg-brand-dark text-white">
  <summary class="flex items-center justify-between px-4 py-3 cursor-pointer list-none select-none">
    <a href="{{ route('admin.products') }}" class="font-bold text-lg tracking-wider">
      <span class="inline-block bg-white rounded-lg px-2 py-1">
        <img src="{{ asset('images/brand-logo.png') }}" alt="Bellura" class="h-10">
      </span>
    </a>
    <img src="{{ asset('icons/hamburger-menu.svg') }}" class="w-6 h-6 brightness-0 invert" alt="Menu" />
  </summary>
  <div class="border-t border-gray-600 flex flex-col text-sm font-medium">
    <a href="{{ route('admin.products') }}" @class([
      'px-4 py-3 transition-colors',
      'bg-brand-accent' => $active === 'products',
      'hover:bg-brand-accent' => $active !== 'products',
    ])>Produkty</a>
    <a href="{{ route('admin.orders') }}" @class([
      'px-4 py-3 transition-colors',
      'bg-brand-accent' => $active === 'orders',
      'hover:bg-brand-accent' => $active !== 'orders',
    ])>Objednávky</a>
    <a href="{{ route('admin.settings') }}" @class([
      'px-4 py-3 transition-colors',
      'bg-brand-accent' => $active === 'settings',
      'hover:bg-brand-accent' => $active !== 'settings',
    ])>Nastavenia</a>
    <form method="POST" action="{{ route('admin.logout') }}">
      @csrf
      <button type="submit" class="w-full text-left px-4 py-3 hover:bg-brand-accent transition-colors">Odhlásiť sa</button>
    </form>
  </div>
</details>
