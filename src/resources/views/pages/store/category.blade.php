@php
  $genderLabels = ['zeny' => 'Ženy', 'muzi' => 'Muži', 'deti' => 'Deti'];
  $subcategoryLabels = [
    'novinky'   => 'Novinky',
    'akcie'     => 'Akcie',
    'oblecenie' => 'Oblečenie',
    'topanky'   => 'Topánky',
    'doplnky'   => 'Doplnky',
  ];
  $genderLabel      = $genderLabels[$gender ?? ''] ?? null;
  $subcategoryLabel = $subcategoryLabels[$subcategory ?? ''] ?? null;
  $h1               = $subcategoryLabel ?? $genderLabel ?? '';
  $pageTitle        = $h1 . ' — Bellura.sk';

  if ($gender) {
    // Ženy  OR  Ženy > Oblečenie
    $breadcrumb = [['label' => $genderLabel, 'href' => $subcategoryLabel ? url('/kategoria/' . $gender) : null]];
    if ($subcategoryLabel) {
      $breadcrumb[] = ['label' => $subcategoryLabel];
    }
  } else {
    // Domov > Oblečenie
    $breadcrumb   = [['label' => 'Domov', 'href' => url('/')]];
    $breadcrumb[] = ['label' => $subcategoryLabel ?? ''];
  }
@endphp

<x-store.layout :title="$pageTitle">

  <x-store.breadcrumb :items="$breadcrumb" />

  <main class="flex-1">
    <div class="max-w-7xl mx-auto px-4 py-6">
      <div class="flex gap-8 items-start">

        <!-- filter sidebar -->
        <aside class="hidden lg:block w-56 shrink-0">

          <div class="mb-6">
            <h3 class="text-sm font-bold mb-3">Kategórie</h3>
            <div class="space-y-2">
              @foreach ([['Tričká', 32], ['Šaty', 18], ['Nohavice', 24], ['Sukne', 28], ['Bundy', 22]] as [$label, $count])
                <label class="flex items-center gap-2 text-sm cursor-pointer">
                  <input type="checkbox" class="w-4 h-4 accent-brand-dark shrink-0" />
                  <span>{{ $label }} ({{ $count }})</span>
                </label>
              @endforeach
            </div>
          </div>

          <div class="mb-6">
            <h3 class="text-sm font-bold mb-3">Veľkosť</h3>
            <div class="flex flex-wrap gap-2">
              @foreach (['XS', 'S', 'M', 'L', 'XL'] as $size)
                <button class="w-9 h-9 border border-gray-300 text-xs hover:border-brand-dark transition-colors">{{ $size }}</button>
              @endforeach
            </div>
          </div>

          <div class="mb-6">
            <h3 class="text-sm font-bold mb-3">Cena</h3>
            <div class="flex items-center gap-2 mb-3">
              <div class="flex items-center border border-gray-300 px-2 py-1.5 text-xs w-full">
                <span class="text-gray-400 mr-1">Od</span>
                <input type="number" value="0" class="w-full focus:outline-none text-sm" />
                <span class="text-gray-400 ml-1">€</span>
              </div>
              <span class="text-gray-400 shrink-0">—</span>
              <div class="flex items-center border border-gray-300 px-2 py-1.5 text-xs w-full">
                <span class="text-gray-400 mr-1">Do</span>
                <input type="number" value="200" class="w-full focus:outline-none text-sm" />
                <span class="text-gray-400 ml-1">€</span>
              </div>
            </div>
            <input type="range" min="0" max="200" value="200" class="w-full accent-brand-dark" />
          </div>

          <div class="mb-6">
            <h3 class="text-sm font-bold mb-3">Značka</h3>
            <div class="space-y-2">
              @foreach ([['Zara', 15], ['H&M', 22], ['Reserved', 18], ['Mango', 12], ['Bershka', 8]] as [$brand, $count])
                <label class="flex items-center gap-2 text-sm cursor-pointer">
                  <input type="checkbox" class="w-4 h-4 accent-brand-dark shrink-0" />
                  <span>{{ $brand }} ({{ $count }})</span>
                </label>
              @endforeach
            </div>
          </div>

          <div class="mb-6">
            <h3 class="text-sm font-bold mb-3">Farba</h3>
            <div class="flex flex-wrap gap-2">
              @foreach (['bg-black border-black', 'bg-white border-gray-300', 'bg-gray-300 border-gray-300', 'bg-gray-500 border-gray-500', 'bg-gray-700 border-gray-700'] as $colorClass)
                <button class="w-7 h-7 rounded-full {{ $colorClass }} border-2 hover:ring-2 hover:ring-gray-400 transition-all"></button>
              @endforeach
            </div>
          </div>

          <div class="mb-6">
            <h3 class="text-sm font-bold mb-3">Materiál</h3>
            <div class="space-y-2">
              @foreach (['Bavlna', 'Polyester', 'Viskóza', 'Vlna'] as $material)
                <label class="flex items-center gap-2 text-sm cursor-pointer">
                  <input type="checkbox" class="w-4 h-4 accent-brand-dark shrink-0" />
                  <span>{{ $material }}</span>
                </label>
              @endforeach
            </div>
          </div>

          <button class="w-full border border-gray-300 hover:border-brand-dark text-sm py-2.5 transition-colors">
            Zrušiť všetky filtre
          </button>
        </aside>

        <!-- product grid -->
        <div class="flex-1 min-w-0">
          <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-5">
            <div>
              <h1 class="text-2xl font-bold">{{ $h1 }}</h1>
              <p class="text-sm text-gray-500 mt-0.5">Zobrazených 1 – 9 z 124 produktov</p>
            </div>
            <div class="flex items-center gap-3">
              <button class="lg:hidden border border-gray-300 hover:border-brand-dark text-sm px-4 py-2 transition-colors">Filtre</button>
              <div class="flex items-center gap-2 text-sm">
                <span class="text-gray-500 whitespace-nowrap">Zoradiť podľa:</span>
                <select class="border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:border-brand-dark bg-white">
                  <option>Odporúčané</option>
                  <option>Cena: od najnižšej</option>
                  <option>Cena: od najvyššej</option>
                  <option>Novinky</option>
                </select>
              </div>
            </div>
          </div>

          <div class="grid grid-cols-2 md:grid-cols-3 gap-4 mb-8">
            @for ($i = 0; $i < 9; $i++)
              <x-store.product-card href="#" image="images/products/satin-blouse.jpg" brand="H&M" name="Saténová blúzka" sizes="XS, S, M, L" price="39,99 €" />
            @endfor
          </div>

          <x-store.pagination :currentPage="1" :totalPages="14" />
        </div>

      </div>
    </div>
  </main>

</x-store.layout>
