@php
  $genders     = ['zeny', 'muzi', 'deti'];
  $seg2        = request()->segment(2); // first segment after /kategoria/
  $activeGender = in_array($seg2, $genders) ? $seg2 : null;
@endphp

<nav class="bg-brand-dark text-white">
  <div class="md:max-w-7xl md:mx-auto md:px-4 flex items-center text-sm font-semibold">
    <a href="{{ route('home') }}" @class([
      'flex flex-1 md:flex-none items-center justify-center gap-2 pr-4 pl-0 py-1.5 transition-colors',
      'bg-brand-accent' => request()->routeIs('home'),
      'hover:bg-brand-accent' => ! request()->routeIs('home'),
    ])>
      <img src="{{ asset('icons/home-icon.svg') }}" class="w-6 h-6" alt="Home" />
      <span class="hidden md:inline">Domov</span>
    </a>
    <span class="hidden md:block w-px h-4 bg-white/25"></span>
    <a href="{{ url('/kategoria/zeny') }}" @class([
      'flex-1 md:flex-none text-center px-4 py-1.5 transition-colors',
      'bg-brand-accent' => $activeGender === 'zeny',
      'hover:bg-brand-accent' => $activeGender !== 'zeny',
    ])>ŽENY</a>
    <span class="hidden md:block w-px h-4 bg-white/25"></span>
    <a href="{{ url('/kategoria/muzi') }}" @class([
      'flex-1 md:flex-none text-center px-4 py-1.5 transition-colors',
      'bg-brand-accent' => $activeGender === 'muzi',
      'hover:bg-brand-accent' => $activeGender !== 'muzi',
    ])>MUŽI</a>
    <span class="hidden md:block w-px h-4 bg-white/25"></span>
    <a href="{{ url('/kategoria/deti') }}" @class([
      'flex-1 md:flex-none text-center px-4 py-1.5 transition-colors',
      'bg-brand-accent' => $activeGender === 'deti',
      'hover:bg-brand-accent' => $activeGender !== 'deti',
    ])>DETI</a>
  </div>
</nav>
