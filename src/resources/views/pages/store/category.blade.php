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

  <x-store.breadcrumb :items="$breadcrumb" />

  <main class="flex-1">
    <div class="max-w-7xl mx-auto px-4 py-6">
      <div class="flex gap-8 items-start">

        <!-- filter sidebar -->
        <aside class="hidden lg:block w-56 shrink-0">
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
                           onchange="document.getElementById('filter-form').submit()" />
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
              <div class="flex items-center gap-2 mb-3">
                <div class="flex items-center border border-gray-300 px-2 py-1.5 text-xs w-full">
                  <span class="text-gray-400 mr-1">Od</span>
                  <input type="number" name="min_price" value="{{ request('min_price', 0) }}" min="0" class="w-full focus:outline-none text-sm" />
                  <span class="text-gray-400 ml-1">€</span>
                </div>
                <span class="text-gray-400 shrink-0">—</span>
                <div class="flex items-center border border-gray-300 px-2 py-1.5 text-xs w-full">
                  <span class="text-gray-400 mr-1">Do</span>
                  <input type="number" name="max_price" value="{{ request('max_price', '') }}" min="0" class="w-full focus:outline-none text-sm" />
                  <span class="text-gray-400 ml-1">€</span>
                </div>
              </div>
              <button type="submit" class="w-full border border-gray-300 hover:border-brand-dark text-xs py-1.5 transition-colors">Použiť</button>
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
                           onchange="document.getElementById('filter-form').submit()" />
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
                           onchange="document.getElementById('filter-form').submit()" />
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
                           onchange="document.getElementById('filter-form').submit()" />
                    <span>{{ $material->name }}</span>
                  </label>
                @endforeach
              </div>
            </div>

            <a href="{{ url()->current() }}" class="block w-full border border-gray-300 hover:border-brand-dark text-sm py-2.5 transition-colors text-center">
              Zrušiť všetky filtre
            </a>
          </form>
        </aside>

        <!-- product grid -->
        <div class="flex-1 min-w-0">
          <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-5">
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
                <select onchange="document.getElementById('sort-form').querySelector('[name=sort]').value = this.value; document.getElementById('sort-form').submit()"
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
              <a href="{{ url()->current() }}" class="mt-4 inline-block text-sm underline hover:text-brand-dark">Zrušiť filtre</a>
            </div>
          @endif

          @if ($totalPages > 1)
            <x-store.pagination :currentPage="$page" :totalPages="$totalPages" />
          @endif
        </div>

      </div>
    </div>
  </main>

</x-store.layout>
