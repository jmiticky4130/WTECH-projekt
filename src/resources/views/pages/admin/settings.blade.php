<x-admin.layout title="Nastavenia — Bellura.sk" active="settings">

  <div class="px-3 py-4 sm:px-6 sm:py-6 max-w-5xl">

    @if (session('success'))
      <div class="mb-4 bg-green-50 border border-green-200 text-green-700 text-sm px-4 py-3 rounded">{{ session('success') }}</div>
    @endif

    <!-- page header -->
    <div class="mb-8">
      <p class="text-xs text-gray-400 mb-0.5">Administrácia / Nastavenia</p>
      <h1 class="text-2xl font-bold text-brand-dark">Nastavenia</h1>
    </div>

    <div class="space-y-5">

      <!-- categories -->
      <div class="bg-white shadow rounded">
        <div class="px-5 py-4 border-b border-gray-100">
          <h2 class="text-sm font-bold uppercase tracking-wider text-gray-500">Kategórie</h2>
        </div>
        <div class="px-5 py-5 flex flex-col md:flex-row md:gap-8">
          <div class="flex-1 mb-4 md:mb-0">
            <p class="text-xs text-gray-400 mb-2 font-medium">Existujúce kategórie</p>
            <div class="flex flex-wrap gap-2">
              @forelse ($categories as $cat)
                <span class="inline-flex items-center gap-1.5 bg-gray-100 text-gray-700 text-sm px-3 py-1">
                  {{ $cat->name }}
                  <form method="POST" action="{{ route('admin.categories.destroy', $cat) }}" class="inline" onsubmit="return confirm('Vymazať kategóriu?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="text-gray-400 hover:text-red-500 leading-none">&#x2715;</button>
                  </form>
                </span>
              @empty
                <p class="text-xs text-gray-400">Žiadne kategórie.</p>
              @endforelse
            </div>
          </div>
          <div class="md:w-72 shrink-0">
            <p class="text-xs text-gray-400 mb-2 font-medium">Pridať novú</p>
            <form method="POST" action="{{ route('admin.categories.store') }}" class="flex gap-2">
              @csrf
              <input type="text" name="name" placeholder="Nová kategória" required class="border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:border-brand-dark flex-1" />
              <button type="submit" class="bg-brand-dark hover:bg-brand-accent text-white text-sm px-4 py-2 transition-colors whitespace-nowrap">Pridať</button>
            </form>
          </div>
        </div>
      </div>

      <!-- subcategories -->
      <div class="bg-white shadow rounded">
        <div class="px-5 py-4 border-b border-gray-100">
          <h2 class="text-sm font-bold uppercase tracking-wider text-gray-500">Podkategórie</h2>
        </div>
        <div class="px-5 py-5 flex flex-col md:flex-row md:gap-8">
          <div class="flex-1 mb-4 md:mb-0">
            <p class="text-xs text-gray-400 mb-2 font-medium">Existujúce podkategórie</p>
            <div class="flex flex-wrap gap-2">
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
          </div>
          <div class="md:w-72 shrink-0">
            <p class="text-xs text-gray-400 mb-2 font-medium">Pridať novú</p>
            <form method="POST" action="{{ route('admin.subcategories.store') }}" class="flex gap-2">
              @csrf
              <input type="text" name="name" placeholder="Nová podkategória" required class="border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:border-brand-dark flex-1" />
              <button type="submit" class="bg-brand-dark hover:bg-brand-accent text-white text-sm px-4 py-2 transition-colors whitespace-nowrap">Pridať</button>
            </form>
          </div>
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

      <!-- brands + payment methods -->
      <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

        <!-- brands -->
        <div class="bg-white shadow rounded">
          <div class="px-5 py-4 border-b border-gray-100">
            <h2 class="text-sm font-bold uppercase tracking-wider text-gray-500">Značky</h2>
          </div>
          <div class="px-5 py-5 flex flex-col">
            <p class="text-xs text-gray-400 mb-2 font-medium">Existujúce značky</p>
            <div class="flex flex-wrap gap-2 mb-5">
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
            <div>
              <p class="text-xs text-gray-400 mb-2 font-medium">Pridať novú</p>
              <form method="POST" action="{{ route('admin.brands.store') }}" class="flex gap-2">
                @csrf
                <input type="text" name="name" placeholder="Nová značka" required class="border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:border-brand-dark flex-1" />
                <button type="submit" class="bg-brand-dark hover:bg-brand-accent text-white text-sm px-4 py-2 transition-colors whitespace-nowrap">Pridať</button>
              </form>
            </div>
          </div>
        </div>

        <!-- payment methods -->
        <div class="bg-white shadow rounded">
          <div class="px-5 py-4 border-b border-gray-100">
            <h2 class="text-sm font-bold uppercase tracking-wider text-gray-500">Spôsoby platby</h2>
          </div>
          <div class="px-5 py-5 flex flex-col">
            <p class="text-xs text-gray-400 mb-2 font-medium">Existujúce spôsoby</p>
            <div class="space-y-3 mb-5">
              @forelse ($paymentMethods as $pm)
                <div class="bg-gray-50 border border-gray-200 p-3">
                  <form method="POST" action="{{ route('admin.payment-methods.update', $pm) }}" class="space-y-3">
                    @csrf @method('PUT')

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                      <input type="text" name="name" value="{{ $pm->name }}" required class="w-full border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:border-brand-dark" />
                      <select name="type" class="w-full border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:border-brand-dark bg-white">
                        <option value="card" @selected($pm->type === 'card')>card</option>
                        <option value="cod" @selected($pm->type === 'cod')>cod</option>
                        <option value="google_pay" @selected($pm->type === 'google_pay')>google_pay</option>
                        <option value="bank_transfer" @selected($pm->type === 'bank_transfer')>bank_transfer</option>
                      </select>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                      <div class="relative">
                        <input type="number" name="fee" min="0" step="0.01" value="{{ number_format((float) $pm->fee, 2, '.', '') }}" class="w-full border border-gray-300 px-3 py-2 pr-7 text-sm focus:outline-none focus:border-brand-dark" />
                        <span class="absolute right-2 top-1/2 -translate-y-1/2 text-gray-400 text-xs pointer-events-none">€</span>
                      </div>
                      <input type="number" name="sort_order" min="0" value="{{ $pm->sort_order }}" placeholder="Poradie" class="w-full border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:border-brand-dark" />
                    </div>

                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                      <div class="flex flex-wrap gap-4 text-xs text-gray-600">
                        <label class="inline-flex items-center gap-1.5 cursor-pointer">
                          <input type="hidden" name="requires_address" value="0" />
                          <input type="checkbox" name="requires_address" value="1" @checked($pm->requires_address) class="accent-brand-dark w-4 h-4" />
                          <span>Len pre adresu</span>
                        </label>
                        <label class="inline-flex items-center gap-1.5 cursor-pointer">
                          <input type="hidden" name="is_active" value="0" />
                          <input type="checkbox" name="is_active" value="1" @checked($pm->is_active) class="accent-brand-dark w-4 h-4" />
                          <span>Aktívne</span>
                        </label>
                      </div>

                      <button type="submit" class="bg-brand-dark hover:bg-brand-accent text-white text-xs px-3 py-2 transition-colors uppercase tracking-wide">
                        Uložiť
                      </button>
                    </div>
                  </form>

                  <form method="POST" action="{{ route('admin.payment-methods.destroy', $pm) }}" class="mt-2 text-right" onsubmit="return confirm('Vymazať spôsob platby?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="text-xs text-gray-500 hover:text-red-500 transition-colors">Vymazať</button>
                  </form>
                </div>
              @empty
                <p class="text-xs text-gray-400">Žiadne spôsoby platby.</p>
              @endforelse
            </div>

            <div>
              <p class="text-xs text-gray-400 mb-2 font-medium">Pridať nový</p>
              <form method="POST" action="{{ route('admin.payment-methods.store') }}" class="space-y-2">
                @csrf

                <input type="text" name="name" placeholder="Názov (napr. Kartou online)" required class="w-full border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:border-brand-dark" />

                <select name="type" required class="w-full border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:border-brand-dark bg-white">
                  <option value="card">card</option>
                  <option value="cod">cod</option>
                  <option value="google_pay">google_pay</option>
                  <option value="bank_transfer">bank_transfer</option>
                </select>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                  <div class="relative">
                    <input type="number" name="fee" min="0" step="0.01" value="0" placeholder="Poplatok" class="w-full border border-gray-300 px-3 py-2 pr-7 text-sm focus:outline-none focus:border-brand-dark" />
                    <span class="absolute right-2 top-1/2 -translate-y-1/2 text-gray-400 text-xs pointer-events-none">€</span>
                  </div>
                  <input type="number" name="sort_order" min="0" value="0" placeholder="Poradie" class="w-full border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:border-brand-dark" />
                </div>

                <div class="flex flex-wrap gap-4 text-xs text-gray-600">
                  <label class="inline-flex items-center gap-1.5 cursor-pointer">
                    <input type="hidden" name="requires_address" value="0" />
                    <input type="checkbox" name="requires_address" value="1" class="accent-brand-dark w-4 h-4" />
                    <span>Len pre adresu</span>
                  </label>
                  <label class="inline-flex items-center gap-1.5 cursor-pointer">
                    <input type="hidden" name="is_active" value="0" />
                    <input type="checkbox" name="is_active" value="1" checked class="accent-brand-dark w-4 h-4" />
                    <span>Aktívne</span>
                  </label>
                </div>

                <button type="submit" class="bg-brand-dark hover:bg-brand-accent text-white text-sm px-4 py-2 transition-colors">Pridať</button>
              </form>
            </div>
          </div>
        </div>

      </div>

      <!-- shipping methods -->
      <div class="bg-white shadow rounded">
        <div class="px-5 py-4 border-b border-gray-100">
          <h2 class="text-sm font-bold uppercase tracking-wider text-gray-500">Spôsoby dopravy</h2>
        </div>
        <div class="px-5 py-5 flex flex-col md:flex-row md:gap-8">
          <div class="flex-1 mb-6 md:mb-0">
            <p class="text-xs text-gray-400 mb-2 font-medium">Existujúce spôsoby</p>
            <div class="space-y-3">
              @forelse ($shippingMethods as $sm)
                <div class="bg-gray-50 border border-gray-200 p-3">
                  <form method="POST" action="{{ route('admin.shipping-methods.update', $sm) }}" class="space-y-3">
                    @csrf @method('PUT')

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                      <input type="text" name="name" value="{{ $sm->name }}" required class="w-full border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:border-brand-dark" />
                      <select name="type" class="w-full border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:border-brand-dark bg-white">
                        <option value="address" @selected($sm->type === 'address')>address</option>
                        <option value="pickup_point" @selected($sm->type === 'pickup_point')>pickup_point</option>
                        <option value="personal_pickup" @selected($sm->type === 'personal_pickup')>personal_pickup</option>
                      </select>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                      <div class="relative">
                        <input type="number" name="price" min="0" step="0.01" value="{{ number_format((float) $sm->price, 2, '.', '') }}" required class="w-full border border-gray-300 px-3 py-2 pr-7 text-sm focus:outline-none focus:border-brand-dark" />
                        <span class="absolute right-2 top-1/2 -translate-y-1/2 text-gray-400 text-xs pointer-events-none">€</span>
                      </div>
                      <input type="number" name="sort_order" min="0" value="{{ $sm->sort_order }}" placeholder="Poradie" class="w-full border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:border-brand-dark" />
                    </div>

                    <div>
                      <p class="text-xs text-gray-400 mb-1.5">Doba dodania (dni)</p>
                      <div class="grid grid-cols-[1fr_auto_1fr] gap-1 items-center">
                        <input type="number" name="delivery_days_from" min="1" value="{{ $sm->delivery_days_from }}" required class="w-full border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:border-brand-dark" />
                        <span class="text-gray-400 text-xs">–</span>
                        <input type="number" name="delivery_days_to" min="1" value="{{ $sm->delivery_days_to }}" required class="w-full border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:border-brand-dark" />
                      </div>
                    </div>

                    <textarea name="description" rows="2" placeholder="Popis dopravy"
                              class="w-full border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:border-brand-dark">{{ $sm->description }}</textarea>

                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                      <label class="inline-flex items-center gap-1.5 cursor-pointer text-xs text-gray-600">
                        <input type="hidden" name="is_active" value="0" />
                        <input type="checkbox" name="is_active" value="1" @checked($sm->is_active) class="accent-brand-dark w-4 h-4" />
                        <span>Aktívne</span>
                      </label>

                      <button type="submit" class="bg-brand-dark hover:bg-brand-accent text-white text-xs px-3 py-2 transition-colors uppercase tracking-wide">
                        Uložiť
                      </button>
                    </div>
                  </form>

                  <form method="POST" action="{{ route('admin.shipping-methods.destroy', $sm) }}" class="mt-2 text-right" onsubmit="return confirm('Vymazať spôsob dopravy?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="text-xs text-gray-500 hover:text-red-500 transition-colors">Vymazať</button>
                  </form>
                </div>
              @empty
                <p class="text-xs text-gray-400">Žiadne spôsoby dopravy.</p>
              @endforelse
            </div>
          </div>

          <div class="md:w-80 shrink-0">
            <p class="text-xs text-gray-400 mb-2 font-medium">Pridať nový</p>
            <form method="POST" action="{{ route('admin.shipping-methods.store') }}" class="space-y-2">
              @csrf

              <input type="text" name="name" placeholder="Názov dopravcu" required class="w-full border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:border-brand-dark" />

              <select name="type" required class="w-full border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:border-brand-dark bg-white">
                <option value="address">address</option>
                <option value="pickup_point">pickup_point</option>
                <option value="personal_pickup">personal_pickup</option>
              </select>

              <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                <div class="relative">
                  <input type="number" name="price" min="0" step="0.01" placeholder="Cena" required class="w-full border border-gray-300 px-3 py-2 pr-7 text-sm focus:outline-none focus:border-brand-dark" />
                  <span class="absolute right-2 top-1/2 -translate-y-1/2 text-gray-400 text-xs pointer-events-none">€</span>
                </div>
                <input type="number" name="sort_order" min="0" value="0" placeholder="Poradie" class="w-full border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:border-brand-dark" />
              </div>

              <div>
                <p class="text-xs text-gray-400 mb-1.5">Doba dodania (dni)</p>
                <div class="flex gap-1 items-center">
                  <input type="number" name="delivery_days_from" min="1" placeholder="Od" required class="w-full border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:border-brand-dark" />
                  <span class="text-gray-400 text-xs shrink-0">–</span>
                  <input type="number" name="delivery_days_to" min="1" placeholder="Do" required class="w-full border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:border-brand-dark" />
                </div>
              </div>

              <textarea name="description" rows="2" placeholder="Popis dopravy"
                        class="w-full border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:border-brand-dark"></textarea>

              <label class="inline-flex items-center gap-1.5 cursor-pointer text-xs text-gray-600">
                <input type="hidden" name="is_active" value="0" />
                <input type="checkbox" name="is_active" value="1" checked class="accent-brand-dark w-4 h-4" />
                <span>Aktívne</span>
              </label>

              <button type="submit" class="bg-brand-dark hover:bg-brand-accent text-white text-sm px-4 py-2 transition-colors">Pridať</button>
            </form>
          </div>
        </div>
      </div>

    </div>
  </div>

</x-admin.layout>
