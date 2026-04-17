<x-store.layout title="Nákupný košík — Bellura.sk">

  <x-store.step-indicator :currentStep="1" />

  <main class="flex-1" x-data="cartPage()" x-init="init()">
    <div class="max-w-7xl mx-auto px-4 py-8">

      {{-- Empty state --}}
      <template x-if="!loading && items.length === 0">
        <div class="text-center py-20">
          <p class="text-lg text-gray-500 mb-6">Váš košík je prázdny</p>
          <a href="{{ route('home') }}"
             class="inline-block border border-gray-300 px-6 py-2 text-sm hover:bg-gray-50 transition-colors">
            ← Pokračovať v nákupe
          </a>
        </div>
      </template>

      {{-- Cart content --}}
      <div x-show="loading || items.length > 0" class="flex flex-col lg:flex-row gap-8 lg:items-start">

        {{-- Items column --}}
        <div class="flex-1 min-w-0">
          <h1 class="text-2xl font-bold mb-6">
            Nákupný košík
            <span class="text-base font-normal text-gray-500 ml-2"
                  x-text="loading ? '' : '(' + totalQty + ' ' + (totalQty === 1 ? 'položka' : (totalQty < 5 ? 'položky' : 'položiek')) + ')'">
            </span>
          </h1>

          {{-- Loading skeleton --}}
          <div class="border border-gray-200" x-show="loading">
            <div class="px-4 py-10 text-center text-gray-400 text-sm">Načítavam košík...</div>
          </div>

          {{-- Items list --}}
          <div class="border border-gray-200" x-show="!loading && items.length > 0">
            {{-- Desktop header --}}
            <div class="hidden md:grid grid-cols-[1fr_5rem_6rem_8rem_7rem_5rem] px-4 py-3 border-b border-gray-200 bg-gray-50 text-xs font-bold uppercase tracking-wider text-gray-500">
              <span>Produkt</span>
              <span class="text-center">Veľkosť</span>
              <span class="text-center">Cena</span>
              <span class="text-center">Množstvo</span>
              <span class="text-right pr-4">Spolu</span>
              <span class="text-center">Odstrániť</span>
            </div>

            <template x-for="(item, index) in items" :key="item.variant_id">
              <div :class="{ 'border-b border-gray-200': index < items.length - 1 }" class="px-4 py-4">

                {{-- Desktop row --}}
                <div class="hidden md:grid grid-cols-[1fr_5rem_6rem_8rem_7rem_5rem] items-center">
                  <div class="flex gap-3 items-center">
                    <a :href="'/produkt/' + item.slug" class="w-16 h-24 bg-gray-200 shrink-0 overflow-hidden relative block">
                      <img :src="item.image_path ? '/' + item.image_path : ''"
                           class="w-full absolute top-1/2 -translate-y-1/2"
                           :alt="item.name">
                    </a>
                    <div>
                      <p class="text-xs text-gray-400" x-text="item.brand_name ?? ''"></p>
                      <a :href="'/produkt/' + item.slug" class="text-sm font-bold hover:underline" x-text="item.name"></a>
                      <p class="text-xs text-gray-500" x-text="item.color_name ? 'Farba: ' + item.color_name : ''"></p>
                    </div>
                  </div>
                  <span class="text-sm text-center" x-text="item.size"></span>
                  <span class="text-sm text-center" x-text="fmtPrice(item.price)"></span>
                  <div class="flex justify-center">
                    <div class="flex items-center border border-gray-300">
                      <button @click="updateQty(item, -1)"
                              class="px-2.5 py-1 hover:bg-gray-100 transition-colors text-sm">&minus;</button>
                      <span class="px-3 py-1 border-x border-gray-300 text-sm min-w-[2rem] text-center"
                            x-text="item.qty"></span>
                      <button @click="updateQty(item, 1)"
                              class="px-2.5 py-1 hover:bg-gray-100 transition-colors text-sm">+</button>
                    </div>
                  </div>
                  <span class="text-sm font-bold text-right pr-4"
                        x-text="fmtPrice(item.price * item.qty)"></span>
                  <div class="flex justify-center">
                    <button @click="removeItem(item)" class="hover:opacity-60 transition-opacity">
                      <img src="{{ asset('icons/trash.svg') }}" class="w-5 h-5" alt="Odstrániť" />
                    </button>
                  </div>
                </div>

                {{-- Mobile row --}}
                <div class="md:hidden flex gap-3">
                  <a :href="'/produkt/' + item.slug" class="w-20 h-28 bg-gray-200 shrink-0 overflow-hidden relative block">
                    <img :src="item.image_path ? '/' + item.image_path : ''"
                         class="w-full absolute top-1/2 -translate-y-1/2"
                         :alt="item.name">
                  </a>
                  <div class="flex-1 min-w-0">
                    <p class="text-xs text-gray-400" x-text="item.brand_name ?? ''"></p>
                    <a :href="'/produkt/' + item.slug" class="text-sm font-bold hover:underline block" x-text="item.name"></a>
                    <p class="text-xs text-gray-500 mb-2"
                       x-text="item.color_name ? 'Farba: ' + item.color_name : ''"></p>
                    <div class="flex items-center justify-between mb-2 text-xs text-gray-500">
                      <span>Veľkosť: <span class="text-brand-dark font-medium" x-text="item.size"></span></span>
                      <span x-text="fmtPrice(item.price)"></span>
                    </div>
                    <div class="flex items-center gap-3">
                      <div class="flex items-center border border-gray-300">
                        <button @click="updateQty(item, -1)"
                                class="px-2.5 py-1 hover:bg-gray-100 transition-colors text-sm">&minus;</button>
                        <span class="px-3 py-1 border-x border-gray-300 text-sm min-w-[2rem] text-center"
                              x-text="item.qty"></span>
                        <button @click="updateQty(item, 1)"
                                class="px-2.5 py-1 hover:bg-gray-100 transition-colors text-sm">+</button>
                      </div>
                      <span class="text-sm font-bold" x-text="fmtPrice(item.price * item.qty)"></span>
                      <button @click="removeItem(item)" class="ml-auto hover:opacity-60 transition-opacity">
                        <img src="{{ asset('icons/trash.svg') }}" class="w-5 h-5" alt="Odstrániť" />
                      </button>
                    </div>
                  </div>
                </div>

              </div>
            </template>
          </div>
        </div>

        {{-- Order summary --}}
        <aside class="w-full lg:w-80 shrink-0 lg:sticky lg:top-4">
          <div class="border border-gray-200 p-5">
            <h2 class="text-base font-bold mb-4">Súhrn objednávky</h2>

            <div class="border-t border-gray-200 my-4"></div>

            <div class="flex justify-between items-baseline mb-1">
              <span class="text-base font-bold">Celkom</span>
              <span class="text-xl font-bold" x-text="loading ? '—' : fmtPrice(subtotal)"></span>
            </div>
            <p class="text-xs text-gray-500 mb-5">Vrátane DPH <span x-text="loading ? '' : fmtPrice(subtotal / 1.2 * 0.2)"></span></p>

            <a href="{{ route('store.cart.shipping') }}"
               class="block w-full bg-brand-dark hover:bg-brand-accent text-white font-bold text-sm tracking-widest py-4 transition-colors uppercase mb-3 text-center"
               :class="{ 'opacity-50 pointer-events-none': items.length === 0 }">
              Pokračovať
            </a>

            <div class="text-center">
              <a href="{{ route('home') }}"
                 class="text-sm text-gray-500 hover:text-brand-dark transition-colors">
                ← Pokračovať v nákupe
              </a>
            </div>
          </div>
        </aside>

      </div>
    </div>
  </main>

  <script>
    function cartPage() {
      return {
        isAuth: window.__bellura?.isAuth ?? false,
        csrfToken: window.__bellura?.csrfToken ?? '',
        items: [],
        loading: true,

        async init() {
          await this.loadCart();
        },

        async loadCart() {
          this.loading = true;
          try {
            if (this.isAuth) {
              const res = await fetch('/cart/data', { headers: { Accept: 'application/json' } });
              const data = await res.json();
              this.items = data.items ?? [];
            } else {
              const stored = JSON.parse(localStorage.getItem('bellura_cart') || '[]');
              if (stored.length === 0) {
                this.items = [];
                this.loading = false;
                return;
              }
              const params = new URLSearchParams();
              stored.forEach((it, i) => {
                params.append(`items[${i}][variant_id]`, it.variant_id);
                params.append(`items[${i}][qty]`, it.qty);
              });
              const res = await fetch('/cart/data?' + params, { headers: { Accept: 'application/json' } });
              const data = await res.json();
              this.items = (data.items ?? []).map(item => {
                const found = stored.find(s => s.variant_id == item.variant_id);
                return { ...item, qty: found ? found.qty : (item.qty ?? 1) };
              });
            }
          } catch (e) {
            this.items = [];
          }
          this.loading = false;
        },

        get subtotal() {
          return this.items.reduce((s, it) => s + parseFloat(it.price) * it.qty, 0);
        },

        get totalQty() {
          return this.items.reduce((s, it) => s + it.qty, 0);
        },

        fmtPrice(p) {
          return parseFloat(p).toFixed(2).replace('.', ',') + ' €';
        },

        async updateQty(item, delta) {
          const newQty = item.qty + delta;
          if (newQty < 1) return;
          item.qty = newQty;

          if (this.isAuth) {
            await fetch(`/cart/item/${item.item_id}`, {
              method: 'PATCH',
              headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': this.csrfToken,
                Accept: 'application/json',
              },
              body: JSON.stringify({ qty: newQty }),
            });
          } else {
            this.syncLocalStorage();
          }
          window.dispatchEvent(new CustomEvent('cart-updated', { detail: { count: this.totalQty } }));
        },

        async removeItem(item) {
          this.items = this.items.filter(i => i !== item);

          if (this.isAuth) {
            await fetch(`/cart/item/${item.item_id}`, {
              method: 'DELETE',
              headers: { 'X-CSRF-TOKEN': this.csrfToken, Accept: 'application/json' },
            });
          } else {
            this.syncLocalStorage();
          }
          window.dispatchEvent(new CustomEvent('cart-updated', { detail: { count: this.totalQty } }));
        },

        syncLocalStorage() {
          localStorage.setItem('bellura_cart', JSON.stringify(
            this.items.map(i => ({ variant_id: i.variant_id, qty: i.qty }))
          ));
        },
      };
    }
  </script>

</x-store.layout>
