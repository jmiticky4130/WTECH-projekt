@php
  $pageTitle    = $q !== '' ? 'Výsledky pre „' . $q . '" — Bellura.sk' : 'Hľadať — Bellura.sk';
  $h1           = $q !== '' ? 'Výsledky pre „' . $q . '"' : 'Všetky produkty';
  $from         = $total > 0 ? ($page - 1) * $perPage + 1 : 0;
  $to           = min($page * $perPage, $total);
  $formAction   = route('store.search');
  $clearUrl     = route('store.search', ['q' => $q]);
  $searchQuery  = $q;
  $emptyMsg     = $q !== '' ? 'Pre „' . $q . '" sa nenašli žiadne produkty.' : 'Žiadne produkty neboli nájdené.';
  $breadcrumb   = [['label' => 'Domov', 'href' => url('/')], ['label' => 'Hľadať']];
@endphp

<x-store.layout :title="$pageTitle">
  <x-store.breadcrumb :items="$breadcrumb" class="sticky top-0 bg-white z-30" />
  <main class="flex-1">
    <div class="max-w-7xl mx-auto px-4 py-6">
      @include('partials.product-listing')
    </div>
  </main>
</x-store.layout>
