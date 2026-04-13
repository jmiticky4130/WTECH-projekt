@php
  $genderLabels = ['zeny' => 'Ženy', 'muzi' => 'Muži', 'deti' => 'Deti'];
  $genderLabel  = $genderLabels[$gender ?? ''] ?? null;
  $h1           = $category->name ?? $genderLabel ?? 'Všetky produkty';
  $pageTitle    = $h1 . ' — Bellura.sk';

  if ($gender) {
    $breadcrumb = [['label' => $genderLabel, 'href' => $category ? url('/kategoria/' . $gender) : null]];
    if ($category) $breadcrumb[] = ['label' => $category->name];
  } else {
    $breadcrumb = [['label' => 'Domov', 'href' => url('/')], ['label' => $h1]];
  }

  $from      = $total > 0 ? ($page - 1) * $perPage + 1 : 0;
  $to        = min($page * $perPage, $total);
  $formAction = url(request()->path());
  $clearUrl   = url(request()->path());
  $emptyMsg   = 'Žiadne produkty neboli nájdené.';
@endphp

<x-store.layout :title="$pageTitle">
  <x-store.breadcrumb :items="$breadcrumb" class="sticky top-0 bg-white z-30" />
  <main class="flex-1">
    <div class="max-w-7xl mx-auto px-4 py-6">
      @include('partials.product-listing')
    </div>
  </main>
</x-store.layout>
