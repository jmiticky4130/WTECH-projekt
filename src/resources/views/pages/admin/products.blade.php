<x-admin.layout title="Produkty — Bellura.sk" active="products">
  <style>
    #modal-add, #modal-edit, #modal-delete { display: none; }
    #modal-add:target, #modal-edit:target, #modal-delete:target { display: flex; }
  </style>

  <div class="px-3 py-4 sm:px-6 sm:py-6 max-w-6xl">

    @if (session('success'))
      <div class="mb-4 bg-green-50 border border-green-200 text-green-700 text-sm px-4 py-3 rounded">{{ session('success') }}</div>
    @endif

    <!-- page header -->
    <div class="flex items-center justify-between mb-6">
      <div>
        <p class="text-xs text-gray-400 mb-0.5">Administrácia / Produkty</p>
        <h1 class="text-2xl font-bold text-brand-dark">Produkty</h1>
      </div>
      <a href="#modal-add" class="bg-brand-dark hover:bg-brand-accent text-white text-sm font-bold tracking-widest uppercase px-5 py-2.5 transition-colors">
        + Pridať produkt
      </a>
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
              <td class="px-4 py-3 font-semibold">{{ number_format($p->min_price, 2, ',', ' ') }} €</td>
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
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
          <div class="sm:col-span-2">
            <label class="block text-sm font-medium mb-1.5">Názov <span class="text-red-500">*</span></label>
            <input type="text" name="name" placeholder="Názov produktu" required class="w-full border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:border-brand-dark" />
          </div>
          <div class="sm:col-span-2">
            <label class="block text-sm font-medium mb-1.5">Opis <span class="text-red-500">*</span></label>
            <textarea name="description" rows="3" placeholder="Popis produktu..." required class="w-full border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:border-brand-dark resize-none"></textarea>
          </div>
          <div>
            <label class="block text-sm font-medium mb-1.5">Kategória <span class="text-red-500">*</span></label>
            <select name="category_id" required class="w-full border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:border-brand-dark bg-white">
              <option value="">Vybrať kategóriu</option>
              @foreach ($categories as $cat)
                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
              @endforeach
            </select>
          </div>
          <div>
            <label class="block text-sm font-medium mb-1.5">Podkategória</label>
            <select name="subcategory_id" class="w-full border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:border-brand-dark bg-white">
              <option value="">Vybrať podkategóriu</option>
              @foreach ($subcategories as $sub)
                <option value="{{ $sub->id }}">{{ $sub->name }}</option>
              @endforeach
            </select>
          </div>
          <div>
            <label class="block text-sm font-medium mb-1.5">Značka</label>
            <select name="brand_id" class="w-full border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:border-brand-dark bg-white">
              <option value="">Vybrať značku</option>
              @foreach ($brands as $brand)
                <option value="{{ $brand->id }}">{{ $brand->name }}</option>
              @endforeach
            </select>
          </div>
          <div>
            <label class="block text-sm font-medium mb-1.5">Materiál</label>
            <select name="material_id" class="w-full border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:border-brand-dark bg-white">
              <option value="">Vybrať materiál</option>
              @foreach ($materials as $mat)
                <option value="{{ $mat->id }}">{{ $mat->name }}</option>
              @endforeach
            </select>
          </div>
          <div class="sm:col-span-2">
            <label class="flex items-center gap-2.5 cursor-pointer">
              <input type="checkbox" name="is_featured" value="1" class="w-4 h-4 accent-brand-dark" />
              <span class="text-sm font-medium">Zvýraznený produkt</span>
            </label>
          </div>
        </div>

        <div class="border-t border-gray-100 pt-4">
          <label class="block text-sm font-bold mb-2">Fotografie <span class="text-red-500">*</span></label>
          <input type="file" name="images[]" accept="image/*" multiple required
            class="w-full border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:border-brand-dark file:mr-3 file:text-xs file:border-0 file:bg-gray-100 file:px-2 file:py-1 file:cursor-pointer"
            onchange="previewImages(this, 'add-previews')"
          />
          <div id="add-previews" class="flex flex-wrap gap-2 mt-2"></div>
          <p class="text-xs text-gray-400 mt-1">Prvý súbor bude primárny.</p>
        </div>

        <div class="border-t border-gray-100 pt-4">
          <div class="flex items-center justify-between mb-2">
            <label class="text-sm font-bold">Varianty <span class="text-gray-400 font-normal text-xs">(farba × veľkosť × cena × sklad)</span></label>
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
                <tr class="text-gray-400 italic" id="add-empty-row">
                  <td colspan="5" class="px-3 py-3 text-center text-xs">Žiadne varianty — kliknite na + Pridať varianty</td>
                </tr>
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

        <div class="border-t border-gray-100 pt-4">
          <label class="block text-sm font-bold mb-2">Existujúce fotografie</label>
          <div id="edit-existing-images" class="flex flex-wrap gap-2 mb-3"></div>
          <label class="block text-sm font-medium mb-1.5">Nové fotografie</label>
          <input type="file" name="images[]" accept="image/*" multiple
            class="w-full border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:border-brand-dark file:mr-3 file:text-xs file:border-0 file:bg-gray-100 file:px-2 file:py-1 file:cursor-pointer"
            onchange="previewImages(this, 'edit-new-previews')"
          />
          <div id="edit-new-previews" class="flex flex-wrap gap-2 mt-2"></div>
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
      <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200">
        <h2 class="text-lg font-bold">Pridať varianty</h2>
        <button type="button" onclick="closeVariantAdder()" class="text-gray-400 hover:text-brand-dark transition-colors text-xl leading-none">&#x2715;</button>
      </div>
      <div class="px-6 py-6 space-y-5">
        <div>
          <label class="block text-sm font-medium mb-2">Farby <span class="text-red-500">*</span></label>
          <div class="flex flex-wrap gap-2" id="va-colors">
            @foreach ($colors as $color)
              <label class="flex items-center gap-2 cursor-pointer border border-gray-200 px-3 py-1.5 text-sm hover:border-brand-dark has-checked:border-brand-dark has-checked:bg-brand-dark has-checked:text-white transition-colors">
                <input type="checkbox" name="va_color" value="{{ $color->id }}" data-name="{{ $color->name }}" class="sr-only" />
                <span class="inline-block w-3 h-3 rounded-full border border-gray-300 shrink-0" style="background:{{ $color->hex_code }}"></span>
                {{ $color->name }}
              </label>
            @endforeach
          </div>
        </div>
        <div>
          <label class="block text-sm font-medium mb-2">Veľkosti <span class="text-red-500">*</span></label>
          <p class="text-xs text-gray-400 mb-1.5">Oblečenie / doplnky</p>
          <div class="flex flex-wrap gap-2 mb-3">
            @foreach (['XS', 'S', 'M', 'L', 'XL', 'XXL'] as $size)
              <label class="flex items-center justify-center cursor-pointer border border-gray-200 w-12 h-10 text-sm font-medium hover:border-brand-dark has-checked:border-brand-dark has-checked:bg-brand-dark has-checked:text-white transition-colors">
                <input type="checkbox" name="va_size" value="{{ $size }}" class="sr-only" />
                {{ $size }}
              </label>
            @endforeach
          </div>
          <p class="text-xs text-gray-400 mb-1.5">Topánky (EU)</p>
          <div class="flex flex-wrap gap-2 max-h-28 overflow-y-auto">
            @foreach (range(20, 50) as $size)
              <label class="flex items-center justify-center cursor-pointer border border-gray-200 w-10 h-10 text-xs font-medium hover:border-brand-dark has-checked:border-brand-dark has-checked:bg-brand-dark has-checked:text-white transition-colors">
                <input type="checkbox" name="va_size" value="{{ $size }}" class="sr-only" />
                {{ $size }}
              </label>
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
    let currentFormId = null;
    const variantIndex = { 'form-add': 0, 'form-edit': 0 };

    function openEditModal(id, name) {
      document.getElementById('edit-name').value = name;
      document.getElementById('form-edit').action = `/admin/products/${id}`;
      document.getElementById('edit-variants-body').innerHTML = '<tr class="text-gray-400 italic" id="edit-empty-row"><td colspan="5" class="px-3 py-3 text-center text-xs">Načítavam...</td></tr>';
      document.getElementById('edit-existing-images').innerHTML = '';
      document.getElementById('edit-description').value = '';
      variantIndex['form-edit'] = 0;
      window.location.hash = 'modal-edit';
      fetch(`/admin/products/${id}/data`)
        .then(r => r.json())
        .then(data => {
          document.getElementById('edit-description').value = data.description || '';
          setSelectVal('edit-category_id', data.category_id);
          setSelectVal('edit-subcategory_id', data.subcategory_id);
          setSelectVal('edit-brand_id', data.brand_id);
          setSelectVal('edit-material_id', data.material_id);
          document.getElementById('edit-is_featured').checked = !!data.is_featured;
          populateEditImages(data.images || []);
          populateEditVariants(data.variants || []);
        })
        .catch(() => {
          document.getElementById('edit-variants-body').innerHTML = '<tr><td colspan="5" class="px-3 py-3 text-center text-xs text-red-500">Chyba načítania</td></tr>';
        });
    }

    function populateEditImages(images) {
      const container = document.getElementById('edit-existing-images');
      container.innerHTML = '';
      images.forEach(img => {
        const div = document.createElement('div');
        div.className = 'relative';
        div.innerHTML = `
          <img src="${img.url}" class="w-16 h-20 object-cover border border-gray-200 rounded" />
          <label class="absolute top-0.5 right-0.5 cursor-pointer" title="Zaškrtnúť = zachovať">
            <input type="checkbox" name="keep_image_ids[]" value="${img.id}" checked class="w-3 h-3" />
          </label>
          ${img.is_primary ? '<span class="absolute bottom-0.5 left-0.5 text-[9px] bg-brand-dark text-white px-1 rounded">1°</span>' : ''}
        `;
        container.appendChild(div);
      });
    }

    function populateEditVariants(variants) {
      const tbody = document.getElementById('edit-variants-body');
      tbody.innerHTML = '';
      if (!variants.length) {
        tbody.innerHTML = '<tr class="text-gray-400 italic" id="edit-empty-row"><td colspan="5" class="px-3 py-3 text-center text-xs">Žiadne varianty</td></tr>';
        return;
      }
      variants.forEach(v => addVariantRow('form-edit', v.color_id, v.color_name, v.size, v.price, v.stock_quantity));
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

    function previewImages(input, containerId) {
      const container = document.getElementById(containerId);
      container.innerHTML = '';
      Array.from(input.files).forEach((file, i) => {
        const reader = new FileReader();
        reader.onload = e => {
          const div = document.createElement('div');
          div.className = 'relative';
          div.innerHTML = `<img src="${e.target.result}" class="w-16 h-20 object-cover border border-gray-200 rounded" />${i === 0 ? '<span class="absolute bottom-0.5 left-0.5 text-[9px] bg-brand-dark text-white px-1 rounded">1°</span>' : ''}`;
          container.appendChild(div);
        };
        reader.readAsDataURL(file);
      });
    }

    function showVariantAdder(formId) {
      currentFormId = formId;
      document.querySelectorAll('#modal-variant-adder input[name="va_color"]').forEach(cb => cb.checked = false);
      document.querySelectorAll('#modal-variant-adder input[name="va_size"]').forEach(cb => cb.checked = false);
      document.getElementById('va-price').value = '';
      document.getElementById('va-stock').value = '';
      const el = document.getElementById('modal-variant-adder');
      el.classList.remove('hidden');
      el.classList.add('flex');
    }

    function closeVariantAdder() {
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
        alert('Vyplňte farbu, veľkosť, cenu a sklad.');
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

    function addVariantRow(formId, colorId, colorName, size, price, stock) {
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
          <input type="hidden" name="variants[${idx}][color_id]" value="${colorId}" />
          <input type="hidden" name="variants[${idx}][size]" value="${size}" />
          <input type="hidden" name="variants[${idx}][price]" value="${price}" />
          <input type="hidden" name="variants[${idx}][stock]" value="${stock}" />
        </td>
      `;
      tbody.appendChild(row);
    }
  </script>

</x-admin.layout>
