<x-admin.layout title="Produkty — Bellura.sk" active="products">
  <style>
    #modal-add, #modal-edit, #modal-delete { display: none; }
    #modal-add:target, #modal-edit:target, #modal-delete:target { display: flex; }
  </style>

  <div class="px-3 py-4 sm:px-6 sm:py-6 max-w-6xl">

    @if (session('success'))
      <div class="mb-4 bg-green-50 border border-green-200 text-green-700 text-sm px-4 py-3 rounded">{{ session('success') }}</div>
    @endif

    @if ($errors->any() && old('_method') !== 'PUT')
      <div class="mb-4 bg-red-50 border border-red-200 text-red-700 text-sm px-4 py-3 rounded">
        <p class="font-semibold mb-1">Produkt sa neulozil. Skontrolujte chyby nizsie.</p>
        <ul class="list-disc list-inside space-y-0.5">
          @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    <!-- page header -->
    <div class="flex items-center justify-between mb-6">
      <div>
        <p class="text-xs text-gray-400 mb-0.5">Administrácia / Produkty</p>
        <h1 class="text-2xl font-bold text-brand-dark">Produkty</h1>
      </div>
      <button type="button" onclick="openAddModal()" class="bg-brand-dark hover:bg-brand-accent text-white text-sm font-bold tracking-widest uppercase px-5 py-2.5 transition-colors">
        + Pridať produkt
      </button>
    </div>

    <!-- search & filter bar -->
    <form method="GET" action="{{ route('admin.products') }}" class="bg-white shadow rounded mb-4 px-4 py-3 flex flex-wrap gap-3 items-center">
      <input
        type="text"
        name="q"
        value="{{ request('q') }}"
        placeholder="Hľadať produkty..."
        class="flex-1 min-w-45 border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:border-brand-dark"
      />
      <select name="category" class="border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:border-brand-dark bg-white">
        <option value="">Všetky kategórie</option>
        @foreach ($categories as $cat)
          <option value="{{ $cat->name }}" @selected(request('category') === $cat->name)>{{ $cat->name }}</option>
        @endforeach
      </select>
      <button type="submit" class="bg-brand-dark hover:bg-brand-accent text-white text-sm px-4 py-2 transition-colors">
        Hľadať
      </button>
    </form>

    <!-- product table -->
    <div class="bg-white rounded shadow overflow-x-auto">
      <table class="w-full text-sm">
        <thead class="border-b border-gray-200 text-gray-500 text-xs uppercase tracking-wider">
          <tr>
            <th class="px-4 py-3 text-left font-semibold hidden wide:table-cell">Fotografia</th>
            <th class="px-4 py-3 text-left font-semibold">Názov</th>
            <th class="px-4 py-3 text-left font-semibold hidden wide:table-cell">Kategória</th>
            <th class="px-4 py-3 text-left font-semibold">Cena</th>
            <th class="px-4 py-3 text-left font-semibold whitespace-nowrap">Sklad</th>
            <th class="px-4 py-3 text-left font-semibold hidden wide:table-cell">Zvýraznený</th>
            <th class="px-4 py-3 text-left font-semibold">Akcie</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
          @forelse ($products as $p)
            @php $stock = \App\Models\ProductVariant::where('product_id', $p->id)->sum('stock_quantity'); @endphp
            <tr class="hover:bg-gray-50 transition-colors">
              <td class="px-4 py-3 hidden wide:table-cell">
                <div class="w-12 h-16 bg-gray-200 rounded overflow-hidden relative">
                  @if ($p->image_url)
                    <img src="{{ $p->image_url }}" class="w-full absolute top-1/2 -translate-y-1/2 object-cover" alt="{{ $p->name }}">
                  @endif
                </div>
              </td>
              <td class="px-4 py-3">
                <p class="font-semibold text-brand-dark">{{ $p->name }}</p>
                <p class="text-xs text-gray-400">{{ $p->brand_name }}</p>
              </td>
              <td class="px-4 py-3 text-gray-600 hidden wide:table-cell">{{ $p->subcategory_name }}</td>
              <td class="px-4 py-3 font-semibold">
                @if ($p->min_price !== null)
                  {{ number_format((float) $p->min_price, 2, ',', ' ') }} €
                @else
                  <span class="text-gray-400">Bez variantov</span>
                @endif
              </td>
              <td class="px-4 py-3">
                @if ($stock > 5)
                  <span class="bg-green-100 text-green-700 text-xs font-semibold px-2 py-0.5 rounded whitespace-nowrap">{{ $stock }} ks</span>
                @elseif ($stock > 0)
                  <span class="bg-yellow-100 text-yellow-700 text-xs font-semibold px-2 py-0.5 rounded whitespace-nowrap">{{ $stock }} ks</span>
                @else
                  <span class="bg-red-100 text-red-600 text-xs font-semibold px-2 py-0.5 rounded whitespace-nowrap">0 ks</span>
                @endif
              </td>
              <td class="px-4 py-3 hidden wide:table-cell">
                @if ($p->is_featured)
                  <span class="bg-yellow-100 text-yellow-700 text-xs font-semibold px-2 py-0.5 rounded">Áno</span>
                @else
                  <span class="text-gray-400 text-xs">Nie</span>
                @endif
              </td>
              <td class="px-4 py-3">
                <div class="flex items-center gap-2">
                  <button
                    type="button"
                    onclick="openEditModal({{ $p->id }}, {{ json_encode($p->name) }})"
                    title="Upraviť"
                    class="opacity-50 hover:opacity-100 transition-opacity"
                  >
                    <img src="{{ asset('icons/edit.svg') }}" class="w-5 h-5" alt="Upraviť" />
                  </button>
                  <button
                    type="button"
                    onclick="openDeleteModal({{ $p->id }}, {{ json_encode($p->name) }})"
                    title="Vymazať"
                    class="opacity-50 hover:opacity-100 transition-opacity"
                  >
                    <img src="{{ asset('icons/trash.svg') }}" class="w-5 h-5" alt="Vymazať" />
                  </button>
                </div>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="7" class="px-4 py-8 text-center text-gray-400 text-sm">Žiadne produkty.</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    @if ($products->hasPages())
      <div class="mt-4">{{ $products->links() }}</div>
    @endif

  </div>


  <!-- modal: add product -->
  <div id="modal-add" class="fixed inset-0 bg-black/40 z-50 items-start justify-center px-4 py-8 overflow-y-auto">
    <div class="bg-white w-full max-w-2xl mx-auto shadow-xl relative my-auto">
      <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200">
        <h2 class="text-lg font-bold">Pridať produkt</h2>
        <a href="#!" class="text-gray-400 hover:text-brand-dark transition-colors text-xl leading-none">&#x2715;</a>
      </div>
      <form id="form-add" action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data" class="px-6 py-6 space-y-5">
        @csrf

        @if ($errors->any() && old('_method') !== 'PUT')
          <div class="bg-red-50 border border-red-200 text-red-700 text-sm px-4 py-3 rounded">
            <p class="font-semibold mb-1">Produkt sa neulozil.</p>
            <ul class="list-disc list-inside space-y-0.5">
              @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
              @endforeach
            </ul>
          </div>
        @endif

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
          <div class="sm:col-span-2">
            <label class="block text-sm font-medium mb-1.5">Názov <span class="text-red-500">*</span></label>
            <input type="text" name="name" value="{{ old('name') }}" placeholder="Názov produktu" required class="w-full border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:border-brand-dark" />
          </div>
          <div class="sm:col-span-2">
            <label class="block text-sm font-medium mb-1.5">Opis <span class="text-red-500">*</span></label>
            <textarea name="description" rows="3" placeholder="Popis produktu..." required class="w-full border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:border-brand-dark resize-none">{{ old('description') }}</textarea>
          </div>
          <div>
            <label class="block text-sm font-medium mb-1.5">Kategória <span class="text-red-500">*</span></label>
            <select name="category_id" id="add-category_id" required class="w-full border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:border-brand-dark bg-white">
              <option value="">Vybrať kategóriu</option>
              @foreach ($categories as $cat)
                <option value="{{ $cat->id }}" @selected((string) old('category_id') === (string) $cat->id)>{{ $cat->name }}</option>
              @endforeach
            </select>
          </div>
          <div>
            <label class="block text-sm font-medium mb-1.5">Podkategória</label>
            <select name="subcategory_id" id="add-subcategory_id" class="w-full border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:border-brand-dark bg-white">
              <option value="">Vybrať podkategóriu</option>
              @foreach ($subcategories as $sub)
                <option value="{{ $sub->id }}" @selected((string) old('subcategory_id') === (string) $sub->id)>{{ $sub->name }}</option>
              @endforeach
            </select>
          </div>
          <div>
            <label class="block text-sm font-medium mb-1.5">Značka</label>
            <select name="brand_id" class="w-full border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:border-brand-dark bg-white">
              <option value="">Vybrať značku</option>
              @foreach ($brands as $brand)
                <option value="{{ $brand->id }}" @selected((string) old('brand_id') === (string) $brand->id)>{{ $brand->name }}</option>
              @endforeach
            </select>
          </div>
          <div>
            <label class="block text-sm font-medium mb-1.5">Materiál</label>
            <select name="material_id" class="w-full border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:border-brand-dark bg-white">
              <option value="">Vybrať materiál</option>
              @foreach ($materials as $mat)
                <option value="{{ $mat->id }}" @selected((string) old('material_id') === (string) $mat->id)>{{ $mat->name }}</option>
              @endforeach
            </select>
          </div>
          <div class="sm:col-span-2">
            <label class="flex items-center gap-2.5 cursor-pointer">
              <input type="checkbox" name="is_featured" value="1" @checked(old('is_featured')) class="w-4 h-4 accent-brand-dark" />
              <span class="text-sm font-medium">Zvýraznený produkt</span>
            </label>
          </div>
        </div>

        <div class="border-t border-gray-100 pt-4" id="add-images-section">
          <label class="block text-sm font-bold mb-2">Fotografie <span class="text-red-500">*</span></label>

          <!-- image tray (shared component rendered by JS) -->
          <div id="add-image-tray" class="flex flex-wrap gap-2 mb-3 min-h-8"></div>
          <p id="add-images-validation-message" class="hidden text-xs text-red-600 mb-2"></p>

          <!-- library picker -->
          <details class="mb-3">
            <summary class="text-xs font-semibold text-gray-600 cursor-pointer select-none mb-1">Vybrať z knižnice obrázkov</summary>
            @if (count($libraryImages) > 0)
              <div class="grid grid-cols-3 sm:grid-cols-4 gap-2 max-h-44 overflow-y-auto border border-gray-200 rounded p-2 mt-2" id="add-library-grid">
                @foreach ($libraryImages as $image)
                  <label class="cursor-pointer block relative" data-lib-url="{{ $image['url'] }}" data-lib-path="{{ $image['path'] }}">
                    <input type="checkbox" data-lib="1" value="{{ $image['path'] }}" class="peer sr-only add-lib-checkbox" />
                    <span class="absolute top-1 right-1 hidden w-5 h-5 items-center justify-center rounded-full bg-brand-dark text-white text-[11px] font-bold peer-checked:flex">&#10003;</span>
                    <span class="block border border-gray-200 rounded p-1 transition-colors peer-checked:border-brand-dark peer-checked:ring-1 peer-checked:ring-brand-dark peer-checked:bg-gray-50">
                      <img src="{{ $image['url'] }}" alt="{{ $image['name'] }}" class="w-full h-20 object-cover rounded">
                      <span class="block text-[10px] text-gray-500 truncate mt-1">{{ $image['name'] }}</span>
                    </span>
                  </label>
                @endforeach
              </div>
            @else
              <p class="text-xs text-gray-400 mt-1">V priečinku public/images/products nie sú dostupné žiadne obrázky.</p>
            @endif
          </details>

          <!-- upload -->
          <p class="text-xs text-gray-500 mb-1">Nahrať nové fotografie</p>
          <input type="file" id="add-file-input" accept="image/*" multiple
            class="w-full border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:border-brand-dark file:mr-3 file:text-xs file:border-0 file:bg-gray-100 file:px-2 file:py-1 file:cursor-pointer"
          />

          <!-- external URL -->
          <p class="text-xs text-gray-500 mt-3 mb-1">Alebo vložiť URL externého obrázka</p>
          <div class="flex gap-2">
            <input type="text" id="add-url-input" placeholder="https://example.com/image.jpg"
              class="flex-1 border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:border-brand-dark" />
            <button type="button" onclick="addExternalUrl('add')" class="px-3 py-2 bg-gray-100 hover:bg-gray-200 text-sm border border-gray-300 transition-colors">Pridať</button>
          </div>

          <p class="text-xs text-gray-400 mt-2">Poradie: ťahaním presúvajte obrázky. Hviezda = primárny. Povolené formáty: jpg, jpeg, png, webp. Max. 2 MB.</p>
        </div>

        <div class="border-t border-gray-100 pt-4">
          <div class="flex items-center justify-between mb-2">
            <label class="text-sm font-bold">Varianty <span class="text-red-500">*</span> <span class="text-gray-400 font-normal text-xs">(farba × veľkosť × cena × sklad)</span></label>
            <button type="button" onclick="showVariantAdder('form-add')" class="text-xs font-semibold text-brand-dark hover:underline">+ Pridať varianty</button>
          </div>
          <div class="border border-gray-200 rounded overflow-hidden">
            <table class="w-full text-xs">
              <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                  <th class="px-3 py-2 text-left font-semibold text-gray-500">Farba</th>
                  <th class="px-3 py-2 text-left font-semibold text-gray-500">Veľkosť</th>
                  <th class="px-3 py-2 text-left font-semibold text-gray-500">Cena</th>
                  <th class="px-3 py-2 text-left font-semibold text-gray-500">Sklad</th>
                  <th class="px-3 py-2"></th>
                </tr>
              </thead>
              <tbody id="add-variants-body">
                @if (is_array(old('variants')) && count(old('variants')) > 0)
                  @foreach (old('variants') as $idx => $variant)
                    @php
                      $oldColorId = (int) data_get($variant, 'color_id');
                      $oldColor = $colors->firstWhere('id', $oldColorId);
                    @endphp
                    <tr class="border-b border-gray-100">
                      <td class="px-3 py-2">{{ $oldColor?->name ?? ('Farba #' . $oldColorId) }}</td>
                      <td class="px-3 py-2">{{ data_get($variant, 'size') }}</td>
                      <td class="px-3 py-2">{{ data_get($variant, 'price') }} €</td>
                      <td class="px-3 py-2">{{ data_get($variant, 'stock') }}</td>
                      <td class="px-3 py-2 text-right">
                        <button type="button" onclick="this.closest('tr').remove()" class="text-gray-400 hover:text-red-500">&#x2715;</button>
                        <input type="hidden" name="variants[{{ $idx }}][color_id]" value="{{ data_get($variant, 'color_id') }}" />
                        <input type="hidden" name="variants[{{ $idx }}][size]" value="{{ data_get($variant, 'size') }}" />
                        <input type="hidden" name="variants[{{ $idx }}][price]" value="{{ data_get($variant, 'price') }}" />
                        <input type="hidden" name="variants[{{ $idx }}][stock]" value="{{ data_get($variant, 'stock') }}" />
                      </td>
                    </tr>
                  @endforeach
                @else
                  <tr class="text-gray-400 italic" id="add-empty-row">
                    <td colspan="5" class="px-3 py-3 text-center text-xs">Žiadne varianty — kliknite na + Pridať varianty</td>
                  </tr>
                @endif
              </tbody>
            </table>
          </div>
        </div>

        <div class="flex justify-end gap-3 pt-2 border-t border-gray-100">
          <a href="#!" class="px-5 py-2.5 border border-gray-300 text-sm font-medium hover:bg-gray-50 transition-colors">Zrušiť</a>
          <button type="submit" class="bg-brand-dark hover:bg-brand-accent text-white text-sm font-bold tracking-widest uppercase px-5 py-2.5 transition-colors">Uložiť produkt</button>
        </div>
      </form>
    </div>
  </div>


  <!-- modal: edit product -->
  <div id="modal-edit" class="fixed inset-0 bg-black/40 z-50 items-start justify-center px-4 py-8 overflow-y-auto">
    <div class="bg-white w-full max-w-2xl mx-auto shadow-xl relative my-auto">
      <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200">
        <h2 class="text-lg font-bold">Upraviť produkt</h2>
        <a href="#!" class="text-gray-400 hover:text-brand-dark transition-colors text-xl leading-none">&#x2715;</a>
      </div>
      <form id="form-edit" action="" method="POST" enctype="multipart/form-data" class="px-6 py-6 space-y-5">
        @csrf
        @method('PUT')
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
          <div class="sm:col-span-2">
            <label class="block text-sm font-medium mb-1.5">Názov <span class="text-red-500">*</span></label>
            <input type="text" name="name" id="edit-name" required class="w-full border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:border-brand-dark" />
          </div>
          <div class="sm:col-span-2">
            <label class="block text-sm font-medium mb-1.5">Opis <span class="text-red-500">*</span></label>
            <textarea name="description" id="edit-description" rows="3" required class="w-full border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:border-brand-dark resize-none"></textarea>
          </div>
          <div>
            <label class="block text-sm font-medium mb-1.5">Kategória <span class="text-red-500">*</span></label>
            <select name="category_id" id="edit-category_id" required class="w-full border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:border-brand-dark bg-white">
              <option value="">Vybrať kategóriu</option>
              @foreach ($categories as $cat)
                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
              @endforeach
            </select>
          </div>
          <div>
            <label class="block text-sm font-medium mb-1.5">Podkategória</label>
            <select name="subcategory_id" id="edit-subcategory_id" class="w-full border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:border-brand-dark bg-white">
              <option value="">Vybrať podkategóriu</option>
              @foreach ($subcategories as $sub)
                <option value="{{ $sub->id }}">{{ $sub->name }}</option>
              @endforeach
            </select>
          </div>
          <div>
            <label class="block text-sm font-medium mb-1.5">Značka</label>
            <select name="brand_id" id="edit-brand_id" class="w-full border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:border-brand-dark bg-white">
              <option value="">Vybrať značku</option>
              @foreach ($brands as $brand)
                <option value="{{ $brand->id }}">{{ $brand->name }}</option>
              @endforeach
            </select>
          </div>
          <div>
            <label class="block text-sm font-medium mb-1.5">Materiál</label>
            <select name="material_id" id="edit-material_id" class="w-full border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:border-brand-dark bg-white">
              <option value="">Vybrať materiál</option>
              @foreach ($materials as $mat)
                <option value="{{ $mat->id }}">{{ $mat->name }}</option>
              @endforeach
            </select>
          </div>
          <div class="sm:col-span-2">
            <label class="flex items-center gap-2.5 cursor-pointer">
              <input type="checkbox" name="is_featured" id="edit-is_featured" value="1" class="w-4 h-4 accent-brand-dark" />
              <span class="text-sm font-medium">Zvýraznený produkt</span>
            </label>
          </div>
        </div>

        <div class="border-t border-gray-100 pt-4" id="edit-images-section">

          <label class="block text-sm font-bold mb-2">Fotografie</label>

          <!-- image tray -->
          <div id="edit-image-tray" class="flex flex-wrap gap-2 mb-3 min-h-8"></div>
          <p id="edit-images-validation-message" class="hidden text-xs text-red-600 mb-2"></p>

          <!-- library picker -->
          <details class="mb-3">
            <summary class="text-xs font-semibold text-gray-600 cursor-pointer select-none mb-1">Vybrať z knižnice obrázkov</summary>
            @if (count($libraryImages) > 0)
              <div class="grid grid-cols-3 sm:grid-cols-4 gap-2 max-h-44 overflow-y-auto border border-gray-200 rounded p-2 mt-2" id="edit-library-grid">
                @foreach ($libraryImages as $image)
                  <label class="cursor-pointer block relative" data-lib-url="{{ $image['url'] }}" data-lib-path="{{ $image['path'] }}">
                    <input type="checkbox" data-lib="1" value="{{ $image['path'] }}" class="peer sr-only edit-lib-checkbox" />
                    <span class="absolute top-1 right-1 hidden w-5 h-5 items-center justify-center rounded-full bg-brand-dark text-white text-[11px] font-bold peer-checked:flex">&#10003;</span>
                    <span class="block border border-gray-200 rounded p-1 transition-colors peer-checked:border-brand-dark peer-checked:ring-1 peer-checked:ring-brand-dark peer-checked:bg-gray-50">
                      <img src="{{ $image['url'] }}" alt="{{ $image['name'] }}" class="w-full h-20 object-cover rounded">
                      <span class="block text-[10px] text-gray-500 truncate mt-1">{{ $image['name'] }}</span>
                    </span>
                  </label>
                @endforeach
              </div>
            @else
              <p class="text-xs text-gray-400 mt-1">V priečinku public/images/products nie sú dostupné žiadne obrázky.</p>
            @endif
          </details>

          <!-- upload -->
          <p class="text-xs text-gray-500 mb-1">Nahrať nové fotografie</p>
          <input type="file" id="edit-file-input" accept="image/*" multiple
            class="w-full border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:border-brand-dark file:mr-3 file:text-xs file:border-0 file:bg-gray-100 file:px-2 file:py-1 file:cursor-pointer"
          />

          <!-- external URL -->
          <p class="text-xs text-gray-500 mt-3 mb-1">Alebo vložiť URL externého obrázka</p>
          <div class="flex gap-2">
            <input type="text" id="edit-url-input" placeholder="https://example.com/image.jpg"
              class="flex-1 border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:border-brand-dark" />
            <button type="button" onclick="addExternalUrl('edit')" class="px-3 py-2 bg-gray-100 hover:bg-gray-200 text-sm border border-gray-300 transition-colors">Pridať</button>
          </div>

          <p class="text-xs text-gray-400 mt-2">Poradie: ťahaním presúvajte obrázky. Hviezda = primárny. Povolené formáty: jpg, jpeg, png, webp. Max. 2 MB.</p>
        </div>

        <div class="border-t border-gray-100 pt-4">
          <div class="flex items-center justify-between mb-2">
            <label class="text-sm font-bold">Varianty</label>
            <button type="button" onclick="showVariantAdder('form-edit')" class="text-xs font-semibold text-brand-dark hover:underline">+ Pridať varianty</button>
          </div>
          <div class="border border-gray-200 rounded overflow-hidden">
            <table class="w-full text-xs">
              <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                  <th class="px-3 py-2 text-left font-semibold text-gray-500">Farba</th>
                  <th class="px-3 py-2 text-left font-semibold text-gray-500">Veľkosť</th>
                  <th class="px-3 py-2 text-left font-semibold text-gray-500">Cena</th>
                  <th class="px-3 py-2 text-left font-semibold text-gray-500">Sklad</th>
                  <th class="px-3 py-2"></th>
                </tr>
              </thead>
              <tbody id="edit-variants-body">
                <tr class="text-gray-400 italic" id="edit-empty-row">
                  <td colspan="5" class="px-3 py-3 text-center text-xs">Žiadne varianty</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>

        <div class="flex justify-end gap-3 pt-2 border-t border-gray-100">
          <a href="#!" class="px-5 py-2.5 border border-gray-300 text-sm font-medium hover:bg-gray-50 transition-colors">Zrušiť</a>
          <button type="submit" class="bg-brand-dark hover:bg-brand-accent text-white text-sm font-bold tracking-widest uppercase px-5 py-2.5 transition-colors">Uložiť zmeny</button>
        </div>
      </form>
    </div>
  </div>


  <!-- modal: delete product -->
  <div id="modal-delete" class="fixed inset-0 bg-black/40 z-50 items-center justify-center px-4">
    <div class="bg-white w-full max-w-sm shadow-xl">
      <div class="px-6 py-6">
        <h2 class="text-lg font-bold mb-2">Vymazať produkt</h2>
        <p class="text-sm text-gray-600 mb-6">Naozaj chcete vymazať produkt <strong id="delete-product-name"></strong>? Táto akcia je nevratná.</p>
        <form id="form-delete" method="POST" action="">
          @csrf
          @method('DELETE')
          <div class="flex justify-end gap-3">
            <a href="#!" class="px-4 py-2.5 border border-gray-300 text-sm font-medium hover:bg-gray-50 transition-colors">Zrušiť</a>
            <button type="submit" class="px-4 py-2.5 bg-red-600 hover:bg-red-700 text-white text-sm font-bold transition-colors">Vymazať</button>
          </div>
        </form>
      </div>
    </div>
  </div>


  <!-- variant adder overlay -->
  <div id="modal-variant-adder" class="fixed inset-0 bg-black/40 hidden items-center justify-center px-4 py-8 overflow-y-auto" style="z-index: 70;">
    <div class="bg-white w-full max-w-lg mx-auto shadow-xl my-auto">
      <div class="px-6 py-4 border-b border-gray-200">
        <div class="flex items-center justify-between gap-3">
          <h2 class="text-lg font-bold">Pridať varianty</h2>
          <button type="button" onclick="closeVariantAdder()" class="text-gray-400 hover:text-brand-dark transition-colors text-xl leading-none">&#x2715;</button>
        </div>
        <p id="variant-validation-message" class="hidden mt-1 text-sm text-red-600 font-medium" aria-live="polite"></p>
      </div>
      <div class="px-6 py-6 space-y-5">
        <div>
          <label class="block text-sm font-medium mb-2">Farby <span class="text-red-500">*</span></label>
          <div class="flex flex-wrap gap-2" id="va-colors">
            @foreach ($colors as $color)
              <div>
                <input type="checkbox" id="va-color-{{ $color->id }}" name="va_color" value="{{ $color->id }}" data-name="{{ $color->name }}" class="peer sr-only" />
                <label for="va-color-{{ $color->id }}" class="flex items-center gap-2 cursor-pointer border border-gray-200 px-3 py-1.5 text-sm hover:border-brand-dark transition-colors peer-checked:border-brand-dark peer-checked:bg-brand-dark peer-checked:text-white">
                  <span class="inline-block w-3 h-3 rounded-full border border-gray-300 shrink-0 peer-checked:border-white" style="background:{{ $color->hex_code }}"></span>
                  {{ $color->name }}
                </label>
              </div>
            @endforeach
          </div>
        </div>
        <div>
          <label class="block text-sm font-medium mb-2">Veľkosti <span class="text-red-500">*</span></label>
          <p class="text-xs text-gray-400 mb-1.5">Oblečenie / doplnky</p>
          <div class="flex flex-wrap gap-2 mb-3">
            @foreach (['XS', 'S', 'M', 'L', 'XL', 'XXL'] as $size)
              <div>
                <input type="checkbox" id="va-size-clothes-{{ $size }}" name="va_size" value="{{ $size }}" class="peer sr-only" />
                <label for="va-size-clothes-{{ $size }}" class="flex items-center justify-center cursor-pointer border border-gray-200 w-12 h-10 text-sm font-medium hover:border-brand-dark transition-colors peer-checked:border-brand-dark peer-checked:bg-brand-dark peer-checked:text-white">
                  {{ $size }}
                </label>
              </div>
            @endforeach
          </div>
          <p class="text-xs text-gray-400 mb-1.5">Topánky (EU)</p>
          <div class="flex flex-wrap gap-2 max-h-28 overflow-y-auto">
            @foreach (range(20, 50) as $size)
              <div>
                <input type="checkbox" id="va-size-shoes-{{ $size }}" name="va_size" value="{{ $size }}" class="peer sr-only" />
                <label for="va-size-shoes-{{ $size }}" class="flex items-center justify-center cursor-pointer border border-gray-200 w-10 h-10 text-xs font-medium hover:border-brand-dark transition-colors peer-checked:border-brand-dark peer-checked:bg-brand-dark peer-checked:text-white">
                  {{ $size }}
                </label>
              </div>
            @endforeach
          </div>
        </div>
        <div class="grid grid-cols-2 gap-4 pt-1 border-t border-gray-100">
          <div>
            <label class="block text-sm font-medium mb-1.5">Cena (€) <span class="text-red-500">*</span></label>
            <input type="number" id="va-price" min="0" step="0.01" placeholder="0.00" class="w-full border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:border-brand-dark" />
          </div>
          <div>
            <label class="block text-sm font-medium mb-1.5">Sklad (ks) <span class="text-red-500">*</span></label>
            <input type="number" id="va-stock" min="0" placeholder="0" class="w-full border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:border-brand-dark" />
          </div>
        </div>
      </div>
      <div class="flex justify-end gap-3 px-6 py-4 border-t border-gray-100">
        <button type="button" onclick="closeVariantAdder()" class="px-4 py-2.5 border border-gray-300 text-sm font-medium hover:bg-gray-50 transition-colors">Zrušiť</button>
        <button type="button" onclick="commitVariants()" class="bg-brand-dark hover:bg-brand-accent text-white text-sm font-bold tracking-widest uppercase px-4 py-2.5 transition-colors">Pridať varianty</button>
      </div>
    </div>
  </div>

  <script>
    // ─── constants ────────────────────────────────────────────────────────────
    const MAX_IMAGE_BYTES = 2 * 1024 * 1024;
    let currentFormId = null;
    const variantIndex = { 'form-add': {{ is_array(old('variants')) ? count(old('variants')) : 0 }}, 'form-edit': 0 };
    const addVariantsEmptyRowHtml = '<tr class="text-gray-400 italic" id="add-empty-row"><td colspan="5" class="px-3 py-3 text-center text-xs">Žiadne varianty — kliknite na + Pridať varianty</td></tr>';

    // image source values: 'existing', 'library_existing', 'upload', 'library', 'external'
    const imageTray = { add: [], edit: [] };
    const pendingFiles = { add: new DataTransfer(), edit: new DataTransfer() };

    function normalizeImagePath(path) {
      if (!path) return '';
      return String(path).replace(/\\/g, '/').replace(/^\/+/, '');
    }

    function escHtml(str) {
      return String(str).replace(/&/g,'&amp;').replace(/"/g,'&quot;').replace(/</g,'&lt;').replace(/>/g,'&gt;');
    }

    // ─── tray rendering ───────────────────────────────────────────────────────
    function renderTray(scope) {
      const tray = document.getElementById(`${scope}-image-tray`);
      tray.innerHTML = '';

      imageTray[scope].forEach((item, idx) => {
        const card = document.createElement('div');
        card.className = 'relative group w-20 cursor-grab active:cursor-grabbing select-none';
        card.dataset.idx = idx;
        card.draggable = true;

        const starClass = item.isPrimary ? 'text-yellow-400' : 'text-gray-300 group-hover:text-gray-400';
        const borderClass = item.isPrimary ? 'border-brand-dark ring-2 ring-brand-dark' : 'border-gray-200';

        card.innerHTML = `
          <img src="${escHtml(item.url)}" class="w-20 h-24 object-cover border ${borderClass} rounded" />
          <button type="button" title="Nastaviť ako primárny"
            onclick="setPrimary('${scope}', ${idx})"
            class="absolute top-0.5 left-0.5 ${starClass} transition-colors text-lg leading-none drop-shadow">&#9733;</button>
          <button type="button" title="Odstrániť"
            onclick="removeFromTray('${scope}', ${idx})"
            class="absolute top-0.5 right-0.5 text-gray-300 group-hover:text-red-500 transition-colors text-sm leading-none font-bold drop-shadow">&#x2715;</button>
          ${item.isPrimary ? '<span class="absolute bottom-0.5 left-0.5 text-[9px] bg-brand-dark text-white px-1 rounded">primárny</span>' : ''}
        `;

        card.addEventListener('dragstart', e => {
          e.dataTransfer.setData('text/plain', String(idx));
          e.dataTransfer.effectAllowed = 'move';
        });
        card.addEventListener('dragover', e => {
          e.preventDefault();
          e.dataTransfer.dropEffect = 'move';
          card.classList.add('opacity-50');
        });
        card.addEventListener('dragleave', () => card.classList.remove('opacity-50'));
        card.addEventListener('drop', e => {
          e.preventDefault();
          card.classList.remove('opacity-50');
          const fromIdx = parseInt(e.dataTransfer.getData('text/plain'), 10);
          const toIdx = parseInt(card.dataset.idx, 10);
          if (fromIdx !== toIdx) {
            const arr = imageTray[scope];
            const [moved] = arr.splice(fromIdx, 1);
            arr.splice(toIdx, 0, moved);
            renderTray(scope);
          }
        });

        tray.appendChild(card);
      });
    }

    // ─── tray mutations ───────────────────────────────────────────────────────
    function setPrimary(scope, idx) {
      imageTray[scope].forEach((item, i) => { item.isPrimary = (i === idx); });
      renderTray(scope);
    }

    function removeFromTray(scope, idx) {
      const item = imageTray[scope][idx];
      imageTray[scope].splice(idx, 1);

      if (item.isPrimary && imageTray[scope].length > 0) {
        imageTray[scope][0].isPrimary = true;
      }

      if (item.source === 'upload') {
        rebuildFileList(scope);
      }

      if (item.source === 'library' || item.source === 'library_existing') {
        const cb = document.querySelector(`#${scope}-library-grid input[value="${CSS.escape(item.path)}"]`);
        if (cb) {
          cb.dataset.skipChange = '1';
          cb.checked = false;
          delete cb.dataset.skipChange;
        }
      }

      renderTray(scope);
    }

    function addToTray(scope, item) {
      const key = item.path || item.url;
      if (imageTray[scope].some(i => (i.path || i.url) === key)) return;
      if (!imageTray[scope].some(i => i.isPrimary)) {
        item.isPrimary = true;
      }
      imageTray[scope].push(item);
      renderTray(scope);
    }

    function rebuildFileList(scope) {
      pendingFiles[scope] = new DataTransfer();
      imageTray[scope].filter(i => i.source === 'upload').forEach(i => {
        if (i.file) pendingFiles[scope].items.add(i.file);
      });
      const fileInput = document.getElementById(`${scope}-file-input`);
      if (fileInput) fileInput.files = pendingFiles[scope].files;
    }

    // ─── sync hidden inputs on form submit ────────────────────────────────────
    function buildHiddenInputs(scope) {
      const form = document.getElementById(`form-${scope}`);
      form.querySelectorAll('[data-tray-generated]').forEach(el => el.remove());

      const items = imageTray[scope];

      const mk = (name, value) => {
        const el = document.createElement('input');
        el.type = 'hidden';
        el.name = name;
        el.value = value;
        el.dataset.trayGenerated = '1';
        form.appendChild(el);
      };

      const newItems = items.filter(i => i.source === 'upload' || i.source === 'library' || i.source === 'external');
      const primaryItem = items.find(i => i.isPrimary);
      const newFileList = new DataTransfer();
      let uploadIdx = 0;

      newItems.forEach((item, ni) => {
        mk(`new_images[${ni}][type]`, item.source);
        if (item.source === 'upload') {
          mk(`new_images[${ni}][value]`, uploadIdx++);
          if (item.file) newFileList.items.add(item.file);
        } else if (item.source === 'library') {
          mk(`new_images[${ni}][value]`, item.path);
        } else {
          mk(`new_images[${ni}][value]`, item.url);
        }
      });

      items.forEach(item => {
        if (item.source === 'existing' || item.source === 'library_existing') {
          mk('keep_image_ids[]', item.id);
          mk('image_order[]', item.id);
        }
      });

      // primary
      if (primaryItem) {
        if (primaryItem.source === 'existing' || primaryItem.source === 'library_existing') {
          mk('primary_image_id', primaryItem.id);
        } else {
          const primaryNewIdx = newItems.indexOf(primaryItem);
          if (primaryNewIdx !== -1) mk('primary_new_index', primaryNewIdx);
        }
      }

      const fileInput = document.getElementById(`${scope}-file-input`);
      if (fileInput) fileInput.files = newFileList.files;
      pendingFiles[scope] = newFileList;
    }

    // ─── file input handler ───────────────────────────────────────────────────
    function setupFileInput(scope) {
      const input = document.getElementById(`${scope}-file-input`);
      if (!input) return;
      input.addEventListener('change', () => {
        const files = Array.from(input.files);
        const msgEl = document.getElementById(`${scope}-images-validation-message`);
        const tooLarge = files.find(f => f.size > MAX_IMAGE_BYTES);
        if (tooLarge) {
          msgEl.textContent = `Súbor ${tooLarge.name} je väčší ako 2 MB.`;
          msgEl.classList.remove('hidden');
          input.value = '';
          return;
        }
        msgEl.classList.add('hidden');
        files.forEach(file => {
          const reader = new FileReader();
          reader.onload = e => {
            pendingFiles[scope].items.add(file);
            input.files = pendingFiles[scope].files;
            addToTray(scope, { url: e.target.result, path: null, isPrimary: false, source: 'upload', file });
          };
          reader.readAsDataURL(file);
        });
        input.value = '';
      });
    }

    // ─── library picker handler ───────────────────────────────────────────────
    function setupLibraryPicker(scope) {
      document.querySelectorAll(`.${scope}-lib-checkbox`).forEach(cb => {
        cb.addEventListener('change', () => {
          // skip if the change was triggered programmatically by removeFromTray
          if (cb.dataset.skipChange) return;

          const label = cb.closest('label');
          const path = cb.value;
          const url = label.dataset.libUrl;
          if (cb.checked) {
            if (!imageTray[scope].some(i => i.path === path)) {
              addToTray(scope, { url, path, isPrimary: false, source: 'library' });
            }
          } else {
            const idx = imageTray[scope].findIndex(i => i.path === path);
            if (idx !== -1) {
              const item = imageTray[scope][idx];
              item.source = 'upload'; // temporarily skip checkbox uncheck logic in removeFromTray
              removeFromTray(scope, idx);
            }
          }
        });
      });
    }

    // ─── external URL ─────────────────────────────────────────────────────────
    function addExternalUrl(scope) {
      const input = document.getElementById(`${scope}-url-input`);
      const url = (input.value || '').trim();
      const msgEl = document.getElementById(`${scope}-images-validation-message`);
      if (!url.match(/^https?:\/\/.+/i)) {
        msgEl.textContent = 'Zadajte platnú URL adresu (začínajúcu https://).';
        msgEl.classList.remove('hidden');
        return;
      }
      msgEl.classList.add('hidden');
      addToTray(scope, { url, path: url, isPrimary: false, source: 'external' });
      input.value = '';
    }

    // ─── add modal ────────────────────────────────────────────────────────────
    function openAddModal(resetForm = true) {
      if (resetForm) resetAddFormState();
      window.location.hash = 'modal-add';
    }

    function resetAddFormState() {
      const form = document.getElementById('form-add');
      form.reset();
      imageTray.add = [];
      pendingFiles.add = new DataTransfer();
      const fileInput = document.getElementById('add-file-input');
      if (fileInput) fileInput.value = '';
      document.getElementById('add-url-input').value = '';
      document.querySelectorAll('.add-lib-checkbox').forEach(cb => cb.checked = false);
      renderTray('add');
      document.getElementById('add-variants-body').innerHTML = addVariantsEmptyRowHtml;
      variantIndex['form-add'] = 0;
      document.getElementById('add-images-validation-message').textContent = '';
      document.getElementById('add-images-validation-message').classList.add('hidden');
      hideVariantValidationMessage();
      syncSubcategoryState('add-category_id', 'add-subcategory_id');
      form.querySelectorAll('[data-tray-generated]').forEach(el => el.remove());
    }

    document.getElementById('form-add').addEventListener('submit', function (event) {
      if (imageTray.add.length === 0) {
        event.preventDefault();
        const msg = document.getElementById('add-images-validation-message');
        msg.textContent = 'Pridajte alebo vyberte aspoň jednu fotografiu.';
        msg.classList.remove('hidden');
        return;
      }
      const hasVariants = this.querySelectorAll('input[name*="[color_id]"]').length > 0;
      if (!hasVariants) {
        event.preventDefault();
        showVariantAdder('form-add', 'Varianty sú povinné. Pridajte aspoň jeden variant.');
        return;
      }
      buildHiddenInputs('add');
    });

    // ─── edit modal ───────────────────────────────────────────────────────────
    function openEditModal(id, name) {
      document.getElementById('edit-name').value = name;
      const form = document.getElementById('form-edit');
      form.action = `/admin/products/${id}`;
      form.querySelectorAll('[data-tray-generated]').forEach(el => el.remove());

      imageTray.edit = [];
      pendingFiles.edit = new DataTransfer();

      document.querySelectorAll('.edit-lib-checkbox').forEach(cb => {
        cb.dataset.skipChange = '1';
        cb.checked = false;
        delete cb.dataset.skipChange;
      });

      renderTray('edit');
      document.getElementById('edit-variants-body').innerHTML =
        '<tr class="text-gray-400 italic" id="edit-empty-row"><td colspan="5" class="px-3 py-3 text-center text-xs">Načítavam...</td></tr>';
      document.getElementById('edit-description').value = '';
      variantIndex['form-edit'] = 0;
      window.location.hash = 'modal-edit';

      fetch(`/admin/products/${id}/data`)
        .then(r => r.json())
        .then(data => {
          document.getElementById('edit-description').value = data.description || '';
          setSelectVal('edit-category_id', data.category_id);
          setSelectVal('edit-subcategory_id', data.subcategory_id);
          syncSubcategoryState('edit-category_id', 'edit-subcategory_id');
          setSelectVal('edit-brand_id', data.brand_id);
          setSelectVal('edit-material_id', data.material_id);
          document.getElementById('edit-is_featured').checked = !!data.is_featured;

          (data.images || []).forEach(img => {
            const normalizedPath = normalizeImagePath(img.path);
            const isLib = normalizedPath.startsWith('images/products/');
            const source = isLib ? 'library_existing' : 'existing';

            imageTray.edit.push({
              id: img.id,
              url: img.url,
              path: img.path,
              isPrimary: !!img.is_primary,
              source,
            });

            if (isLib) {
              const cb = document.querySelector(`#edit-library-grid input[value="${CSS.escape(img.path)}"]`);
              if (cb) {
                cb.dataset.skipChange = '1';
                cb.checked = true;
                delete cb.dataset.skipChange;
              }
            }
          });

          renderTray('edit');
          populateEditVariants(data.variants || []);
        })
        .catch(() => {
          document.getElementById('edit-variants-body').innerHTML =
            '<tr><td colspan="5" class="px-3 py-3 text-center text-xs text-red-500">Chyba načítania</td></tr>';
        });
    }

    document.getElementById('form-edit').addEventListener('submit', function () {
      buildHiddenInputs('edit');
    });

    // ─── subcat sync ─────────────────────────────────────────────────────────
    function syncSubcategoryState(categorySelectId, subcategorySelectId) {
      const category = document.getElementById(categorySelectId);
      const subcategory = document.getElementById(subcategorySelectId);
      if (!category || !subcategory) return;
      const hasCategory = String(category.value || '').trim() !== '';
      subcategory.disabled = !hasCategory;
      subcategory.classList.toggle('bg-gray-100', !hasCategory);
      subcategory.classList.toggle('cursor-not-allowed', !hasCategory);
      if (!hasCategory) subcategory.value = '';
    }

    // ─── variant helpers ─────────────────────────────────────────────────────
    function showVariantValidationMessage(message) {
      const el = document.getElementById('variant-validation-message');
      el.textContent = message;
      el.classList.remove('hidden');
    }

    function hideVariantValidationMessage() {
      const el = document.getElementById('variant-validation-message');
      el.textContent = '';
      el.classList.add('hidden');
    }

    function populateEditVariants(variants) {
      const tbody = document.getElementById('edit-variants-body');
      tbody.innerHTML = '';
      if (!variants.length) {
        tbody.innerHTML = '<tr class="text-gray-400 italic" id="edit-empty-row"><td colspan="5" class="px-3 py-3 text-center text-xs">Žiadne varianty</td></tr>';
        return;
      }
      variants.forEach(v => addVariantRow('form-edit', v.color_id, v.color_name, v.size, v.price, v.stock_quantity, v.id));
    }

    function setSelectVal(id, value) {
      const sel = document.getElementById(id);
      if (sel) sel.value = value ?? '';
    }

    function openDeleteModal(id, name) {
      document.getElementById('delete-product-name').textContent = name;
      document.getElementById('form-delete').action = `/admin/products/${id}`;
      window.location.hash = 'modal-delete';
    }

    function showVariantAdder(formId, validationMessage = null) {
      currentFormId = formId;
      document.querySelectorAll('#modal-variant-adder input[name="va_color"]').forEach(cb => cb.checked = false);
      document.querySelectorAll('#modal-variant-adder input[name="va_size"]').forEach(cb => cb.checked = false);
      document.getElementById('va-price').value = '';
      document.getElementById('va-stock').value = '';
      if (validationMessage) showVariantValidationMessage(validationMessage);
      else hideVariantValidationMessage();
      const el = document.getElementById('modal-variant-adder');
      el.classList.remove('hidden');
      el.classList.add('flex');
    }

    function closeVariantAdder() {
      hideVariantValidationMessage();
      const el = document.getElementById('modal-variant-adder');
      el.classList.add('hidden');
      el.classList.remove('flex');
    }

    function commitVariants() {
      const colors = Array.from(document.querySelectorAll('#modal-variant-adder input[name="va_color"]:checked'));
      const sizes  = Array.from(document.querySelectorAll('#modal-variant-adder input[name="va_size"]:checked'));
      const price  = document.getElementById('va-price').value;
      const stock  = document.getElementById('va-stock').value;
      if (!colors.length || !sizes.length || !price || stock === '') {
        showVariantValidationMessage('Vyplňte farbu, veľkosť, cenu a sklad.');
        return;
      }
      const emptyId = currentFormId === 'form-add' ? 'add-empty-row' : 'edit-empty-row';
      const emptyRow = document.getElementById(emptyId);
      if (emptyRow) emptyRow.remove();
      colors.forEach(colorCb => {
        sizes.forEach(sizeCb => {
          addVariantRow(currentFormId, colorCb.value, colorCb.dataset.name, sizeCb.value, price, stock);
        });
      });
      closeVariantAdder();
    }

    function addVariantRow(formId, colorId, colorName, size, price, stock, variantId = null) {
      const tbody = document.getElementById(formId === 'form-add' ? 'add-variants-body' : 'edit-variants-body');
      const idx = variantIndex[formId]++;
      const row = document.createElement('tr');
      row.className = 'border-b border-gray-100';
      row.innerHTML = `
        <td class="px-3 py-2">${colorName}</td>
        <td class="px-3 py-2">${size}</td>
        <td class="px-3 py-2">${price} €</td>
        <td class="px-3 py-2">${stock}</td>
        <td class="px-3 py-2 text-right">
          <button type="button" onclick="this.closest('tr').remove()" class="text-gray-400 hover:text-red-500">&#x2715;</button>
          ${variantId ? `<input type="hidden" name="variants[${idx}][id]" value="${variantId}" />` : ''}
          <input type="hidden" name="variants[${idx}][color_id]" value="${colorId}" />
          <input type="hidden" name="variants[${idx}][size]" value="${size}" />
          <input type="hidden" name="variants[${idx}][price]" value="${price}" />
          <input type="hidden" name="variants[${idx}][stock]" value="${stock}" />
        </td>
      `;
      tbody.appendChild(row);
    }

    // ─── boot ─────────────────────────────────────────────────────────────────
    window.addEventListener('DOMContentLoaded', function () {
      setupFileInput('add');
      setupFileInput('edit');
      setupLibraryPicker('add');
      setupLibraryPicker('edit');
      syncSubcategoryState('add-category_id', 'add-subcategory_id');
      syncSubcategoryState('edit-category_id', 'edit-subcategory_id');
      document.getElementById('add-category_id')?.addEventListener('change', () =>
        syncSubcategoryState('add-category_id', 'add-subcategory_id'));
      document.getElementById('edit-category_id')?.addEventListener('change', () =>
        syncSubcategoryState('edit-category_id', 'edit-subcategory_id'));
      document.getElementById('add-url-input')?.addEventListener('keydown', e => {
        if (e.key === 'Enter') { e.preventDefault(); addExternalUrl('add'); }
      });
      document.getElementById('edit-url-input')?.addEventListener('keydown', e => {
        if (e.key === 'Enter') { e.preventDefault(); addExternalUrl('edit'); }
      });
    });
  </script>

  @if ($errors->any() && old('_method') !== 'PUT')
    <script>
      window.addEventListener('DOMContentLoaded', function () {
        openAddModal(false);
      });
    </script>
  @endif

</x-admin.layout>
