<x-admin.layout title="Nastavenia — Bellura.sk" active="settings">

  <div class="px-3 py-4 sm:px-6 sm:py-6 max-w-[1400px]">

    @if (session('success'))
      <div class="mb-4 bg-green-50 border border-green-200 text-green-700 text-sm px-4 py-3 rounded">{{ session('success') }}</div>
    @endif
    @if (session('error'))
      <div class="mb-4 bg-red-50 border border-red-200 text-red-700 text-sm px-4 py-3 rounded">{{ session('error') }}</div>
    @endif

    <!-- page header -->
    <div class="mb-8">
      <p class="text-xs text-gray-400 mb-0.5">Administrácia / Nastavenia</p>
      <h1 class="text-2xl font-bold text-brand-dark">Nastavenia</h1>
    </div>

    <div class="space-y-5">

      <!-- subcategories -->
      <div class="grid grid-cols-1 gap-5">

        <!-- subcategories -->
        <div class="bg-white shadow rounded">
          <div class="px-5 py-4 border-b border-gray-100">
            <h2 class="text-sm font-bold uppercase tracking-wider text-gray-500">Podkategórie</h2>
          </div>
          <div class="px-5 py-5 flex flex-col">
            <p class="text-xs text-gray-400 mb-2 font-medium">Existujúce podkategórie</p>
            <div class="flex flex-wrap gap-2 mb-5">
              @forelse ($subcategories as $sub)
                <span class="inline-flex items-center gap-1.5 bg-gray-100 text-gray-700 text-sm px-3 py-1">
                  {{ $sub->name }}
                  <form method="POST" action="{{ route('admin.subcategories.destroy', $sub) }}" class="inline" onsubmit="return confirm('Vymazať podkategóriu?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="text-gray-400 hover:text-red-500 leading-none">&#x2715;</button>
                  </form>
                </span>
              @empty
                <p class="text-xs text-gray-400">Žiadne podkategórie.</p>
              @endforelse
            </div>
            <div>
              <p class="text-xs text-gray-400 mb-2 font-medium">Pridať novú</p>
              <form method="POST" action="{{ route('admin.subcategories.store') }}" class="flex gap-2">
                @csrf
                <input type="text" name="name" placeholder="Nová podkategória" required class="border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:border-brand-dark flex-1" />
                <button type="submit" class="bg-brand-dark hover:bg-brand-accent text-white text-sm px-4 py-2 transition-colors whitespace-nowrap">Pridať</button>
              </form>
            </div>
          </div>
        </div>

      </div>

      <!-- landing page categories -->
      <div class="bg-white shadow rounded">
        <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
          <h2 class="text-sm font-bold uppercase tracking-wider text-gray-500">Úvodná stránka – Kategórie</h2>
          <div class="flex items-center gap-3">
            <span id="lp-dirty" class="hidden text-xs text-amber-500 font-medium">● Neuložené zmeny</span>
            <span class="text-xs text-gray-400"><span id="lp-count">0</span>/4 vybraných</span>
          </div>
        </div>
        <div class="px-5 py-5">
          <p class="text-xs text-gray-400 mb-4">Vyberte max. 4 podkategórie, ktoré sa zobrazia na úvodnej stránke, a nahrajte k nim obrázky.</p>

          @if ($subcategories->isEmpty())
            <p class="text-xs text-gray-400">Žiadne podkategórie. Najprv ich pridajte vyššie.</p>
          @else
            <form method="POST" action="{{ route('admin.subcategories.landing') }}" enctype="multipart/form-data">
              @csrf
              <div class="space-y-2">
                @foreach ($subcategories as $sub)
                  <div class="lp-row flex items-center gap-4 p-3 border rounded transition-colors {{ $sub->show_on_landing ? 'border-brand-dark bg-gray-50' : 'border-gray-200' }}" data-sub-id="{{ $sub->id }}">

                    <input
                      type="checkbox"
                      name="show_on_landing[]"
                      value="{{ $sub->id }}"
                      @checked($sub->show_on_landing)
                      class="lp-checkbox accent-brand-dark w-4 h-4 shrink-0 cursor-pointer"
                    />

                    <span class="text-sm font-medium text-gray-700 flex-1 min-w-0 truncate">{{ $sub->name }}</span>

                    {{-- image thumbnail --}}
                    <div class="lp-thumb w-12 h-12 shrink-0 bg-gray-100 border border-gray-200 overflow-hidden flex items-center justify-center" id="lp-thumb-{{ $sub->id }}">
                      @if ($sub->landing_image)
                        <img
                          src="{{ \App\Support\ProductImageUrl::resolve($sub->landing_image) }}"
                          class="w-full h-full object-cover"
                          alt="{{ $sub->name }}"
                        >
                      @else
                        <span class="text-gray-300 text-xs">—</span>
                      @endif
                    </div>

                    {{-- image source: segmented toggle — only one can be active --}}
                    @php
                      $lpHasFile = $sub->landing_image && !preg_match('#^https?://#i', $sub->landing_image);
                      $lpHasUrl  = $sub->landing_image &&  preg_match('#^https?://#i', $sub->landing_image);
                    @endphp
                    <input type="hidden" name="url_images[{{ $sub->id }}]" id="lp-url-hidden-{{ $sub->id }}" value="" />
                    <div class="shrink-0 flex border border-gray-300 text-xs overflow-hidden">
                      <label id="lp-file-tab-{{ $sub->id }}"
                        class="lp-file-tab cursor-pointer flex items-center px-2.5 py-1.5 whitespace-nowrap select-none transition-colors {{ $lpHasFile ? 'bg-brand-dark text-white' : 'bg-white text-gray-500 hover:bg-gray-50' }}">
                        <span class="lp-file-label">{{ $lpHasFile ? '✓ Súbor' : 'Súbor' }}</span>
                        <input type="file" name="images[{{ $sub->id }}]" accept="image/*" class="sr-only lp-file-input" />
                      </label>
                      <div class="w-px bg-gray-300 shrink-0"></div>
                      <button type="button" onclick="openLpUrlModal({{ $sub->id }})" id="lp-url-btn-{{ $sub->id }}"
                        class="lp-url-tab flex items-center px-2.5 py-1.5 whitespace-nowrap select-none transition-colors {{ $lpHasUrl ? 'bg-brand-dark text-white' : 'bg-white text-gray-500 hover:bg-gray-50' }}">
                        {{ $lpHasUrl ? '✓ URL' : 'URL' }}
                      </button>
                    </div>

                  </div>
                @endforeach
              </div>

              <div class="flex items-center justify-between mt-4">
                <p id="lp-warning" class="text-xs text-red-500 hidden">Môžete vybrať najviac 4 podkategórie.</p>
                <button type="submit" class="ml-auto bg-brand-dark hover:bg-brand-accent text-white text-sm px-5 py-2 transition-colors">Uložiť</button>
              </div>
            </form>
          @endif
        </div>
      </div>

      <!-- colors + materials -->
      <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

        <!-- colors -->
        <div class="bg-white shadow rounded">
          <div class="px-5 py-4 border-b border-gray-100">
            <h2 class="text-sm font-bold uppercase tracking-wider text-gray-500">Farby</h2>
          </div>
          <div class="px-5 py-5 flex flex-col">
            <p class="text-xs text-gray-400 mb-2 font-medium">Existujúce farby</p>
            <div class="flex flex-wrap gap-2 mb-5">
              @forelse ($colors as $color)
                <span class="inline-flex items-center gap-1.5 bg-gray-100 text-gray-700 text-sm px-3 py-1">
                  <span class="inline-block w-3 h-3 rounded-full border border-gray-300" style="background:{{ $color->hex_code }}"></span>
                  {{ $color->name }}
                  <form method="POST" action="{{ route('admin.colors.destroy', $color) }}" class="inline" onsubmit="return confirm('Vymazať farbu?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="text-gray-400 hover:text-red-500 leading-none ml-0.5">&#x2715;</button>
                  </form>
                </span>
              @empty
                <p class="text-xs text-gray-400">Žiadne farby.</p>
              @endforelse
            </div>
            <div>
              <p class="text-xs text-gray-400 mb-2 font-medium">Pridať novú</p>
              <form method="POST" action="{{ route('admin.colors.store') }}" class="flex gap-2">
                @csrf
                <input type="text" name="name" placeholder="Názov farby" required class="border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:border-brand-dark flex-1 min-w-0" />
                <input type="color" name="hex_code" value="#000000" class="border border-gray-300 h-9.5 w-10 p-0.5 cursor-pointer focus:outline-none focus:border-brand-dark shrink-0" title="Hex kód farby" />
                <button type="submit" class="bg-brand-dark hover:bg-brand-accent text-white text-sm px-4 py-2 transition-colors whitespace-nowrap">Pridať</button>
              </form>
            </div>
          </div>
        </div>

        <!-- materials -->
        <div class="bg-white shadow rounded">
          <div class="px-5 py-4 border-b border-gray-100">
            <h2 class="text-sm font-bold uppercase tracking-wider text-gray-500">Materiály</h2>
          </div>
          <div class="px-5 py-5 flex flex-col">
            <p class="text-xs text-gray-400 mb-2 font-medium">Existujúce materiály</p>
            <div class="flex flex-wrap gap-2 mb-5">
              @forelse ($materials as $mat)
                <span class="inline-flex items-center gap-1.5 bg-gray-100 text-gray-700 text-sm px-3 py-1">
                  {{ $mat->name }}
                  <form method="POST" action="{{ route('admin.materials.destroy', $mat) }}" class="inline" onsubmit="return confirm('Vymazať materiál?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="text-gray-400 hover:text-red-500 leading-none">&#x2715;</button>
                  </form>
                </span>
              @empty
                <p class="text-xs text-gray-400">Žiadne materiály.</p>
              @endforelse
            </div>
            <div>
              <p class="text-xs text-gray-400 mb-2 font-medium">Pridať nový</p>
              <form method="POST" action="{{ route('admin.materials.store') }}" class="flex gap-2">
                @csrf
                <input type="text" name="name" placeholder="Nový materiál" required class="border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:border-brand-dark flex-1" />
                <button type="submit" class="bg-brand-dark hover:bg-brand-accent text-white text-sm px-4 py-2 transition-colors whitespace-nowrap">Pridať</button>
              </form>
            </div>
          </div>
        </div>

      </div>

      <!-- brands -->
      <div class="bg-white shadow rounded">
        <div class="px-5 py-4 border-b border-gray-100">
          <h2 class="text-sm font-bold uppercase tracking-wider text-gray-500">Značky</h2>
        </div>
        <div class="px-5 py-5 flex flex-col md:flex-row md:gap-8">
          <div class="flex-1 mb-4 md:mb-0">
            <p class="text-xs text-gray-400 mb-2 font-medium">Existujúce značky</p>
            <div class="flex flex-wrap gap-2">
              @forelse ($brands as $brand)
                <span class="inline-flex items-center gap-1.5 bg-gray-100 text-gray-700 text-sm px-3 py-1">
                  {{ $brand->name }}
                  <form method="POST" action="{{ route('admin.brands.destroy', $brand) }}" class="inline" onsubmit="return confirm('Vymazať značku?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="text-gray-400 hover:text-red-500 leading-none">&#x2715;</button>
                  </form>
                </span>
              @empty
                <p class="text-xs text-gray-400">Žiadne značky.</p>
              @endforelse
            </div>
          </div>
          <div class="md:w-72 shrink-0">
            <p class="text-xs text-gray-400 mb-2 font-medium">Pridať novú</p>
            <form method="POST" action="{{ route('admin.brands.store') }}" class="flex gap-2">
              @csrf
              <input type="text" name="name" placeholder="Nová značka" required class="border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:border-brand-dark flex-1" />
              <button type="submit" class="bg-brand-dark hover:bg-brand-accent text-white text-sm px-4 py-2 transition-colors whitespace-nowrap">Pridať</button>
            </form>
          </div>
        </div>
      </div>

      <!-- shipping methods -->
      <div class="bg-white shadow rounded overflow-hidden">
        <div class="px-5 py-4 border-b border-gray-100 flex items-center gap-4">
          <h2 class="text-sm font-bold uppercase tracking-wider text-gray-500">Spôsoby dopravy</h2>
          <span id="sm-delivery-error" class="hidden text-xs text-red-500"></span>
        </div>

        {{-- orphaned forms referenced by table inputs via form="..." --}}
        @foreach ($shippingMethods as $sm)
          <form id="sm-upd-{{ $sm->id }}" method="POST" action="{{ route('admin.shipping-methods.update', $sm) }}">@csrf @method('PUT')</form>
          <form id="sm-del-{{ $sm->id }}" method="POST" action="{{ route('admin.shipping-methods.destroy', $sm) }}" onsubmit="return confirm('Vymazať spôsob dopravy?')">@csrf @method('DELETE')</form>
        @endforeach
        <form id="sm-store" method="POST" action="{{ route('admin.shipping-methods.store') }}">@csrf</form>

        <div class="overflow-auto max-h-80 sm:max-h-none">
          <table class="w-full text-sm">
            <thead class="sticky top-0 z-10">
              <tr class="bg-gray-50 border-b border-gray-200 text-xs text-gray-500 uppercase tracking-wider">
                <th class="px-4 py-3 text-left font-medium">Názov</th>
                <th class="px-4 py-3 text-left font-medium">Typ</th>
                <th class="px-4 py-3 text-left font-medium">Cena</th>
                <th class="px-4 py-3 text-left font-medium">Dodanie (dni)</th>
                <th class="px-4 py-3 text-left font-medium max-w-[200px]">Povolené platby</th>
                <th class="px-4 py-3 text-right font-medium">Akcie</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
              @forelse ($shippingMethods as $sm)
                <tr class="hover:bg-gray-50 sm-row" data-sm-id="{{ $sm->id }}">
                  <td class="px-4 py-2">
                    <input form="sm-upd-{{ $sm->id }}" type="text" name="name" value="{{ $sm->name }}" required class="w-full min-w-[160px] border border-gray-300 px-2 py-1.5 text-sm focus:outline-none focus:border-brand-dark" />
                  </td>
                  <td class="px-4 py-2">
                    @php
                       $shippingTypeOptions = \App\Enums\ShippingType::cases();
                    @endphp
                    <select form="sm-upd-{{ $sm->id }}" name="type" class="w-full border border-gray-300 px-2 py-1.5 text-sm focus:outline-none focus:border-brand-dark bg-white">
                      @foreach($shippingTypeOptions as $enumType)
                        <option value="{{ $enumType->value }}" @selected($sm->type === $enumType->value)>{{ $enumType->value }}</option>
                      @endforeach
                    </select>
                  </td>
                  <td class="px-4 py-2">
                    <div class="relative">
                      <input form="sm-upd-{{ $sm->id }}" type="number" name="price" min="0" step="0.01" value="{{ number_format((float) $sm->price, 2, '.', '') }}" required class="w-24 border border-gray-300 px-2 py-1.5 pr-6 text-sm focus:outline-none focus:border-brand-dark" />
                      <span class="absolute right-2 top-1/2 -translate-y-1/2 text-gray-400 text-xs pointer-events-none">€</span>
                    </div>
                  </td>
                  <td class="px-4 py-2">
                    <div class="flex items-center gap-1">
                      <input form="sm-upd-{{ $sm->id }}" type="number" name="delivery_days_from" min="1" value="{{ $sm->delivery_days_from }}" required class="w-14 border border-gray-300 px-2 py-1.5 text-sm focus:outline-none focus:border-brand-dark" />
                      <span class="text-gray-400 text-xs shrink-0">–</span>
                      <input form="sm-upd-{{ $sm->id }}" type="number" name="delivery_days_to" min="1" value="{{ $sm->delivery_days_to }}" required class="w-14 border border-gray-300 px-2 py-1.5 text-sm focus:outline-none focus:border-brand-dark" />
                    </div>
                  </td>
                  <td class="px-4 py-2 relative group cursor-pointer">
                    @php $smPaymentIds = $sm->paymentMethods->pluck('id')->toArray(); @endphp
                    <div class="w-full min-w-[150px] border border-gray-300 px-3 py-1.5 text-sm bg-white flex justify-between items-center group-hover:border-brand-dark transition-colors">
                        <span class="text-gray-600 truncate">{{ count($smPaymentIds) }} vybrané</span>
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </div>
                    <div class="absolute left-4 right-4 mt-1 bg-white border border-gray-200 shadow-lg rounded-sm opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all z-10 p-2 space-y-2">
                      @foreach($paymentMethods as $pm)
                        <label class="flex items-center gap-2 text-sm hover:bg-gray-50 px-2 py-1 cursor-pointer">
                            <input form="sm-upd-{{ $sm->id }}" type="checkbox" name="payment_methods[]" value="{{ $pm->id }}" @checked(in_array($pm->id, $smPaymentIds)) class="accent-brand-dark w-3.5 h-3.5" />
                            <span>{{ $pm->name }}</span>
                        </label>
                      @endforeach
                    </div>
                  </td>
                  <td class="px-4 py-2 text-right whitespace-nowrap">
                    <button form="sm-upd-{{ $sm->id }}" type="submit" class="bg-brand-dark hover:bg-brand-accent text-white text-xs px-3 py-1.5 transition-colors uppercase tracking-wide mr-2">Uložiť</button>
                    <button form="sm-del-{{ $sm->id }}" type="submit" class="text-xs text-gray-400 hover:text-red-500 transition-colors">Vymazať</button>
                  </td>
                </tr>
              @empty
                <tr>
                  <td colspan="6" class="px-4 py-5 text-xs text-gray-400 text-center">Žiadne spôsoby dopravy.</td>
                </tr>
              @endforelse

              {{-- add new row --}}
              <tr class="bg-gray-50 border-t-2 border-gray-200">
                <td class="px-4 py-2">
                  <input form="sm-store" type="text" name="name" placeholder="Nový dopravca" required class="w-full min-w-[160px] border border-gray-300 px-2 py-1.5 text-sm focus:outline-none focus:border-brand-dark" />
                </td>
                <td class="px-4 py-2">
                  <select form="sm-store" name="type" required class="w-full border border-gray-300 px-2 py-1.5 text-sm focus:outline-none focus:border-brand-dark bg-white">
                    @foreach(\App\Enums\ShippingType::cases() as $enumType)
                        <option value="{{ $enumType->value }}">{{ $enumType->value }}</option>
                    @endforeach
                  </select>
                </td>
                <td class="px-4 py-2">
                  <div class="relative">
                    <input form="sm-store" type="number" name="price" min="0" step="0.01" placeholder="0.00" required class="w-24 border border-gray-300 px-2 py-1.5 pr-6 text-sm focus:outline-none focus:border-brand-dark" />
                    <span class="absolute right-2 top-1/2 -translate-y-1/2 text-gray-400 text-xs pointer-events-none">€</span>
                  </div>
                </td>
                <td class="px-4 py-2">
                  <div class="flex items-center gap-1">
                    <input form="sm-store" type="number" name="delivery_days_from" min="1" placeholder="Od" required class="w-14 border border-gray-300 px-2 py-1.5 text-sm focus:outline-none focus:border-brand-dark" />
                    <span class="text-gray-400 text-xs shrink-0">–</span>
                    <input form="sm-store" type="number" name="delivery_days_to" min="1" placeholder="Do" required class="w-14 border border-gray-300 px-2 py-1.5 text-sm focus:outline-none focus:border-brand-dark" />
                  </div>
                </td>
                <td class="px-4 py-2 relative group cursor-pointer">
                  <div class="w-full min-w-[150px] border border-gray-300 px-3 py-1.5 text-sm bg-white flex justify-between items-center group-hover:border-brand-dark transition-colors">
                      <span class="text-gray-600 truncate">Všetky</span>
                      <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                  </div>
                  <div class="absolute left-4 right-4 bottom-full mb-1 bg-white border border-gray-200 shadow-lg rounded-sm opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all z-10 p-2 space-y-2">
                    @foreach($paymentMethods as $pm)
                      <label class="flex items-center gap-2 text-sm hover:bg-gray-50 px-2 py-1 cursor-pointer">
                          <input form="sm-store" type="checkbox" name="payment_methods[]" value="{{ $pm->id }}" checked class="accent-brand-dark w-3.5 h-3.5" />
                          <span>{{ $pm->name }}</span>
                      </label>
                    @endforeach
                  </div>
                </td>
                <td class="px-4 py-2 text-right">
                  <button form="sm-store" type="submit" class="bg-brand-dark hover:bg-brand-accent text-white text-xs px-4 py-1.5 transition-colors uppercase tracking-wide">Pridať</button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>

    </div>
  </div>

<script>
  document.addEventListener('DOMContentLoaded', () => {

    // ── Landing page category picker ────────────────────────────────────────
    const lpCheckboxes = document.querySelectorAll('.lp-checkbox');
    const lpCountEl    = document.getElementById('lp-count');
    const lpWarning    = document.getElementById('lp-warning');
    const lpDirty      = document.getElementById('lp-dirty');
    const lpForm       = document.querySelector('form[action*="landing-page"]');

    function markLPDirty() {
      if (lpDirty) lpDirty.classList.remove('hidden');
    }

    function updateLPCounter() {
      const count = document.querySelectorAll('.lp-checkbox:checked').length;
      if (lpCountEl) lpCountEl.textContent = count;
      if (lpWarning) lpWarning.classList.toggle('hidden', count <= 4);
    }

    lpCheckboxes.forEach(cb => {
      cb.addEventListener('change', () => {
        const row = cb.closest('.lp-row');
        if (row) {
          row.classList.toggle('border-brand-dark', cb.checked);
          row.classList.toggle('bg-gray-50', cb.checked);
          row.classList.toggle('border-gray-200', !cb.checked);
        }
        updateLPCounter();
        markLPDirty();
      });
    });

    function setLpActiveTab(subId, active) {
      const fileTab = document.getElementById('lp-file-tab-' + subId);
      const urlBtn  = document.getElementById('lp-url-btn-'  + subId);
      if (!fileTab || !urlBtn) return;
      const on  = ['bg-brand-dark', 'text-white'];
      const off = ['bg-white', 'text-gray-500', 'hover:bg-gray-50'];
      if (active === 'file') {
        fileTab.classList.add(...on);    fileTab.classList.remove(...off);
        urlBtn.classList.add(...off);    urlBtn.classList.remove(...on);
      } else {
        urlBtn.classList.add(...on);     urlBtn.classList.remove(...off);
        fileTab.classList.add(...off);   fileTab.classList.remove(...on);
      }
    }

    document.querySelectorAll('.lp-file-input').forEach(input => {
      input.addEventListener('change', function () {
        if (!this.files.length) return;
        const row = this.closest('.lp-row');
        if (!row) return;
        const subId = row.dataset.subId;
        const label = this.closest('.lp-file-tab')?.querySelector('.lp-file-label');
        if (label) label.textContent = '✓ ' + this.files[0].name;
        const urlHidden = document.getElementById('lp-url-hidden-' + subId);
        if (urlHidden) urlHidden.value = '';
        const urlBtn = document.getElementById('lp-url-btn-' + subId);
        if (urlBtn) urlBtn.textContent = 'URL';
        setLpActiveTab(subId, 'file');
        markLPDirty();
      });
    });

    lpForm?.addEventListener('submit', () => {
      if (lpDirty) lpDirty.classList.add('hidden');
    });

    updateLPCounter();
    // ───────────────────────────────────────────────────────────────────────
    // For each shipping method row, snapshot original values and watch for changes
    document.querySelectorAll('tr.sm-row').forEach(row => {
      const smId = row.dataset.smId;
      const form = document.getElementById('sm-upd-' + smId);
      if (!form) return;

      // Collect all inputs/selects/checkboxes belonging to this update form
      const fields = () => document.querySelectorAll('[form="sm-upd-' + smId + '"]');

      // Per-field original values
      const originalValues = {};
      fields().forEach(el => {
        const key = el.type === 'checkbox' ? el.name + '_' + el.value : el.name;
        originalValues[key] = el.type === 'checkbox' ? el.checked : el.value;
      });

      const isFieldChanged = el => {
        const key = el.type === 'checkbox' ? el.name + '_' + el.value : el.name;
        return el.type === 'checkbox'
          ? el.checked !== originalValues[key]
          : el.value !== originalValues[key];
      };

      const isFieldInvalid = el => el.style.borderColor === 'rgb(239, 68, 68)';

      const highlightField = el => {
        if (el.type === 'hidden' || el.type === 'submit') return;
        if (isFieldInvalid(el)) return; // red validation error takes priority
        if (isFieldChanged(el)) {
          el.style.borderColor = '#f59e0b';
          el.style.backgroundColor = '#fffbeb';
        } else {
          el.style.borderColor = '';
          el.style.backgroundColor = '';
        }
      };

      const firstCell = row.querySelector('td');
      const saveBtn = document.querySelector('[form="sm-upd-' + smId + '"][type="submit"]');

      const checkDirty = () => {
        let anyDirty = false;
        fields().forEach(el => {
          highlightField(el);
          if (el.type !== 'hidden' && el.type !== 'submit' && isFieldChanged(el)) anyDirty = true;
        });
        firstCell.style.boxShadow = anyDirty ? 'inset 3px 0 0 #f59e0b' : '';
        row.style.backgroundColor = anyDirty ? '#fff7ed' : '';
        if (saveBtn) {
          saveBtn.style.backgroundColor = anyDirty ? '#f59e0b' : '';
          saveBtn.style.boxShadow = anyDirty ? '0 0 0 2px #fde68a' : '';
        }
      };

      fields().forEach(el => {
        el.addEventListener('input', checkDirty);
        el.addEventListener('change', checkDirty);
      });

      // Delivery days validation
      const fromInput  = document.querySelector('[form="sm-upd-' + smId + '"][name="delivery_days_from"]');
      const toInput    = document.querySelector('[form="sm-upd-' + smId + '"][name="delivery_days_to"]');
      const headerError = document.getElementById('sm-delivery-error');

      const validateDelivery = () => {
        if (!fromInput || !toInput) return true;
        const from = parseInt(fromInput.value, 10);
        const to   = parseInt(toInput.value, 10);
        let msg = '';
        if (fromInput.value !== '' && from < 1) msg = 'Minimálna hodnota je 1.';
        else if (toInput.value !== '' && to < 1) msg = 'Minimálna hodnota je 1.';
        else if (fromInput.value !== '' && toInput.value !== '' && from >= to)
          msg = from === to ? 'Od a Do nemôžu byť rovnaké.' : 'Od musí byť menšie ako Do.';
        if (msg) {
          if (headerError) { headerError.textContent = msg; headerError.classList.remove('hidden'); }
          [fromInput, toInput].forEach(el => { el.style.borderColor = '#ef4444'; el.style.backgroundColor = '#fef2f2'; });
          return false;
        } else {
          if (headerError) headerError.classList.add('hidden');
          [fromInput, toInput].forEach(el => { el.style.borderColor = ''; el.style.backgroundColor = ''; });
          return true;
        }
      };

      [fromInput, toInput].forEach(el => el?.addEventListener('input', validateDelivery));

      // Remove all highlights when save is submitted
      form.addEventListener('submit', e => {
        if (!validateDelivery()) { e.preventDefault(); return; }
        fields().forEach(el => {
          el.style.borderColor = '';
          el.style.backgroundColor = '';
        });
        firstCell.style.boxShadow = '';
        row.style.backgroundColor = '';
        if (saveBtn) {
          saveBtn.style.backgroundColor = '';
          saveBtn.style.boxShadow = '';
        }
      });
    });

    // Delivery validation for the new-row form
    const storeForm  = document.getElementById('sm-store');
    const storeFrom  = document.querySelector('[form="sm-store"][name="delivery_days_from"]');
    const storeTo    = document.querySelector('[form="sm-store"][name="delivery_days_to"]');
    const storeHeaderError = document.getElementById('sm-delivery-error');

    const validateStoreDelivery = () => {
      if (!storeFrom || !storeTo) return true;
      const from = parseInt(storeFrom.value, 10);
      const to   = parseInt(storeTo.value, 10);
      let msg = '';
      if (storeFrom.value !== '' && from < 1) msg = 'Minimálna hodnota je 1.';
      else if (storeTo.value !== '' && to < 1)  msg = 'Minimálna hodnota je 1.';
      else if (storeFrom.value !== '' && storeTo.value !== '' && from >= to)
        msg = from === to ? 'Od a Do nemôžu byť rovnaké.' : 'Od musí byť menšie ako Do.';
      if (msg) {
        if (storeHeaderError) { storeHeaderError.textContent = msg; storeHeaderError.classList.remove('hidden'); }
        [storeFrom, storeTo].forEach(el => { el.style.borderColor = '#ef4444'; el.style.backgroundColor = '#fef2f2'; });
        return false;
      } else {
        if (storeHeaderError) storeHeaderError.classList.add('hidden');
        [storeFrom, storeTo].forEach(el => { el.style.borderColor = ''; el.style.backgroundColor = ''; });
        return true;
      }
    };

    [storeFrom, storeTo].forEach(el => el?.addEventListener('input', validateStoreDelivery));
    storeForm?.addEventListener('submit', e => { if (!validateStoreDelivery()) e.preventDefault(); });
  });
</script>

<!-- modal: landing image via URL -->
<div id="modal-lp-url" class="fixed inset-0 bg-black/40 hidden items-center justify-center px-4 z-50">
  <div class="bg-white w-full max-w-md shadow-xl">
    <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200">
      <h2 class="text-base font-bold">Obrázok cez URL</h2>
      <button type="button" onclick="closeLpUrlModal()" class="text-gray-400 hover:text-brand-dark transition-colors text-xl leading-none">&#x2715;</button>
    </div>
    <div class="px-6 py-5 space-y-4">
      <div>
        <label class="block text-sm font-medium mb-1.5">URL obrázka</label>
        <div class="flex gap-2">
          <input type="text" id="lp-url-input" placeholder="https://example.com/image.jpg"
            class="flex-1 border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:border-brand-dark" />
          <button type="button" onclick="previewLpUrl()" class="px-3 py-2 bg-gray-100 hover:bg-gray-200 text-sm border border-gray-300 transition-colors whitespace-nowrap">Náhľad</button>
        </div>
        <p id="lp-url-error" class="hidden text-xs text-red-500 mt-1"></p>
      </div>
      <div id="lp-url-preview-wrap" class="hidden">
        <p class="text-xs text-gray-400 mb-1.5">Náhľad:</p>
        <img id="lp-url-preview-img" src="" alt="Náhľad"
          class="max-h-40 max-w-full border border-gray-200 object-contain"
          />
      </div>
    </div>
    <div class="flex justify-end gap-3 px-6 py-4 border-t border-gray-100">
      <button type="button" onclick="closeLpUrlModal()" class="px-4 py-2.5 border border-gray-300 text-sm font-medium hover:bg-gray-50 transition-colors">Zrušiť</button>
      <button type="button" onclick="confirmLpUrl()" class="bg-brand-dark hover:bg-brand-accent text-white text-sm font-bold tracking-widest uppercase px-4 py-2.5 transition-colors">Potvrdiť</button>
    </div>
  </div>
</div>

<script>
  let _lpCurrentSubId = null;

  function openLpUrlModal(subId) {
    _lpCurrentSubId = subId;
    const hidden = document.getElementById('lp-url-hidden-' + subId);
    const input  = document.getElementById('lp-url-input');
    input.value  = (hidden && hidden.value) ? hidden.value : '';
    const errEl  = document.getElementById('lp-url-error');
    errEl.textContent = '';
    errEl.classList.add('hidden');
    if (input.value.trim().match(/^https?:\/\/.+/i)) {
      _showLpPreview(input.value.trim());
    } else {
      document.getElementById('lp-url-preview-wrap').classList.add('hidden');
      document.getElementById('lp-url-preview-img').src = '';
    }
    const modal = document.getElementById('modal-lp-url');
    modal.classList.remove('hidden');
    modal.classList.add('flex');
    setTimeout(() => input.select(), 50);
  }

  function closeLpUrlModal() {
    _lpCurrentSubId = null;
    const modal = document.getElementById('modal-lp-url');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
  }

  function previewLpUrl() {
    const url   = document.getElementById('lp-url-input').value.trim();
    const errEl = document.getElementById('lp-url-error');
    if (!url.match(/^https?:\/\/.+/i)) {
      errEl.textContent = 'Zadajte platnú URL adresu (začínajúcu https://).';
      errEl.classList.remove('hidden');
      return;
    }
    errEl.classList.add('hidden');
    _showLpPreview(url);
  }

  function _showLpPreview(url) {
    const img = document.getElementById('lp-url-preview-img');
    img.src   = url;
    document.getElementById('lp-url-preview-wrap').classList.remove('hidden');
  }

  function confirmLpUrl() {
    const url   = document.getElementById('lp-url-input').value.trim();
    const errEl = document.getElementById('lp-url-error');
    if (!url.match(/^https?:\/\/.+/i)) {
      errEl.textContent = 'Zadajte platnú URL adresu (začínajúcu https://).';
      errEl.classList.remove('hidden');
      return;
    }
    errEl.classList.add('hidden');

    const hidden = document.getElementById('lp-url-hidden-' + _lpCurrentSubId);
    if (hidden) hidden.value = url;

    const row = document.querySelector('.lp-row[data-sub-id="' + _lpCurrentSubId + '"]');
    if (row) {
      const fileInput = row.querySelector('.lp-file-input');
      if (fileInput) fileInput.value = '';
      const fileLabel = row.querySelector('.lp-file-label');
      if (fileLabel) fileLabel.textContent = 'Súbor';
    }

    const btn = document.getElementById('lp-url-btn-' + _lpCurrentSubId);
    if (btn) btn.textContent = '✓ URL';

    if (typeof setLpActiveTab === 'function') setLpActiveTab(_lpCurrentSubId, 'url');

    const thumb = document.getElementById('lp-thumb-' + _lpCurrentSubId);
    if (thumb) {
      thumb.innerHTML = '<img src="' + url.replace(/&/g,'&amp;').replace(/"/g,'&quot;') + '" class="w-full h-full object-cover" alt="">';
    }

    if (typeof markLPDirty === 'function') markLPDirty();
    closeLpUrlModal();
  }

  document.addEventListener('DOMContentLoaded', () => {
    document.getElementById('lp-url-input')?.addEventListener('keydown', e => {
      if (e.key === 'Enter')  { e.preventDefault(); confirmLpUrl(); }
      if (e.key === 'Escape') { closeLpUrlModal(); }
    });
    document.getElementById('modal-lp-url')?.addEventListener('click', e => {
      if (e.target === e.currentTarget) closeLpUrlModal();
    });
  });
</script>

</x-admin.layout>
