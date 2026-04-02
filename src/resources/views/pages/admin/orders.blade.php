<x-admin.layout title="Objednávky — Bellura.sk" active="orders">
  <style>
    #modal-order-detail,
    #modal-delete-order-1, #modal-delete-order-2, #modal-delete-order-3 { display: none; }
    #modal-order-detail:target,
    #modal-delete-order-1:target, #modal-delete-order-2:target, #modal-delete-order-3:target { display: flex; }
  </style>

  <div class="px-3 py-4 sm:px-6 sm:py-6 max-w-6xl">

    <!-- page header -->
    <div class="flex items-center justify-between mb-6">
      <div>
        <p class="text-xs text-gray-400 mb-0.5">Administrácia / Objednávky</p>
        <h1 class="text-2xl font-bold text-brand-dark">Objednávky</h1>
      </div>
    </div>

    <!-- summary cards -->
    <div class="grid grid-cols-2 wide:grid-cols-4 gap-4 mb-6">
      @foreach ([['Čakajúce', 4], ['Zaplatené', 12], ['Odoslané', 7], ['Doručené', 89]] as [$label, $count])
        <div class="bg-white rounded shadow px-4 py-4">
          <p class="text-xs text-gray-400 uppercase tracking-wider mb-1">{{ $label }}</p>
          <p class="text-2xl font-bold text-brand-dark">{{ $count }}</p>
        </div>
      @endforeach
    </div>

    <!-- search & filter bar -->
    <div class="bg-white shadow rounded mb-4 px-4 py-3 flex flex-wrap gap-3 items-center">
      <input
        type="text"
        placeholder="Hľadať objednávku..."
        class="flex-1 min-w-[180px] border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:border-brand-dark"
      />
      <select class="border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:border-brand-dark bg-white">
        <option value="">Všetky stavy</option>
        <option>pending</option>
        <option>paid</option>
        <option>shipped</option>
        <option>delivered</option>
        <option>cancelled</option>
      </select>
      <button class="bg-brand-dark hover:bg-brand-accent text-white text-sm px-4 py-2 transition-colors">
        Hľadať
      </button>
    </div>

    <!-- mobile cards -->
    <div class="wide:hidden space-y-2">

      <div class="bg-white rounded shadow flex items-stretch overflow-hidden hover:bg-gray-50 transition-colors">
        <a href="#modal-order-detail" class="flex-1 flex items-start justify-between gap-3 px-4 py-3 min-w-0">
          <div class="min-w-0">
            <p class="font-semibold text-brand-dark truncate">Jana Kováčová</p>
            <p class="text-xs text-gray-400 truncate">jana.kovacova@email.sk</p>
            <p class="text-xs text-gray-400 mt-0.5">18.03.2026 · Slovenská pošta</p>
          </div>
          <div class="flex flex-col items-end gap-2 shrink-0">
            <span class="font-semibold text-sm">79,98 €</span>
            <span class="text-xs bg-blue-100 text-blue-700 px-2 py-0.5 rounded font-medium">paid</span>
          </div>
        </a>
        <a href="#modal-delete-order-1" class="flex items-center px-3 border-l border-gray-100 text-gray-300 hover:text-red-500 hover:bg-red-50 transition-colors shrink-0">
          <img src="{{ asset('icons/trash.svg') }}" class="w-5 h-5 opacity-40 hover:opacity-100" alt="Vymazať" />
        </a>
      </div>

      <div class="bg-white rounded shadow flex items-stretch overflow-hidden hover:bg-gray-50 transition-colors">
        <a href="#modal-order-detail" class="flex-1 flex items-start justify-between gap-3 px-4 py-3 min-w-0">
          <div class="min-w-0">
            <p class="font-semibold text-brand-dark truncate">Peter Novák</p>
            <p class="text-xs text-gray-400 truncate">peter.novak@email.sk</p>
            <p class="text-xs text-gray-400 mt-0.5">17.03.2026 · DPD</p>
          </div>
          <div class="flex flex-col items-end gap-2 shrink-0">
            <span class="font-semibold text-sm">59,99 €</span>
            <span class="text-xs bg-yellow-100 text-yellow-700 px-2 py-0.5 rounded font-medium">shipped</span>
          </div>
        </a>
        <a href="#modal-delete-order-2" class="flex items-center px-3 border-l border-gray-100 text-gray-300 hover:text-red-500 hover:bg-red-50 transition-colors shrink-0">
          <img src="{{ asset('icons/trash.svg') }}" class="w-5 h-5 opacity-40 hover:opacity-100" alt="Vymazať" />
        </a>
      </div>

      <div class="bg-white rounded shadow flex items-stretch overflow-hidden hover:bg-gray-50 transition-colors">
        <a href="#modal-order-detail" class="flex-1 flex items-start justify-between gap-3 px-4 py-3 min-w-0">
          <div class="min-w-0">
            <p class="font-semibold text-brand-dark truncate">Mária Horáková</p>
            <p class="text-xs text-gray-400 truncate">maria.horakova@email.sk</p>
            <p class="text-xs text-gray-400 mt-0.5">15.03.2026 · GLS</p>
          </div>
          <div class="flex flex-col items-end gap-2 shrink-0">
            <span class="font-semibold text-sm">114,98 €</span>
            <span class="text-xs bg-green-100 text-green-700 px-2 py-0.5 rounded font-medium">delivered</span>
          </div>
        </a>
        <a href="#modal-delete-order-3" class="flex items-center px-3 border-l border-gray-100 text-gray-300 hover:text-red-500 hover:bg-red-50 transition-colors shrink-0">
          <img src="{{ asset('icons/trash.svg') }}" class="w-5 h-5 opacity-40 hover:opacity-100" alt="Vymazať" />
        </a>
      </div>

      <div class="bg-white rounded shadow flex items-stretch overflow-hidden hover:bg-gray-50 transition-colors">
        <a href="#modal-order-detail" class="flex-1 flex items-start justify-between gap-3 px-4 py-3 min-w-0">
          <div class="min-w-0">
            <p class="font-semibold text-brand-dark truncate">Tomáš Blaho</p>
            <p class="text-xs text-gray-400 truncate">tomas.blaho@email.sk</p>
            <p class="text-xs text-gray-400 mt-0.5">14.03.2026 · DPD</p>
          </div>
          <div class="flex flex-col items-end gap-2 shrink-0">
            <span class="font-semibold text-sm">24,99 €</span>
            <span class="text-xs bg-green-100 text-green-700 px-2 py-0.5 rounded font-medium">delivered</span>
          </div>
        </a>
        <a href="#!" class="flex items-center px-3 border-l border-gray-100 text-gray-300 hover:text-red-500 hover:bg-red-50 transition-colors shrink-0">
          <img src="{{ asset('icons/trash.svg') }}" class="w-5 h-5 opacity-40 hover:opacity-100" alt="Vymazať" />
        </a>
      </div>

      <div class="bg-white rounded shadow flex items-stretch overflow-hidden hover:bg-gray-50 transition-colors">
        <a href="#modal-order-detail" class="flex-1 flex items-start justify-between gap-3 px-4 py-3 min-w-0">
          <div class="min-w-0">
            <p class="font-semibold text-brand-dark truncate">Zuzana Fialová</p>
            <p class="text-xs text-gray-400 truncate">zuzana.fialova@email.sk</p>
            <p class="text-xs text-gray-400 mt-0.5">12.03.2026 · Slovenská pošta</p>
          </div>
          <div class="flex flex-col items-end gap-2 shrink-0">
            <span class="font-semibold text-sm">49,99 €</span>
            <span class="text-xs bg-red-100 text-red-600 px-2 py-0.5 rounded font-medium">cancelled</span>
          </div>
        </a>
        <a href="#!" class="flex items-center px-3 border-l border-gray-100 text-gray-300 hover:text-red-500 hover:bg-red-50 transition-colors shrink-0">
          <img src="{{ asset('icons/trash.svg') }}" class="w-5 h-5 opacity-40 hover:opacity-100" alt="Vymazať" />
        </a>
      </div>

    </div>

    <!-- desktop table -->
    <div class="hidden wide:block bg-white rounded shadow overflow-x-auto">
      <table class="w-full text-sm">
        <thead class="border-b border-gray-200 text-gray-500 text-xs uppercase tracking-wider">
          <tr>
            <th class="px-4 py-3 text-left font-semibold">#</th>
            <th class="px-4 py-3 text-left font-semibold">Zákazník</th>
            <th class="px-4 py-3 text-left font-semibold">Dátum</th>
            <th class="px-4 py-3 text-left font-semibold">Celkom</th>
            <th class="px-4 py-3 text-left font-semibold">Doprava</th>
            <th class="px-4 py-3 text-left font-semibold">Stav</th>
            <th class="px-4 py-3 text-left font-semibold">Akcie</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">

          <tr class="hover:bg-gray-50 transition-colors">
            <td class="px-4 py-3 font-mono text-xs text-gray-500">#1042</td>
            <td class="px-4 py-3">
              <p class="font-semibold text-brand-dark">Jana Kováčová</p>
              <p class="text-xs text-gray-400">jana.kovacova@email.sk</p>
            </td>
            <td class="px-4 py-3 text-gray-500 text-xs">18.03.2026</td>
            <td class="px-4 py-3 font-semibold">79,98 €</td>
            <td class="px-4 py-3 text-xs text-gray-500">Slovenská pošta</td>
            <td class="px-4 py-3">
              <select class="border border-gray-200 px-2 py-1 text-xs bg-white focus:outline-none focus:border-brand-dark rounded">
                <option>pending</option>
                <option selected>paid</option>
                <option>shipped</option>
                <option>delivered</option>
                <option>cancelled</option>
              </select>
            </td>
            <td class="px-4 py-3">
              <div class="flex items-center gap-2">
                <a href="#modal-order-detail" title="Detail" class="opacity-50 hover:opacity-100 transition-opacity">
                  <img src="{{ asset('icons/eye.svg') }}" class="w-5 h-5" alt="Detail" />
                </a>
                <a href="#modal-delete-order-1" title="Vymazať" class="opacity-50 hover:opacity-100 transition-opacity">
                  <img src="{{ asset('icons/trash.svg') }}" class="w-5 h-5" alt="Vymazať" />
                </a>
              </div>
            </td>
          </tr>

          <tr class="hover:bg-gray-50 transition-colors">
            <td class="px-4 py-3 font-mono text-xs text-gray-500">#1041</td>
            <td class="px-4 py-3">
              <p class="font-semibold text-brand-dark">Peter Novák</p>
              <p class="text-xs text-gray-400">peter.novak@email.sk</p>
            </td>
            <td class="px-4 py-3 text-gray-500 text-xs">17.03.2026</td>
            <td class="px-4 py-3 font-semibold">59,99 €</td>
            <td class="px-4 py-3 text-xs text-gray-500">DPD</td>
            <td class="px-4 py-3">
              <select class="border border-gray-200 px-2 py-1 text-xs bg-white focus:outline-none focus:border-brand-dark rounded">
                <option>pending</option>
                <option>paid</option>
                <option selected>shipped</option>
                <option>delivered</option>
                <option>cancelled</option>
              </select>
            </td>
            <td class="px-4 py-3">
              <div class="flex items-center gap-2">
                <a href="#modal-order-detail" title="Detail" class="opacity-50 hover:opacity-100 transition-opacity">
                  <img src="{{ asset('icons/eye.svg') }}" class="w-5 h-5" alt="Detail" />
                </a>
                <a href="#modal-delete-order-2" title="Vymazať" class="opacity-50 hover:opacity-100 transition-opacity">
                  <img src="{{ asset('icons/trash.svg') }}" class="w-5 h-5" alt="Vymazať" />
                </a>
              </div>
            </td>
          </tr>

          <tr class="hover:bg-gray-50 transition-colors">
            <td class="px-4 py-3 font-mono text-xs text-gray-500">#1040</td>
            <td class="px-4 py-3">
              <p class="font-semibold text-brand-dark">Mária Horáková</p>
              <p class="text-xs text-gray-400">maria.horakova@email.sk</p>
            </td>
            <td class="px-4 py-3 text-gray-500 text-xs">15.03.2026</td>
            <td class="px-4 py-3 font-semibold">114,98 €</td>
            <td class="px-4 py-3 text-xs text-gray-500">GLS</td>
            <td class="px-4 py-3">
              <select class="border border-gray-200 px-2 py-1 text-xs bg-white focus:outline-none focus:border-brand-dark rounded">
                <option>pending</option>
                <option>paid</option>
                <option>shipped</option>
                <option selected>delivered</option>
                <option>cancelled</option>
              </select>
            </td>
            <td class="px-4 py-3">
              <div class="flex items-center gap-2">
                <a href="#modal-order-detail" title="Detail" class="opacity-50 hover:opacity-100 transition-opacity">
                  <img src="{{ asset('icons/eye.svg') }}" class="w-5 h-5" alt="Detail" />
                </a>
                <a href="#modal-delete-order-3" title="Vymazať" class="opacity-50 hover:opacity-100 transition-opacity">
                  <img src="{{ asset('icons/trash.svg') }}" class="w-5 h-5" alt="Vymazať" />
                </a>
              </div>
            </td>
          </tr>

          <tr class="hover:bg-gray-50 transition-colors">
            <td class="px-4 py-3 font-mono text-xs text-gray-500">#1039</td>
            <td class="px-4 py-3">
              <p class="font-semibold text-brand-dark">Tomáš Blaho</p>
              <p class="text-xs text-gray-400">tomas.blaho@email.sk</p>
            </td>
            <td class="px-4 py-3 text-gray-500 text-xs">14.03.2026</td>
            <td class="px-4 py-3 font-semibold">24,99 €</td>
            <td class="px-4 py-3 text-xs text-gray-500">DPD</td>
            <td class="px-4 py-3">
              <select class="border border-gray-200 px-2 py-1 text-xs bg-white focus:outline-none focus:border-brand-dark rounded">
                <option>pending</option>
                <option>paid</option>
                <option>shipped</option>
                <option selected>delivered</option>
                <option>cancelled</option>
              </select>
            </td>
            <td class="px-4 py-3">
              <div class="flex items-center gap-2">
                <a href="#modal-order-detail" title="Detail" class="opacity-50 hover:opacity-100 transition-opacity">
                  <img src="{{ asset('icons/eye.svg') }}" class="w-5 h-5" alt="Detail" />
                </a>
                <a href="#!" title="Vymazať" class="opacity-50 hover:opacity-100 transition-opacity">
                  <img src="{{ asset('icons/trash.svg') }}" class="w-5 h-5" alt="Vymazať" />
                </a>
              </div>
            </td>
          </tr>

          <tr class="hover:bg-gray-50 transition-colors">
            <td class="px-4 py-3 font-mono text-xs text-gray-500">#1038</td>
            <td class="px-4 py-3">
              <p class="font-semibold text-brand-dark">Zuzana Fialová</p>
              <p class="text-xs text-gray-400">zuzana.fialova@email.sk</p>
            </td>
            <td class="px-4 py-3 text-gray-500 text-xs">12.03.2026</td>
            <td class="px-4 py-3 font-semibold">49,99 €</td>
            <td class="px-4 py-3 text-xs text-gray-500">Slovenská pošta</td>
            <td class="px-4 py-3">
              <select class="border border-gray-200 px-2 py-1 text-xs bg-white focus:outline-none focus:border-brand-dark rounded">
                <option>pending</option>
                <option>paid</option>
                <option>shipped</option>
                <option>delivered</option>
                <option selected>cancelled</option>
              </select>
            </td>
            <td class="px-4 py-3">
              <div class="flex items-center gap-2">
                <a href="#modal-order-detail" title="Detail" class="opacity-50 hover:opacity-100 transition-opacity">
                  <img src="{{ asset('icons/eye.svg') }}" class="w-5 h-5" alt="Detail" />
                </a>
                <a href="#!" title="Vymazať" class="opacity-50 hover:opacity-100 transition-opacity">
                  <img src="{{ asset('icons/trash.svg') }}" class="w-5 h-5" alt="Vymazať" />
                </a>
              </div>
            </td>
          </tr>

        </tbody>
      </table>
    </div>

  </div>


  <!-- modal: order detail -->
  <div id="modal-order-detail" class="fixed inset-0 bg-black/40 z-50 items-start justify-center px-4 py-8 overflow-y-auto">
    <div class="bg-white w-full max-w-lg mx-auto shadow-xl my-auto">
      <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200">
        <h2 class="text-lg font-bold">Objednávka #1042</h2>
        <a href="#!" class="text-gray-400 hover:text-brand-dark transition-colors text-xl leading-none">&#x2715;</a>
      </div>
      <div class="px-6 py-6 space-y-5">

        <section>
          <h3 class="text-xs font-bold uppercase tracking-wider text-gray-400 mb-2">Zákazník</h3>
          <p class="text-sm font-semibold text-brand-dark">Jana Kováčová</p>
          <p class="text-sm text-gray-500">jana.kovacova@email.sk</p>
          <p class="text-sm text-gray-500 mt-1">Hlavná 12, 010 01 Žilina</p>
        </section>

        <section>
          <h3 class="text-xs font-bold uppercase tracking-wider text-gray-400 mb-2">Položky</h3>
          <div class="border border-gray-200 rounded overflow-hidden">
            <table class="w-full text-sm">
              <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                  <th class="px-3 py-2 text-left text-xs font-semibold text-gray-500">Produkt</th>
                  <th class="px-3 py-2 text-left text-xs font-semibold text-gray-500">Variant</th>
                  <th class="px-3 py-2 text-right text-xs font-semibold text-gray-500">Cena</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-gray-100">
                <tr>
                  <td class="px-3 py-2">Saténová blúzka</td>
                  <td class="px-3 py-2 text-xs text-gray-500">Biela / S</td>
                  <td class="px-3 py-2 text-right font-semibold">39,99 €</td>
                </tr>
                <tr>
                  <td class="px-3 py-2">Kožená kabelka</td>
                  <td class="px-3 py-2 text-xs text-gray-500">Čierna / —</td>
                  <td class="px-3 py-2 text-right font-semibold">39,99 €</td>
                </tr>
              </tbody>
            </table>
          </div>
        </section>

        <section class="grid grid-cols-2 gap-4">
          <div>
            <h3 class="text-xs font-bold uppercase tracking-wider text-gray-400 mb-1">Doprava</h3>
            <p class="text-sm text-brand-dark">Slovenská pošta</p>
            <p class="text-xs text-gray-400">2–4 pracovné dni · 3,50 €</p>
          </div>
          <div>
            <h3 class="text-xs font-bold uppercase tracking-wider text-gray-400 mb-1">Platba</h3>
            <p class="text-sm text-brand-dark">Kartou online</p>
          </div>
        </section>

        <div class="flex justify-between items-center border-t border-gray-100 pt-4">
          <span class="text-sm font-bold">Celkom</span>
          <span class="text-lg font-bold text-brand-dark">79,98 €</span>
        </div>

        <div>
          <label class="block text-xs font-bold uppercase tracking-wider text-gray-400 mb-1.5">Stav objednávky</label>
          <select class="w-full border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:border-brand-dark bg-white">
            <option>pending</option>
            <option selected>paid</option>
            <option>shipped</option>
            <option>delivered</option>
            <option>cancelled</option>
          </select>
        </div>

      </div>
      <div class="flex justify-end gap-3 px-6 py-4 border-t border-gray-100">
        <a href="#!" class="px-5 py-2.5 border border-gray-300 text-sm font-medium hover:bg-gray-50 transition-colors">Zavrieť</a>
        <button class="bg-brand-dark hover:bg-brand-accent text-white text-sm font-bold tracking-widest uppercase px-5 py-2.5 transition-colors">Uložiť stav</button>
      </div>
    </div>
  </div>


  <!-- modals: delete order -->
  @foreach ([
    ['modal-delete-order-1', '#1042'],
    ['modal-delete-order-2', '#1041'],
    ['modal-delete-order-3', '#1040'],
  ] as [$id, $num])
    <div id="{{ $id }}" class="fixed inset-0 bg-black/40 z-50 items-center justify-center px-4">
      <div class="bg-white w-full max-w-sm shadow-xl">
        <div class="px-6 py-6">
          <h2 class="text-lg font-bold mb-2">Vymazať objednávku</h2>
          <p class="text-sm text-gray-600 mb-6">Naozaj chcete vymazať objednávku <strong>{{ $num }}</strong>? Táto akcia je nevratná.</p>
          <div class="flex justify-end gap-3">
            <a href="#!" class="px-4 py-2.5 border border-gray-300 text-sm font-medium hover:bg-gray-50 transition-colors">Zrušiť</a>
            <a href="#!" class="px-4 py-2.5 bg-red-600 hover:bg-red-700 text-white text-sm font-bold transition-colors">Vymazať</a>
          </div>
        </div>
      </div>
    </div>
  @endforeach

</x-admin.layout>
