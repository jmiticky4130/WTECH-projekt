<x-admin.layout title="Nastavenia — Bellura.sk" active="settings">

  <div class="px-3 py-4 sm:px-6 sm:py-6 max-w-5xl">

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
              @foreach (['Ženy', 'Muži', 'Deti'] as $cat)
                <span class="inline-flex items-center gap-1.5 bg-gray-100 text-gray-700 text-sm px-3 py-1">
                  {{ $cat }} <a href="#!" class="text-gray-400 hover:text-red-500 leading-none">&#x2715;</a>
                </span>
              @endforeach
            </div>
          </div>
          <div class="md:w-72 shrink-0">
            <p class="text-xs text-gray-400 mb-2 font-medium">Pridať novú</p>
            <div class="flex gap-2">
              <input type="text" placeholder="Nová kategória" class="border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:border-brand-dark flex-1" />
              <button class="bg-brand-dark hover:bg-brand-accent text-white text-sm px-4 py-2 transition-colors whitespace-nowrap">Pridať</button>
            </div>
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
              @foreach (['Šaty', 'Blúzky', 'Nohavice', 'Mikiny', 'Topánky', 'Doplnky'] as $sub)
                <span class="inline-flex items-center gap-1.5 bg-gray-100 text-gray-700 text-sm px-3 py-1">
                  {{ $sub }} <a href="#!" class="text-gray-400 hover:text-red-500 leading-none">&#x2715;</a>
                </span>
              @endforeach
            </div>
          </div>
          <div class="md:w-72 shrink-0">
            <p class="text-xs text-gray-400 mb-2 font-medium">Pridať novú</p>
            <div class="flex gap-2">
              <input type="text" placeholder="Nová podkategória" class="border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:border-brand-dark flex-1" />
              <button class="bg-brand-dark hover:bg-brand-accent text-white text-sm px-4 py-2 transition-colors whitespace-nowrap">Pridať</button>
            </div>
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
              @foreach ([['#ffffff', 'Biela'], ['#111111', 'Čierna'], ['#3b82f6', 'Modrá'], ['#ef4444', 'Červená'], ['#f9a8d4', 'Ružová'], ['#9ca3af', 'Sivá'], ['#d4b896', 'Béžová']] as [$hex, $label])
                <span class="inline-flex items-center gap-1.5 bg-gray-100 text-gray-700 text-sm px-3 py-1">
                  <span class="inline-block w-3 h-3 rounded-full border border-gray-300" style="background:{{ $hex }}"></span>
                  {{ $label }} <a href="#!" class="text-gray-400 hover:text-red-500 leading-none ml-0.5">&#x2715;</a>
                </span>
              @endforeach
            </div>
            <div>
              <p class="text-xs text-gray-400 mb-2 font-medium">Pridať novú</p>
              <div class="flex gap-2">
                <input type="text" placeholder="Názov farby" class="border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:border-brand-dark flex-1 min-w-0" />
                <input type="color" value="#000000" class="border border-gray-300 h-[38px] w-10 p-0.5 cursor-pointer focus:outline-none focus:border-brand-dark shrink-0" title="Hex kód farby" />
                <button class="bg-brand-dark hover:bg-brand-accent text-white text-sm px-4 py-2 transition-colors whitespace-nowrap">Pridať</button>
              </div>
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
              @foreach (['100% bavlna', '100% polyester', 'Bavlna / Elastan', 'Vlna', 'Ľan', 'Satén', 'Koža'] as $mat)
                <span class="inline-flex items-center gap-1.5 bg-gray-100 text-gray-700 text-sm px-3 py-1">
                  {{ $mat }} <a href="#!" class="text-gray-400 hover:text-red-500 leading-none">&#x2715;</a>
                </span>
              @endforeach
            </div>
            <div>
              <p class="text-xs text-gray-400 mb-2 font-medium">Pridať nový</p>
              <div class="flex gap-2">
                <input type="text" placeholder="Nový materiál" class="border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:border-brand-dark flex-1" />
                <button class="bg-brand-dark hover:bg-brand-accent text-white text-sm px-4 py-2 transition-colors whitespace-nowrap">Pridať</button>
              </div>
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
              @foreach (['H&M', 'Zara', "Levi's", 'Nike', 'Mango', 'Zara Kids'] as $brand)
                <span class="inline-flex items-center gap-1.5 bg-gray-100 text-gray-700 text-sm px-3 py-1">
                  {{ $brand }} <a href="#!" class="text-gray-400 hover:text-red-500 leading-none">&#x2715;</a>
                </span>
              @endforeach
            </div>
            <div>
              <p class="text-xs text-gray-400 mb-2 font-medium">Pridať novú</p>
              <div class="flex gap-2">
                <input type="text" placeholder="Nová značka" class="border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:border-brand-dark flex-1" />
                <button class="bg-brand-dark hover:bg-brand-accent text-white text-sm px-4 py-2 transition-colors whitespace-nowrap">Pridať</button>
              </div>
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
            <div class="flex flex-wrap gap-2 mb-5">
              @foreach (['Kartou online', 'Dobierka', 'Bankový prevod'] as $method)
                <span class="inline-flex items-center gap-1.5 bg-gray-100 text-gray-700 text-sm px-3 py-1">
                  {{ $method }} <a href="#!" class="text-gray-400 hover:text-red-500 leading-none">&#x2715;</a>
                </span>
              @endforeach
            </div>
            <div>
              <p class="text-xs text-gray-400 mb-2 font-medium">Pridať nový</p>
              <div class="flex gap-2">
                <input type="text" placeholder="Nový spôsob platby" class="border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:border-brand-dark flex-1" />
                <button class="bg-brand-dark hover:bg-brand-accent text-white text-sm px-4 py-2 transition-colors whitespace-nowrap">Pridať</button>
              </div>
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
            <div class="space-y-2">
              @foreach ([['Slovenská pošta', '2–4 dni', '3,50'], ['DPD', '1–2 dni', '4,90'], ['GLS', '1–3 dni', '4,20']] as [$name, $days, $price])
                <div class="flex items-center justify-between bg-gray-50 border border-gray-200 px-3 py-2.5 text-sm">
                  <div>
                    <span class="font-medium">{{ $name }}</span>
                    <span class="text-gray-400 text-xs ml-2">{{ $days }} · {{ $price }} €</span>
                  </div>
                  <a href="#!" class="text-gray-400 hover:text-red-500 leading-none text-xs ml-4">&#x2715;</a>
                </div>
              @endforeach
            </div>
          </div>
          <div class="md:w-80 shrink-0">
            <p class="text-xs text-gray-400 mb-2 font-medium">Pridať nový</p>
            <div class="space-y-2">
              <input type="text" placeholder="Názov dopravcu" class="w-full border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:border-brand-dark" />
              <div class="relative">
                <input type="number" min="0" step="0.01" placeholder="Cena" class="w-full border border-gray-300 px-3 py-2 pr-7 text-sm focus:outline-none focus:border-brand-dark" />
                <span class="absolute right-2 top-1/2 -translate-y-1/2 text-gray-400 text-xs pointer-events-none">€</span>
              </div>
              <div>
                <p class="text-xs text-gray-400 mb-1.5">Doba dodania (dni)</p>
                <div class="flex gap-1 items-center">
                  <input type="number" min="1" placeholder="Od" class="w-full border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:border-brand-dark" />
                  <span class="text-gray-400 text-xs shrink-0">–</span>
                  <input type="number" min="1" placeholder="Do" class="w-full border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:border-brand-dark" />
                </div>
              </div>
              <button class="bg-brand-dark hover:bg-brand-accent text-white text-sm px-4 py-2 transition-colors">Pridať</button>
            </div>
          </div>
        </div>
      </div>

    </div>
  </div>

</x-admin.layout>
