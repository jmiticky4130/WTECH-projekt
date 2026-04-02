<nav class="bg-brand-dark text-white">
  <div class="md:max-w-7xl md:mx-auto md:px-4 flex items-center text-sm font-semibold">
    <a href="{{ route('home') }}" class="flex flex-1 md:flex-none items-center justify-center gap-2 pr-4 pl-0 py-1.5 hover:bg-brand-accent transition-colors">
      <img src="{{ asset('icons/home-icon.svg') }}" class="w-6 h-6" alt="Home" />
      <span class="hidden md:inline">Domov</span>
    </a>
    <span class="hidden md:block w-px h-4 bg-white/25"></span>
    <a href="{{ route('store.category') }}" class="flex-1 md:flex-none text-center px-4 py-1.5 hover:bg-brand-accent transition-colors">ŽENY</a>
    <span class="hidden md:block w-px h-4 bg-white/25"></span>
    <a href="{{ route('store.category') }}" class="flex-1 md:flex-none text-center px-4 py-1.5 hover:bg-brand-accent transition-colors">MUŽI</a>
    <span class="hidden md:block w-px h-4 bg-white/25"></span>
    <a href="{{ route('store.category') }}" class="flex-1 md:flex-none text-center px-4 py-1.5 hover:bg-brand-accent transition-colors">DETI</a>
  </div>
</nav>
