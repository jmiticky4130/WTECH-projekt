<x-admin.layout title="Produkty — Bellura.sk" active="products">
  <style>
    #modal-add, #modal-edit, #modal-add-variant,
    #modal-delete-1, #modal-delete-2, #modal-delete-3, #modal-delete-4, #modal-delete-5 { display: none; }
    #modal-add:target, #modal-edit:target, #modal-add-variant:target,
    #modal-delete-1:target, #modal-delete-2:target, #modal-delete-3:target,
    #modal-delete-4:target, #modal-delete-5:target { display: flex; }
  </style>

  <div class="px-3 py-4 sm:px-6 sm:py-6 max-w-6xl">

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
    <div class="bg-white shadow rounded mb-4 px-4 py-3 flex flex-wrap gap-3 items-center">
      <input
        type="text"
        placeholder="Hľadať produkty..."
        class="flex-1 min-w-[180px] border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:border-brand-dark"
      />
      <select class="border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:border-brand-dark bg-white">
        <option value="">Všetky kategórie</option>
        <option>Ženy</option>
        <option>Muži</option>
        <option>Deti</option>
      </select>
      <button class="bg-brand-dark hover:bg-brand-accent text-white text-sm px-4 py-2 transition-colors">
        Hľadať
      </button>
    </div>

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

          <tr class="hover:bg-gray-50 transition-colors">
            <td class="px-4 py-3 hidden wide:table-cell">
              <div class="w-12 h-16 bg-gray-200 rounded overflow-hidden relative">
                <img src="{{ asset('images/products/satin-blouse.jpg') }}" class="w-full absolute top-1/2 -translate-y-1/2" alt="Saténová blúzka">
              </div>
            </td>
            <td class="px-4 py-3">
              <p class="font-semibold text-brand-dark">Saténová blúzka</p>
              <p class="text-xs text-gray-400">H&M · Biela</p>
            </td>
            <td class="px-4 py-3 text-gray-600 hidden wide:table-cell">Ženy</td>
            <td class="px-4 py-3 font-semibold">39,99 €</td>
            <td class="px-4 py-3">
              <span class="bg-green-100 text-green-700 text-xs font-semibold px-2 py-0.5 rounded whitespace-nowrap">24 ks</span>
            </td>
            <td class="px-4 py-3 hidden wide:table-cell">
              <span class="bg-yellow-100 text-yellow-700 text-xs font-semibold px-2 py-0.5 rounded">Áno</span>
            </td>
            <td class="px-4 py-3">
              <div class="flex items-center gap-2">
                <a href="#modal-edit" title="Upraviť" class="opacity-50 hover:opacity-100 transition-opacity">
                  <img src="{{ asset('icons/edit.svg') }}" class="w-5 h-5" alt="Upraviť" />
                </a>
                <a href="#modal-delete-1" title="Vymazať" class="opacity-50 hover:opacity-100 transition-opacity">
                  <img src="{{ asset('icons/trash.svg') }}" class="w-5 h-5" alt="Vymazať" />
                </a>
              </div>
            </td>
          </tr>

          <tr class="hover:bg-gray-50 transition-colors">
            <td class="px-4 py-3 hidden wide:table-cell">
              <div class="w-12 h-16 bg-gray-200 rounded overflow-hidden relative">
                <img src="{{ asset('images/products/satin-blouse.jpg') }}" class="w-full absolute top-1/2 -translate-y-1/2" alt="Džínsové nohavice">
              </div>
            </td>
            <td class="px-4 py-3">
              <p class="font-semibold text-brand-dark">Džínsové nohavice</p>
              <p class="text-xs text-gray-400">Levi's · Modrá</p>
            </td>
            <td class="px-4 py-3 text-gray-600 hidden wide:table-cell">Muži</td>
            <td class="px-4 py-3 font-semibold">59,99 €</td>
            <td class="px-4 py-3">
              <span class="bg-green-100 text-green-700 text-xs font-semibold px-2 py-0.5 rounded whitespace-nowrap">12 ks</span>
            </td>
            <td class="px-4 py-3 hidden wide:table-cell">
              <span class="text-gray-400 text-xs">Nie</span>
            </td>
            <td class="px-4 py-3">
              <div class="flex items-center gap-2">
                <a href="#modal-edit" title="Upraviť" class="opacity-50 hover:opacity-100 transition-opacity">
                  <img src="{{ asset('icons/edit.svg') }}" class="w-5 h-5" alt="Upraviť" />
                </a>
                <a href="#modal-delete-2" title="Vymazať" class="opacity-50 hover:opacity-100 transition-opacity">
                  <img src="{{ asset('icons/trash.svg') }}" class="w-5 h-5" alt="Vymazať" />
                </a>
              </div>
            </td>
          </tr>

          <tr class="hover:bg-gray-50 transition-colors">
            <td class="px-4 py-3 hidden wide:table-cell">
              <div class="w-12 h-16 bg-gray-200 rounded overflow-hidden relative">
                <img src="{{ asset('images/products/satin-blouse.jpg') }}" class="w-full absolute top-1/2 -translate-y-1/2" alt="Detská mikina">
              </div>
            </td>
            <td class="px-4 py-3">
              <p class="font-semibold text-brand-dark">Detská mikina</p>
              <p class="text-xs text-gray-400">Zara Kids · Ružová</p>
            </td>
            <td class="px-4 py-3 text-gray-600 hidden wide:table-cell">Deti</td>
            <td class="px-4 py-3 font-semibold">24,99 €</td>
            <td class="px-4 py-3">
              <span class="bg-yellow-100 text-yellow-700 text-xs font-semibold px-2 py-0.5 rounded whitespace-nowrap">3 ks</span>
            </td>
            <td class="px-4 py-3 hidden wide:table-cell">
              <span class="text-gray-400 text-xs">Nie</span>
            </td>
            <td class="px-4 py-3">
              <div class="flex items-center gap-2">
                <a href="#modal-edit" title="Upraviť" class="opacity-50 hover:opacity-100 transition-opacity">
                  <img src="{{ asset('icons/edit.svg') }}" class="w-5 h-5" alt="Upraviť" />
                </a>
                <a href="#modal-delete-3" title="Vymazať" class="opacity-50 hover:opacity-100 transition-opacity">
                  <img src="{{ asset('icons/trash.svg') }}" class="w-5 h-5" alt="Vymazať" />
                </a>
              </div>
            </td>
          </tr>

          <tr class="hover:bg-gray-50 transition-colors">
            <td class="px-4 py-3 hidden wide:table-cell">
              <div class="w-12 h-16 bg-gray-200 rounded overflow-hidden relative">
                <img src="{{ asset('images/products/satin-blouse.jpg') }}" class="w-full absolute top-1/2 -translate-y-1/2" alt="Kožená kabelka">
              </div>
            </td>
            <td class="px-4 py-3">
              <p class="font-semibold text-brand-dark">Kožená kabelka</p>
              <p class="text-xs text-gray-400">Mango · Čierna</p>
            </td>
            <td class="px-4 py-3 text-gray-600 hidden wide:table-cell">Ženy</td>
            <td class="px-4 py-3 font-semibold">89,99 €</td>
            <td class="px-4 py-3">
              <span class="bg-green-100 text-green-700 text-xs font-semibold px-2 py-0.5 rounded whitespace-nowrap">8 ks</span>
            </td>
            <td class="px-4 py-3 hidden wide:table-cell">
              <span class="text-gray-400 text-xs">Nie</span>
            </td>
            <td class="px-4 py-3">
              <div class="flex items-center gap-2">
                <a href="#modal-edit" title="Upraviť" class="opacity-50 hover:opacity-100 transition-opacity">
                  <img src="{{ asset('icons/edit.svg') }}" class="w-5 h-5" alt="Upraviť" />
                </a>
                <a href="#modal-delete-4" title="Vymazať" class="opacity-50 hover:opacity-100 transition-opacity">
                  <img src="{{ asset('icons/trash.svg') }}" class="w-5 h-5" alt="Vymazať" />
                </a>
              </div>
            </td>
          </tr>

          <tr class="hover:bg-gray-50 transition-colors">
            <td class="px-4 py-3 hidden wide:table-cell">
              <div class="w-12 h-16 bg-gray-200 rounded overflow-hidden relative">
                <img src="{{ asset('images/products/satin-blouse.jpg') }}" class="w-full absolute top-1/2 -translate-y-1/2" alt="Pánske tričko">
              </div>
            </td>
            <td class="px-4 py-3">
              <p class="font-semibold text-brand-dark">Pánske tričko</p>
              <p class="text-xs text-gray-400">Nike · Sivá</p>
            </td>
            <td class="px-4 py-3 text-gray-600 hidden wide:table-cell">Muži</td>
            <td class="px-4 py-3 font-semibold">29,99 €</td>
            <td class="px-4 py-3">
              <span class="bg-red-100 text-red-600 text-xs font-semibold px-2 py-0.5 rounded whitespace-nowrap">0 ks</span>
            </td>
            <td class="px-4 py-3 hidden wide:table-cell">
              <span class="text-gray-400 text-xs">Nie</span>
            </td>
            <td class="px-4 py-3">
              <div class="flex items-center gap-2">
                <a href="#modal-edit" title="Upraviť" class="opacity-50 hover:opacity-100 transition-opacity">
                  <img src="{{ asset('icons/edit.svg') }}" class="w-5 h-5" alt="Upraviť" />
                </a>
                <a href="#modal-delete-5" title="Vymazať" class="opacity-50 hover:opacity-100 transition-opacity">
                  <img src="{{ asset('icons/trash.svg') }}" class="w-5 h-5" alt="Vymazať" />
                </a>
              </div>
            </td>
          </tr>

        </tbody>
      </table>
    </div>

  </div>


  <!-- modal: add product -->
  <div id="modal-add" class="fixed inset-0 bg-black/40 z-50 items-center justify-center px-4 py-8 overflow-y-auto">
    <div class="bg-white w-full max-w-2xl mx-auto shadow-xl relative my-auto">
      <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200">
        <h2 class="text-lg font-bold">Pridať produkt</h2>
        <a href="#!" class="text-gray-400 hover:text-brand-dark transition-colors text-xl leading-none">&#x2715;</a>
      </div>
      <form action="#" method="post" class="px-6 py-6 space-y-5">
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
          <div class="sm:col-span-2">
            <label class="block text-sm font-medium mb-1.5">Názov <span class="text-red-500">*</span></label>
            <input type="text" placeholder="Názov produktu" required class="w-full border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:border-brand-dark" />
          </div>
          <div class="sm:col-span-2">
            <label class="block text-sm font-medium mb-1.5">Opis <span class="text-red-500">*</span></label>
            <textarea rows="3" placeholder="Popis produktu..." required class="w-full border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:border-brand-dark resize-none"></textarea>
          </div>
          <div>
            <label class="block text-sm font-medium mb-1.5">Kategória <span class="text-red-500">*</span></label>
            <select class="w-full border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:border-brand-dark bg-white">
              <option value="">Vybrať kategóriu</option>
              <option>Ženy</option>
              <option>Muži</option>
              <option>Deti</option>
            </select>
          </div>
          <div>
            <label class="block text-sm font-medium mb-1.5">Podkategória</label>
            <select class="w-full border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:border-brand-dark bg-white">
              <option value="">Vybrať podkategóriu</option>
              <option>Šaty</option>
              <option>Blúzky</option>
              <option>Nohavice</option>
              <option>Mikiny</option>
              <option>Topánky</option>
              <option>Doplnky</option>
            </select>
          </div>
          <div>
            <label class="block text-sm font-medium mb-1.5">Značka</label>
            <select class="w-full border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:border-brand-dark bg-white">
              <option value="">Vybrať značku</option>
              <option>H&amp;M</option>
              <option>Zara</option>
              <option>Levi's</option>
              <option>Nike</option>
              <option>Mango</option>
              <option>Zara Kids</option>
            </select>
          </div>
          <div>
            <label class="block text-sm font-medium mb-1.5">Materiál</label>
            <select class="w-full border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:border-brand-dark bg-white">
              <option value="">Vybrať materiál</option>
              <option>100% bavlna</option>
              <option>100% polyester</option>
              <option>Bavlna / Elastan</option>
              <option>Vlna</option>
              <option>Ľan</option>
              <option>Satén</option>
              <option>Koža</option>
            </select>
          </div>
          <div class="sm:col-span-2">
            <label class="flex items-center gap-2.5 cursor-pointer">
              <input type="checkbox" name="is_featured" class="w-4 h-4 accent-brand-dark" />
              <span class="text-sm font-medium">Zvýraznený produkt</span>
            </label>
          </div>
        </div>

        <div class="border-t border-gray-100 pt-4">
          <label class="block text-sm font-bold mb-2">Fotografie</label>
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
            <div>
              <label class="block text-xs text-gray-500 mb-1">Primárna fotografia <span class="text-red-500">*</span></label>
              <input type="file" accept="image/*" class="w-full border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:border-brand-dark file:mr-3 file:text-xs file:border-0 file:bg-gray-100 file:px-2 file:py-1 file:cursor-pointer" />
            </div>
            <div>
              <label class="block text-xs text-gray-500 mb-1">Ďalšia fotografia</label>
              <input type="file" accept="image/*" class="w-full border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:border-brand-dark file:mr-3 file:text-xs file:border-0 file:bg-gray-100 file:px-2 file:py-1 file:cursor-pointer" />
            </div>
          </div>
        </div>

        <div class="border-t border-gray-100 pt-4">
          <div class="flex items-center justify-between mb-2">
            <label class="text-sm font-bold">Varianty <span class="text-gray-400 font-normal text-xs">(farba + veľkosť + cena + sklad)</span></label>
            <a href="#modal-add-variant" class="text-xs font-semibold text-brand-dark hover:underline">+ Pridať variant</a>
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
              <tbody>
                <tr class="text-gray-400 italic">
                  <td colspan="5" class="px-3 py-3 text-center text-xs">Zatiaľ žiadne varianty — kliknite na + Pridať variant</td>
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
  <div id="modal-edit" class="fixed inset-0 bg-black/40 z-50 items-center justify-center px-4 py-8 overflow-y-auto">
    <div class="bg-white w-full max-w-2xl mx-auto shadow-xl relative my-auto">
      <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200">
        <h2 class="text-lg font-bold">Upraviť produkt</h2>
        <a href="#!" class="text-gray-400 hover:text-brand-dark transition-colors text-xl leading-none">&#x2715;</a>
      </div>
      <form action="#" method="post" class="px-6 py-6 space-y-5">
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
          <div class="sm:col-span-2">
            <label class="block text-sm font-medium mb-1.5">Názov <span class="text-red-500">*</span></label>
            <input type="text" value="Saténová blúzka" required class="w-full border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:border-brand-dark" />
          </div>
          <div class="sm:col-span-2">
            <label class="block text-sm font-medium mb-1.5">Opis <span class="text-red-500">*</span></label>
            <textarea rows="3" required class="w-full border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:border-brand-dark resize-none">Elegantná saténová blúzka v bielej farbe. Vhodná na každodenné nosenie aj na špeciálne príležitosti.</textarea>
          </div>
          <div>
            <label class="block text-sm font-medium mb-1.5">Kategória <span class="text-red-500">*</span></label>
            <select class="w-full border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:border-brand-dark bg-white">
              <option selected>Ženy</option>
              <option>Muži</option>
              <option>Deti</option>
            </select>
          </div>
          <div>
            <label class="block text-sm font-medium mb-1.5">Podkategória</label>
            <select class="w-full border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:border-brand-dark bg-white">
              <option>Šaty</option>
              <option selected>Blúzky</option>
              <option>Nohavice</option>
              <option>Mikiny</option>
              <option>Topánky</option>
              <option>Doplnky</option>
            </select>
          </div>
          <div>
            <label class="block text-sm font-medium mb-1.5">Značka</label>
            <select class="w-full border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:border-brand-dark bg-white">
              <option selected>H&amp;M</option>
              <option>Zara</option>
              <option>Levi's</option>
              <option>Nike</option>
              <option>Mango</option>
              <option>Zara Kids</option>
            </select>
          </div>
          <div>
            <label class="block text-sm font-medium mb-1.5">Materiál</label>
            <select class="w-full border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:border-brand-dark bg-white">
              <option>100% bavlna</option>
              <option selected>100% polyester</option>
              <option>Bavlna / Elastan</option>
              <option>Vlna</option>
              <option>Ľan</option>
              <option>Satén</option>
              <option>Koža</option>
            </select>
          </div>
          <div class="sm:col-span-2">
            <label class="flex items-center gap-2.5 cursor-pointer">
              <input type="checkbox" name="is_featured" checked class="w-4 h-4 accent-brand-dark" />
              <span class="text-sm font-medium">Zvýraznený produkt</span>
            </label>
          </div>
        </div>

        <div class="border-t border-gray-100 pt-4">
          <label class="block text-sm font-bold mb-2">Fotografie</label>
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
            <div>
              <label class="block text-xs text-gray-500 mb-1">Primárna fotografia <span class="text-red-500">*</span></label>
              <input type="file" accept="image/*" class="w-full border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:border-brand-dark file:mr-3 file:text-xs file:border-0 file:bg-gray-100 file:px-2 file:py-1 file:cursor-pointer" />
            </div>
            <div>
              <label class="block text-xs text-gray-500 mb-1">Ďalšia fotografia</label>
              <input type="file" accept="image/*" class="w-full border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:border-brand-dark file:mr-3 file:text-xs file:border-0 file:bg-gray-100 file:px-2 file:py-1 file:cursor-pointer" />
            </div>
          </div>
        </div>

        <div class="border-t border-gray-100 pt-4">
          <div class="flex items-center justify-between mb-2">
            <label class="text-sm font-bold">Varianty <span class="text-gray-400 font-normal text-xs">(farba + veľkosť + cena + sklad)</span></label>
            <a href="#modal-add-variant" class="text-xs font-semibold text-brand-dark hover:underline">+ Pridať variant</a>
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
              <tbody class="divide-y divide-gray-100">
                <tr>
                  <td class="px-3 py-2 flex items-center gap-1.5">
                    <span class="inline-block w-3 h-3 rounded-full border border-gray-300" style="background:#ffffff"></span> Biela
                  </td>
                  <td class="px-3 py-2">S</td>
                  <td class="px-3 py-2">39,99 €</td>
                  <td class="px-3 py-2"><span class="bg-green-100 text-green-700 px-1.5 py-0.5 rounded font-semibold">12</span></td>
                  <td class="px-3 py-2 text-right"><a href="#!" class="text-gray-400 hover:text-red-500">&#x2715;</a></td>
                </tr>
                <tr>
                  <td class="px-3 py-2 flex items-center gap-1.5">
                    <span class="inline-block w-3 h-3 rounded-full border border-gray-300" style="background:#ffffff"></span> Biela
                  </td>
                  <td class="px-3 py-2">M</td>
                  <td class="px-3 py-2">39,99 €</td>
                  <td class="px-3 py-2"><span class="bg-yellow-100 text-yellow-700 px-1.5 py-0.5 rounded font-semibold">3</span></td>
                  <td class="px-3 py-2 text-right"><a href="#!" class="text-gray-400 hover:text-red-500">&#x2715;</a></td>
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


  <!-- modal: add variant -->
  <div id="modal-add-variant" class="fixed inset-0 bg-black/40 z-50 items-center justify-center px-4 py-8 overflow-y-auto">
    <div class="bg-white w-full max-w-lg mx-auto shadow-xl my-auto">
      <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200">
        <h2 class="text-lg font-bold">Pridať varianty</h2>
        <a href="#!" class="text-gray-400 hover:text-brand-dark transition-colors text-xl leading-none">&#x2715;</a>
      </div>
      <form action="#" method="post" class="px-6 py-6 space-y-5">
        <div>
          <label class="block text-sm font-medium mb-2">Farby <span class="text-red-500">*</span></label>
          <div class="flex flex-wrap gap-2">
            @foreach ([['biela', '#ffffff', 'Biela'], ['cierna', '#111111', 'Čierna'], ['modra', '#3b82f6', 'Modrá'], ['cervena', '#ef4444', 'Červená'], ['ruzova', '#f9a8d4', 'Ružová'], ['siva', '#9ca3af', 'Sivá'], ['bezova', '#d4b896', 'Béžová']] as [$val, $hex, $label])
              <label class="flex items-center gap-2 cursor-pointer border border-gray-200 px-3 py-1.5 text-sm hover:border-brand-dark has-[:checked]:border-brand-dark has-[:checked]:bg-brand-dark has-[:checked]:text-white transition-colors">
                <input type="checkbox" name="color" value="{{ $val }}" class="sr-only" />
                <span class="inline-block w-3 h-3 rounded-full border border-gray-300 shrink-0" style="background:{{ $hex }}"></span>
                {{ $label }}
              </label>
            @endforeach
          </div>
        </div>
        <div>
          <label class="block text-sm font-medium mb-2">Veľkosti <span class="text-red-500">*</span></label>
          <div class="flex flex-wrap gap-2">
            @foreach (['XS', 'S', 'M', 'L', 'XL', 'XXL'] as $size)
              <label class="flex items-center justify-center cursor-pointer border border-gray-200 w-12 h-10 text-sm font-medium hover:border-brand-dark has-[:checked]:border-brand-dark has-[:checked]:bg-brand-dark has-[:checked]:text-white transition-colors {{ $size === 'XXL' ? 'w-14' : '' }}">
                <input type="checkbox" name="size" value="{{ $size }}" class="sr-only" />
                {{ $size }}
              </label>
            @endforeach
          </div>
        </div>
        <div class="grid grid-cols-2 gap-4 pt-1 border-t border-gray-100">
          <div>
            <label class="block text-sm font-medium mb-1.5">Cena (€) <span class="text-red-500">*</span></label>
            <div class="relative">
              <input type="number" min="0" step="0.01" placeholder="0,00" required class="w-full border border-gray-300 px-3 py-2 pr-8 text-sm focus:outline-none focus:border-brand-dark" />
              <span class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm">€</span>
            </div>
          </div>
          <div>
            <label class="block text-sm font-medium mb-1.5">Sklad (ks) <span class="text-red-500">*</span></label>
            <input type="number" min="0" placeholder="0" required class="w-full border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:border-brand-dark" />
          </div>
          <p class="col-span-2 text-xs text-gray-400 -mt-2">Cena a sklad sa aplikujú na každú kombináciu farba × veľkosť.</p>
        </div>
        <div class="flex justify-end gap-3 pt-2 border-t border-gray-100">
          <a href="#!" class="px-4 py-2.5 border border-gray-300 text-sm font-medium hover:bg-gray-50 transition-colors">Zrušiť</a>
          <button type="submit" class="bg-brand-dark hover:bg-brand-accent text-white text-sm font-bold tracking-widest uppercase px-4 py-2.5 transition-colors">Pridať varianty</button>
        </div>
      </form>
    </div>
  </div>


  <!-- modals: delete product -->
  @foreach ([
    ['modal-delete-1', 'Saténová blúzka'],
    ['modal-delete-2', 'Džínsové nohavice'],
    ['modal-delete-3', 'Detská mikina'],
    ['modal-delete-4', 'Kožená kabelka'],
    ['modal-delete-5', 'Pánske tričko'],
  ] as [$id, $name])
    <div id="{{ $id }}" class="fixed inset-0 bg-black/40 z-50 items-center justify-center px-4">
      <div class="bg-white w-full max-w-sm shadow-xl">
        <div class="px-6 py-6">
          <h2 class="text-lg font-bold mb-2">Vymazať produkt</h2>
          <p class="text-sm text-gray-600 mb-6">Naozaj chcete vymazať produkt <strong>{{ $name }}</strong>? Táto akcia je nevratná.</p>
          <div class="flex justify-end gap-3">
            <a href="#!" class="px-4 py-2.5 border border-gray-300 text-sm font-medium hover:bg-gray-50 transition-colors">Zrušiť</a>
            <a href="#!" class="px-4 py-2.5 bg-red-600 hover:bg-red-700 text-white text-sm font-bold transition-colors">Vymazať</a>
          </div>
        </div>
      </div>
    </div>
  @endforeach

</x-admin.layout>
