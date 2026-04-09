@php
  $genderLabels = ['zeny' => 'Ženy', 'muzi' => 'Muži', 'deti' => 'Deti'];
  $genderLabel  = $genderLabels[$gender ?? ''] ?? null;
  $h1           = $category->name ?? $genderLabel ?? 'Všetky produkty';
  $pageTitle    = $h1 . ' — Bellura.sk';

  if ($gender) {
    $breadcrumb = [['label' => $genderLabel, 'href' => $category ? url('/kategoria/' . $gender) : null]];
    if ($category) {
      $breadcrumb[] = ['label' => $category->name];
    }
  } else {
    $breadcrumb   = [['label' => 'Domov', 'href' => url('/')]];
    $breadcrumb[] = ['label' => $h1];
  }

  $from = $total > 0 ? ($page - 1) * $perPage + 1 : 0;
  $to   = min($page * $perPage, $total);
@endphp

<x-store.layout :title="$pageTitle">

  <x-store.breadcrumb :items="$breadcrumb" class="sticky top-0 bg-white z-30" />

  <main class="flex-1">
    <div class="max-w-7xl mx-auto px-4 py-6">
      <div class="flex gap-8 items-start">

        <!-- filter sidebar -->
        <aside class="hidden lg:block w-56 shrink-0 sticky top-16">
          <form method="GET" id="filter-form">
            @if (request('sort'))
              <input type="hidden" name="sort" value="{{ request('sort') }}" />
            @endif

            <!-- Size filter -->
            <div class="mb-6">
              <h3 class="text-sm font-bold mb-3">Veľkosť</h3>
              <div class="flex flex-wrap gap-2">
                @foreach ($allSizes as $size)
                  <label class="cursor-pointer">
                    <input type="checkbox" name="size[]" value="{{ $size }}"
                           class="sr-only peer"
                           {{ in_array($size, $filterSizes) ? 'checked' : '' }}
                           onchange="document.getElementById('filter-form').requestSubmit()" />
                    <span class="flex items-center justify-center w-9 h-9 border text-xs transition-colors
                                 peer-checked:bg-brand-dark peer-checked:text-white peer-checked:border-brand-dark
                                 border-gray-300 hover:border-brand-dark">
                      {{ $size }}
                    </span>
                  </label>
                @endforeach
              </div>
            </div>

            <!-- Price filter -->
            <div class="mb-6">
              <h3 class="text-sm font-bold mb-3">Cena</h3>
              <div class="flex justify-between text-xs text-gray-500 mb-3">
                <span id="price-label-min">{{ (int) request('min_price', $globalMinPrice) }} €</span>
                <span id="price-label-max">{{ (int) request('max_price', $globalMaxPrice) }} €</span>
              </div>
              <div class="price-slider-wrap" id="price-slider-wrap"
                   data-global-min="{{ $globalMinPrice }}"
                   data-global-max="{{ $globalMaxPrice }}">
                <div class="price-track"></div>
                <div class="price-fill" id="price-range-fill"></div>
                <input type="range" id="price-min-range"
                       min="{{ $globalMinPrice }}" max="{{ $globalMaxPrice }}"
                       value="{{ (int) request('min_price', $globalMinPrice) }}"
                       class="price-range-input" />
                <input type="range" id="price-max-range"
                       min="{{ $globalMinPrice }}" max="{{ $globalMaxPrice }}"
                       value="{{ (int) request('max_price', $globalMaxPrice) }}"
                       class="price-range-input" />
              </div>
              <input type="hidden" name="min_price" id="price-hidden-min"
                     value="{{ request('min_price', 0) }}" />
              <input type="hidden" name="max_price" id="price-hidden-max"
                     value="{{ request('max_price', '') }}" />
            </div>

            <!-- Brand filter -->
            <div class="mb-6">
              <h3 class="text-sm font-bold mb-3">Značka</h3>
              <div class="space-y-2">
                @foreach ($brands as $brand)
                  <label class="flex items-center gap-2 text-sm cursor-pointer">
                    <input type="checkbox" name="brand[]" value="{{ $brand->name }}"
                           class="w-4 h-4 accent-brand-dark shrink-0"
                           {{ in_array($brand->name, $filterBrands) ? 'checked' : '' }}
                           onchange="document.getElementById('filter-form').requestSubmit()" />
                    <span>{{ $brand->name }}</span>
                  </label>
                @endforeach
              </div>
            </div>

            <!-- Color filter -->
            <div class="mb-6">
              <h3 class="text-sm font-bold mb-3">Farba</h3>
              <div class="flex flex-wrap gap-2">
                @foreach ($colors as $color)
                  <label class="cursor-pointer" title="{{ $color->name }}">
                    <input type="checkbox" name="color[]" value="{{ $color->name }}"
                           class="sr-only peer"
                           {{ in_array($color->name, $filterColors) ? 'checked' : '' }}
                           onchange="document.getElementById('filter-form').requestSubmit()" />
                    <span class="block w-7 h-7 rounded-full border-2 transition-all
                                 peer-checked:ring-2 peer-checked:ring-brand-dark peer-checked:ring-offset-1
                                 border-gray-300 hover:ring-2 hover:ring-gray-400"
                          style="background-color: {{ $color->hex_code }}"></span>
                  </label>
                @endforeach
              </div>
            </div>

            <!-- Material filter -->
            <div class="mb-6">
              <h3 class="text-sm font-bold mb-3">Materiál</h3>
              <div class="space-y-2">
                @foreach ($materials as $material)
                  <label class="flex items-center gap-2 text-sm cursor-pointer">
                    <input type="checkbox" name="material[]" value="{{ $material->name }}"
                           class="w-4 h-4 accent-brand-dark shrink-0"
                           {{ in_array($material->name, $filterMaterials) ? 'checked' : '' }}
                           onchange="document.getElementById('filter-form').requestSubmit()" />
                    <span>{{ $material->name }}</span>
                  </label>
                @endforeach
              </div>
            </div>

            <a href="{{ url(request()->path()) }}" class="block w-full border border-gray-300 hover:border-brand-dark text-sm py-2.5 transition-colors text-center">
              Zrušiť všetky filtre
            </a>
          </form>
        </aside>

        <!-- product grid -->
        <div class="flex-1 min-w-0" id="products-section">
          <div class="sticky top-10 bg-white z-20 pb-3 mb-3 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div>
              <h1 class="text-2xl font-bold">{{ $h1 }}</h1>
              @if ($total > 0)
                <p class="text-sm text-gray-500 mt-0.5">Zobrazených {{ $from }} – {{ $to }} z {{ $total }} produktov</p>
              @else
                <p class="text-sm text-gray-500 mt-0.5">Žiadne produkty</p>
              @endif
            </div>
            <div class="flex items-center gap-3">
              <button class="lg:hidden border border-gray-300 hover:border-brand-dark text-sm px-4 py-2 transition-colors">Filtre</button>
              <div class="flex items-center gap-2 text-sm">
                <span class="text-gray-500 whitespace-nowrap">Zoradiť podľa:</span>
                <select onchange="document.getElementById('sort-form').querySelector('[name=sort]').value = this.value; document.getElementById('sort-form').requestSubmit()"
                        class="border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:border-brand-dark bg-white">
                  <option value="featured" {{ $sortBy === 'featured' ? 'selected' : '' }}>Odporúčané</option>
                  <option value="price_asc" {{ $sortBy === 'price_asc' ? 'selected' : '' }}>Cena: od najnižšej</option>
                  <option value="price_desc" {{ $sortBy === 'price_desc' ? 'selected' : '' }}>Cena: od najvyššej</option>
                  <option value="new" {{ $sortBy === 'new' ? 'selected' : '' }}>Novinky</option>
                </select>
                {{-- hidden form that mirrors filter-form + sort --}}
                <form id="sort-form" method="GET" class="hidden">
                  <input type="hidden" name="sort" value="{{ $sortBy }}" />
                  @foreach ($filterBrands as $v)    <input type="hidden" name="brand[]"    value="{{ $v }}" /> @endforeach
                  @foreach ($filterColors as $v)    <input type="hidden" name="color[]"    value="{{ $v }}" /> @endforeach
                  @foreach ($filterMaterials as $v) <input type="hidden" name="material[]" value="{{ $v }}" /> @endforeach
                  @foreach ($filterSizes as $v)     <input type="hidden" name="size[]"     value="{{ $v }}" /> @endforeach
                  @if(request('min_price')) <input type="hidden" name="min_price" value="{{ request('min_price') }}" /> @endif
                  @if(request('max_price')) <input type="hidden" name="max_price" value="{{ request('max_price') }}" /> @endif
                </form>
              </div>
            </div>
          </div>

          @if ($products->isNotEmpty())
            <div class="grid grid-cols-2 md:grid-cols-3 gap-4 mb-8">
              @foreach ($products as $product)
                <x-store.product-card
                  :href="route('store.product')"
                  :image="$product->image_path ?? ''"
                  :brand="$product->brand_name"
                  :name="$product->name"
                  :sizes="$product->sizes ?? ''"
                  :price="number_format($product->min_price, 2, ',', ' ') . ' €'"
                />
              @endforeach
            </div>
          @else
            <div class="py-16 text-center text-gray-500">
              <p class="text-lg">Žiadne produkty neboli nájdené.</p>
              <a href="{{ url(request()->path()) }}" class="mt-4 inline-block text-sm underline hover:text-brand-dark">Zrušiť filtre</a>
            </div>
          @endif

          @if ($totalPages > 1)
            <x-store.pagination :currentPage="$page" :totalPages="$totalPages" />
          @endif
        </div>

      </div>
    </div>
  </main>

<style>
.price-slider-wrap {
  position: relative;
  height: 20px;
  margin-bottom: 4px;
}
.price-track,
.price-fill {
  position: absolute;
  top: 50%;
  transform: translateY(-50%);
  height: 4px;
  border-radius: 2px;
}
.price-track {
  left: 0; right: 0;
  background: #e5e7eb;
}
.price-fill {
  background: #444444;
  pointer-events: none;
}
.price-range-input {
  position: absolute;
  top: 50%;
  transform: translateY(-50%);
  left: 0;
  width: 100%;
  height: 4px;
  -webkit-appearance: none;
  appearance: none;
  background: transparent;
  pointer-events: none;
  outline: none;
}
.price-range-input::-webkit-slider-thumb {
  -webkit-appearance: none;
  appearance: none;
  width: 18px;
  height: 18px;
  border-radius: 50%;
  background: #444444;
  border: 2px solid #fff;
  box-shadow: 0 1px 3px rgba(0,0,0,.25);
  cursor: pointer;
  pointer-events: all;
}
.price-range-input::-moz-range-thumb {
  width: 18px;
  height: 18px;
  border-radius: 50%;
  background: #444444;
  border: 2px solid #fff;
  box-shadow: 0 1px 3px rgba(0,0,0,.25);
  cursor: pointer;
  pointer-events: all;
}
</style>

<script>
(function () {
  function fetchProducts(url) {
    const section = document.getElementById('products-section');
    section.style.opacity = '0.5';
    section.style.pointerEvents = 'none';
    fetch(url)
      .then(r => r.text())
      .then(html => {
        const doc = new DOMParser().parseFromString(html, 'text/html');
        const fresh = doc.getElementById('products-section');
        if (fresh) section.innerHTML = fresh.innerHTML;
        section.style.opacity = '1';
        section.style.pointerEvents = '';
        history.pushState({}, '', url);
        bindSortListener();
      });
  }

  function bindSortListener() {
    const form = document.getElementById('sort-form');
    if (!form) return;
    form.addEventListener('submit', function handler(e) {
      e.preventDefault();
      form.removeEventListener('submit', handler);
      const url = new URL(window.location.href);
      url.searchParams.delete('page');
      new FormData(form).forEach((v, k) => url.searchParams.set(k, v));
      fetchProducts(url.toString());
    });
  }

  // Filter form lives outside products-section — bind once, survives swaps
  document.getElementById('filter-form').addEventListener('submit', function (e) {
    e.preventDefault();
    const url = new URL(window.location.href);
    const sort = url.searchParams.get('sort'); // preserve current sort across filter changes
    ['brand[]', 'color[]', 'material[]', 'size[]', 'min_price', 'max_price', 'sort', 'page']
      .forEach(k => url.searchParams.delete(k));
    new FormData(this).forEach((v, k) => {
      if (k === 'sort') return; // read from URL instead (avoids stale hidden input)
      if (k === 'min_price' && v == 0) return; // skip default
      if (k === 'max_price' && v === '') return; // skip empty
      url.searchParams.append(k, v);
    });
    if (sort) url.searchParams.set('sort', sort);
    fetchProducts(url.toString());
  });

  // Clear filters links — resets checkboxes + price slider
  document.addEventListener('click', function (e) {
    const link = e.target.closest('a[href*="kategoria"]');
    if (!link || link.href.includes('?')) return; // only clear filters on clean URLs
    e.preventDefault();
    const form = document.getElementById('filter-form');
    form.querySelectorAll('input[type="checkbox"]').forEach(cb => cb.checked = false);
    resetPriceSlider();
    fetchProducts(link.href);
  });

  // Pagination links — document-level delegation survives products-section swaps
  document.addEventListener('click', function (e) {
    const link = e.target.closest('#products-section a');
    if (!link) return;
    e.preventDefault();
    fetchProducts(link.href);
  });

  // Browser back / forward
  window.addEventListener('popstate', function () {
    fetchProducts(window.location.href);
  });

  // Price range slider
  var resetPriceSlider = function () {};
  (function () {
    const wrap = document.getElementById('price-slider-wrap');
    if (!wrap) return;
    const gMin = +wrap.dataset.globalMin;
    const gMax = +wrap.dataset.globalMax;
    const minR = document.getElementById('price-min-range');
    const maxR = document.getElementById('price-max-range');
    const fill = document.getElementById('price-range-fill');
    const labelMin = document.getElementById('price-label-min');
    const labelMax = document.getElementById('price-label-max');
    const hiddenMin = document.getElementById('price-hidden-min');
    const hiddenMax = document.getElementById('price-hidden-max');
    const range = gMax - gMin || 1;

    function pct(v) { return ((v - gMin) / range) * 100; }

    function updateFill() {
      fill.style.left  = pct(+minR.value) + '%';
      fill.style.width = (pct(+maxR.value) - pct(+minR.value)) + '%';
    }

    function syncHidden() {
      hiddenMin.value = +minR.value === gMin ? 0 : minR.value;
      hiddenMax.value = +maxR.value === gMax ? '' : maxR.value;
    }

    minR.addEventListener('input', function () {
      if (+minR.value > +maxR.value) minR.value = maxR.value;
      labelMin.textContent = minR.value + ' €';
      syncHidden(); updateFill();
    });
    maxR.addEventListener('input', function () {
      if (+maxR.value < +minR.value) maxR.value = minR.value;
      labelMax.textContent = maxR.value + ' €';
      syncHidden(); updateFill();
    });
    minR.addEventListener('change', function () {
      document.getElementById('filter-form').requestSubmit();
    });
    maxR.addEventListener('change', function () {
      document.getElementById('filter-form').requestSubmit();
    });

    resetPriceSlider = function () {
      minR.value = gMin; maxR.value = gMax;
      hiddenMin.value = 0; hiddenMax.value = '';
      labelMin.textContent = gMin + ' €';
      labelMax.textContent = gMax + ' €';
      updateFill();
    };

    syncHidden();
    updateFill();
  })();

  bindSortListener();
})();
</script>

</x-store.layout>
