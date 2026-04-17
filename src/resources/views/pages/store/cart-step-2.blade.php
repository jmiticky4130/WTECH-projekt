<x-store.layout title="Doprava a platba — Bellura.sk">

  <x-store.step-indicator :currentStep="2" />

  <main class="flex-1" x-data="cartStep2()" x-init="init()">
    <div class="max-w-7xl mx-auto px-4 py-8">
      <div class="flex flex-col lg:flex-row gap-8 lg:items-start">

        <div class="flex-1 min-w-0">
          <h1 class="text-2xl font-bold mb-6">Doprava a platba</h1>

          <!-- shipping methods -->
          <section class="mb-8">
            <h2 class="text-base font-bold mb-3">Spôsob dopravy</h2>

            <template x-for="(option, i) in shippingOptions" :key="i">
              <label
                class="flex items-center gap-4 rounded p-4 mb-2 cursor-pointer transition-colors"
                :class="selectedShipping === i ? 'border-2 border-brand-dark' : 'border border-gray-200 hover:border-gray-400'">
                <input type="radio" name="doprava" :checked="selectedShipping === i"
                       @change="selectShipping(i)"
                       class="accent-brand-dark w-4 h-4 shrink-0" />
                <div class="flex-1 min-w-0">
                  <p class="font-semibold text-sm" x-text="option.label"></p>
                  <p class="text-xs text-gray-500 mt-0.5" x-text="option.desc"></p>
                </div>
                <span class="text-sm font-semibold shrink-0" x-text="option.price === 0 ? 'Zadarmo' : fmtPrice(option.price)"></span>
              </label>
            </template>
          </section>

          <!-- payment methods -->
          <section>
            <h2 class="text-base font-bold mb-3">Spôsob platby</h2>

            <template x-for="(option, i) in paymentOptions" :key="i">
              <label
                class="flex items-center gap-4 rounded p-4 mb-2 transition-colors"
                :class="isPaymentDisabled(i)
                  ? 'border border-gray-100 opacity-40 cursor-not-allowed'
                  : selectedPayment === i
                    ? 'border-2 border-brand-dark cursor-pointer'
                    : 'border border-gray-200 hover:border-gray-400 cursor-pointer'">
                <input type="radio" name="platba" :checked="selectedPayment === i"
                       :disabled="isPaymentDisabled(i)"
                       @change="if (!isPaymentDisabled(i)) selectedPayment = i"
                       class="accent-brand-dark w-4 h-4 shrink-0" />
                <div class="flex-1 min-w-0">
                  <p class="font-semibold text-sm" x-text="option.label"></p>
                </div>
                <template x-if="option.surcharge > 0 && !isPaymentDisabled(i)">
                  <span class="text-sm font-semibold shrink-0" x-text="'+' + fmtPrice(option.surcharge)"></span>
                </template>
              </label>
            </template>
          </section>
        </div>

        <!-- order summary -->
        <aside class="w-full lg:w-80 shrink-0 lg:sticky lg:top-4">
          <div class="border border-gray-200 p-5">
            <h2 class="text-base font-bold mb-4">Súhrn objednávky</h2>

            <div class="space-y-2 mb-4" x-show="!loading && items.length > 0">
              <template x-for="item in items" :key="item.variant_id">
                <div class="flex justify-between text-sm">
                  <span class="text-gray-600" x-text="item.name + ' × ' + item.qty"></span>
                  <span class="font-medium shrink-0 ml-2" x-text="fmtPrice(item.price * item.qty)"></span>
                </div>
              </template>
            </div>

            <div class="border-t border-gray-200 my-4"></div>

            <div class="space-y-2 text-sm mb-4">
              <div class="flex justify-between">
                <span class="text-gray-600">Medzisúčet</span>
                <span class="font-medium" x-text="loading ? '—' : fmtPrice(subtotal)"></span>
              </div>
              <div class="flex justify-between">
                <span class="text-gray-600" x-text="'Doprava (' + shippingOptions[selectedShipping]?.label + ')'"></span>
                <span class="font-medium" x-text="shippingOptions[selectedShipping]?.price === 0 ? 'Zadarmo' : fmtPrice(shippingOptions[selectedShipping]?.price ?? 0)"></span>
              </div>
              <div class="flex justify-between" x-show="paymentOptions[selectedPayment]?.surcharge > 0">
                <span class="text-gray-600" x-text="'Platba (' + paymentOptions[selectedPayment]?.label + ')'"></span>
                <span class="font-medium" x-text="fmtPrice(paymentOptions[selectedPayment]?.surcharge ?? 0)"></span>
              </div>
            </div>

            <div class="border-t border-gray-200 my-4"></div>

            <div class="flex justify-between items-baseline mb-1">
              <span class="text-base font-bold">Celkom</span>
              <span class="text-xl font-bold" x-text="loading ? '—' : fmtPrice(total)"></span>
            </div>
            <p class="text-xs text-gray-500 mb-5">
              Vrátane DPH <span x-text="loading ? '' : fmtPrice(total / 1.2 * 0.2)"></span>
            </p>

            <button @click="proceed()"
               class="block w-full bg-brand-dark hover:bg-brand-accent text-white font-bold text-sm tracking-widest py-4 transition-colors uppercase mb-3 text-center">
              Pokračovať
            </button>

            <div class="text-center">
              <a href="{{ route('store.cart') }}" class="text-sm text-gray-500 hover:text-brand-dark transition-colors">← Späť na košík</a>
            </div>
          </div>
        </aside>

      </div>
    </div>
  </main>

  <script>
    function cartStep2() {
      return {
        isAuth: window.__bellura?.isAuth ?? false,
        csrfToken: window.__bellura?.csrfToken ?? '',
        items: [],
        loading: true,
        selectedShipping: 0,
        selectedPayment: 0,

        shippingOptions: [
          { label: 'Kuriér DPD', desc: 'Doručenie do 2–3 pracovných dní na adresu', price: 3.99, type: 'address' },
          { label: 'Slovenská pošta', desc: 'Doručenie do 3–5 pracovných dní', price: 2.49, type: 'address' },
          { label: 'Zásielkovňa (výdajné miesto)', desc: 'Vyzdvihnutie na najbližšom výdajnom mieste', price: 1.99, type: 'pickup_point' },
          { label: 'Osobný odber — pobočka Bratislava', desc: 'Obchodná 12, Bratislava — pripravené do 24h', price: 0, type: 'personal_pickup' },
        ],

        paymentOptions: [
          { label: 'Platba kartou online', surcharge: 0, type: 'card', requires: null },
          { label: 'Dobierka (platba pri prevzatí)', surcharge: 1.50, type: 'cod', requires: 'address' },
          { label: 'Google Pay', surcharge: 0, type: 'google_pay', requires: null },
          { label: 'Bankový prevod', surcharge: 0, type: 'bank_transfer', requires: null },
        ],

        isPaymentDisabled(paymentIndex) {
          const req = this.paymentOptions[paymentIndex]?.requires;
          if (!req) return false;
          return this.shippingOptions[this.selectedShipping]?.type !== req;
        },

        selectShipping(i) {
          this.selectedShipping = i;
          if (this.isPaymentDisabled(this.selectedPayment)) {
            this.selectedPayment = 0;
          }
        },

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
              if (stored.length === 0) { this.loading = false; return; }
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

        get total() {
          const shipping = this.shippingOptions[this.selectedShipping]?.price ?? 0;
          const payment = this.isPaymentDisabled(this.selectedPayment) ? 0 : (this.paymentOptions[this.selectedPayment]?.surcharge ?? 0);
          return this.subtotal + shipping + payment;
        },

        proceed() {
          sessionStorage.setItem('bellura_checkout', JSON.stringify({
            shipping: this.selectedShipping,
            payment: this.selectedPayment,
          }));
          window.location.href = '{{ route('store.cart.details') }}';
        },

        fmtPrice(p) {
          return parseFloat(p).toFixed(2).replace('.', ',') + ' €';
        },
      };
    }
  </script>

</x-store.layout>
