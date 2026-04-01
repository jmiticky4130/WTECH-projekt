<x-store.layout title="Dodacie údaje — Bellura.sk">

  <x-store.step-indicator :currentStep="3" />

  <main class="flex-1">
    <div class="max-w-7xl mx-auto px-4 py-8">
      <div class="flex flex-col lg:flex-row gap-8 lg:items-start">

        <div class="flex-1 min-w-0">
          <h1 class="text-2xl font-bold mb-6">Dodacie údaje</h1>

          <!-- contact info -->
          <section class="mb-6">
            <h2 class="text-base font-bold mb-4">Kontaktné údaje</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
              <div>
                <label class="block text-sm mb-1">E-mail <span class="text-gray-400">*</span></label>
                <input type="email" placeholder="vas@email.sk" class="w-full border border-gray-300 px-3 py-2.5 text-sm focus:outline-none focus:border-brand-dark" />
              </div>
              <div>
                <label class="block text-sm mb-1">Telefón <span class="text-gray-400">*</span></label>
                <input type="tel" placeholder="+421 9XX XXX XXX" class="w-full border border-gray-300 px-3 py-2.5 text-sm focus:outline-none focus:border-brand-dark" />
              </div>
            </div>
          </section>

          <!-- delivery address -->
          <section class="mb-6">
            <h2 class="text-base font-bold mb-4">Doručovacia adresa</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
              <div>
                <label class="block text-sm mb-1">Meno <span class="text-gray-400">*</span></label>
                <input type="text" placeholder="Meno" class="w-full border border-gray-300 px-3 py-2.5 text-sm focus:outline-none focus:border-brand-dark" />
              </div>
              <div>
                <label class="block text-sm mb-1">Priezvisko <span class="text-gray-400">*</span></label>
                <input type="text" placeholder="Priezvisko" class="w-full border border-gray-300 px-3 py-2.5 text-sm focus:outline-none focus:border-brand-dark" />
              </div>
            </div>
            <div class="mb-4">
              <label class="block text-sm mb-1">Ulica a číslo domu <span class="text-gray-400">*</span></label>
              <input type="text" placeholder="Ulica a číslo" class="w-full border border-gray-300 px-3 py-2.5 text-sm focus:outline-none focus:border-brand-dark" />
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-[1fr_7rem_10rem] gap-4">
              <div>
                <label class="block text-sm mb-1">Mesto <span class="text-gray-400">*</span></label>
                <input type="text" placeholder="Mesto" class="w-full border border-gray-300 px-3 py-2.5 text-sm focus:outline-none focus:border-brand-dark" />
              </div>
              <div>
                <label class="block text-sm mb-1">PSČ <span class="text-gray-400">*</span></label>
                <input type="text" placeholder="XXX XX" class="w-full border border-gray-300 px-3 py-2.5 text-sm focus:outline-none focus:border-brand-dark" />
              </div>
              <div>
                <label class="block text-sm mb-1">Krajina <span class="text-gray-400">*</span></label>
                <select class="w-full border border-gray-300 px-3 py-2.5 text-sm focus:outline-none focus:border-brand-dark bg-white appearance-none">
                  <option>Slovensko</option>
                  <option>Česká republika</option>
                  <option>Rakúsko</option>
                  <option>Maďarsko</option>
                  <option>Poľsko</option>
                </select>
              </div>
            </div>
          </section>

          <div class="space-y-3 mb-8">
            <label class="flex items-center gap-3 cursor-pointer text-sm">
              <input type="checkbox" checked class="w-4 h-4 accent-brand-dark shrink-0" />
              <span>Fakturačná adresa je rovnaká ako doručovacia</span>
            </label>
          </div>

          <!-- card details -->
          <section class="mb-8">
            <h2 class="text-base font-bold mb-3">Údaje o karte</h2>
            <div class="border border-gray-200 rounded p-4 space-y-4">
              <div>
                <label class="block text-sm mb-1">Číslo karty</label>
                <input type="text" placeholder="1234 5678 9012 3456" class="w-full border border-gray-300 px-3 py-2.5 text-sm focus:outline-none focus:border-brand-dark" />
              </div>
              <div>
                <label class="block text-sm mb-1">Meno držiteľa karty</label>
                <input type="text" placeholder="Meno Priezvisko" class="w-full border border-gray-300 px-3 py-2.5 text-sm focus:outline-none focus:border-brand-dark" />
              </div>
              <div class="grid grid-cols-2 gap-4">
                <div>
                  <label class="block text-sm mb-1">Platnosť do</label>
                  <input type="text" placeholder="MM / RR" class="w-full border border-gray-300 px-3 py-2.5 text-sm focus:outline-none focus:border-brand-dark" />
                </div>
                <div>
                  <label class="block text-sm mb-1">CVV</label>
                  <input type="text" placeholder="123" class="w-full border border-gray-300 px-3 py-2.5 text-sm focus:outline-none focus:border-brand-dark" />
                </div>
              </div>
            </div>
          </section>
        </div>

        <!-- order summary -->
        <x-store.order-summary
          :items="[
            ['name' => 'Kvetinové midi šaty × 1', 'price' => '39,99 €'],
            ['name' => 'Oversized bavlnené tričko × 2', 'price' => '29,98 €'],
          ]"
          subtotal="69,97 €"
          shippingLabel="Kuriér DPD"
          shipping="3,99 €"
          paymentLabel="Kartou"
          payment="Zadarmo"
          total="73,96 €"
          vat="12,33 €"
          shippingInfo="Kuriér DPD"
          paymentInfo="Kartou online"
          buttonText="Objednať a zaplatiť"
          :backLink="route('store.cart.shipping')"
          backText="← Späť na dopravu a platbu"
        />

      </div>
    </div>
  </main>

</x-store.layout>
