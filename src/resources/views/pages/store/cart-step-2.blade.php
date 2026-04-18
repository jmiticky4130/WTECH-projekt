<x-store.layout title="Doprava a platba — Bellura.sk">

  <x-store.step-indicator :currentStep="2" />

  <main class="flex-1" x-data="cartStep2()" x-init="init()">
    <div class="max-w-7xl mx-auto px-4 py-8">
      <div class="flex flex-col lg:flex-row gap-8 lg:items-start">

        <div class="flex-1 min-w-0">
          <h1 class="text-2xl font-bold mb-6">Doprava a platba</h1>

          <section class="mb-8">
            <h2 class="text-base font-bold mb-3">Spôsob dopravy</h2>

            <template x-for="option in shippingOptions" :key="option.id">
              <label
                class="flex items-center gap-4 rounded p-4 mb-2 cursor-pointer transition-colors"
                :class="selectedShippingId === option.id ? 'border-2 border-brand-dark' : 'border border-gray-200 hover:border-gray-400'">
                <input type="radio" name="doprava" :checked="selectedShippingId === option.id"
                       @change="selectShipping(option.id)"
                       class="accent-brand-dark w-4 h-4 shrink-0" />
                <div class="flex-1 min-w-0">
                  <p class="font-semibold text-sm" x-text="option.label"></p>
                  <p class="text-xs text-gray-500 mt-0.5" x-text="option.desc ?? ''"></p>
                </div>
                <span class="text-sm font-semibold shrink-0" x-text="Number(option.price) === 0 ? 'Zadarmo' : fmtPrice(option.price)"></span>
              </label>
            </template>
          </section>

          <section>
            <h2 class="text-base font-bold mb-3">Spôsob platby</h2>

            <template x-for="option in paymentOptions" :key="option.id">
              <label
                class="flex items-center gap-4 rounded p-4 mb-2 transition-colors"
                :class="isPaymentDisabled(option.id)
                  ? 'border border-gray-100 opacity-40 cursor-not-allowed'
                  : selectedPaymentId === option.id
                    ? 'border-2 border-brand-dark cursor-pointer'
                    : 'border border-gray-200 hover:border-gray-400 cursor-pointer'">
                <input type="radio" name="platba" :checked="selectedPaymentId === option.id"
                       :disabled="isPaymentDisabled(option.id)"
                       @change="selectPayment(option.id)"
                       class="accent-brand-dark w-4 h-4 shrink-0" />
                <div class="flex-1 min-w-0">
                  <p class="font-semibold text-sm" x-text="option.label"></p>
                </div>
                <template x-if="Number(option.fee) > 0 && !isPaymentDisabled(option.id)">
                  <span class="text-sm font-semibold shrink-0" x-text="'+' + fmtPrice(option.fee)"></span>
                </template>
              </label>
            </template>
          </section>
        </div>

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
              <div class="flex justify-between" x-show="shipping">
                <span class="text-gray-600" x-text="'Doprava (' + shipping.label + ')'"></span>
                <span class="font-medium" x-text="Number(shipping.price) === 0 ? 'Zadarmo' : fmtPrice(shipping.price)"></span>
              </div>
              <div class="flex justify-between" x-show="payment && Number(payment.fee) > 0">
                <span class="text-gray-600" x-text="'Platba (' + payment.label + ')'"></span>
                <span class="font-medium" x-text="fmtPrice(payment.fee)"></span>
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

        selectedShippingId: null,
        selectedPaymentId: null,

        shippingOptions: @json($shippingMethods),
        paymentOptions: @json($paymentMethods),

        get shipping() {
          return this.shippingOptions.find(option => option.id === this.selectedShippingId) ?? null;
        },

        get payment() {
          return this.paymentOptions.find(option => option.id === this.selectedPaymentId) ?? null;
        },

        isPaymentDisabled(paymentId) {
          const payment = this.paymentOptions.find(option => option.id === paymentId);
          const shipping = this.shipping;

          return !!(payment?.requires_address && shipping?.type !== 'address');
        },

        firstEnabledPaymentId() {
          const enabled = this.paymentOptions.find(option => !this.isPaymentDisabled(option.id));

          return enabled ? enabled.id : null;
        },

        selectShipping(shippingId) {
          this.selectedShippingId = shippingId;

          if (this.isPaymentDisabled(this.selectedPaymentId)) {
            this.selectedPaymentId = this.firstEnabledPaymentId();
          }
        },

        selectPayment(paymentId) {
          if (this.isPaymentDisabled(paymentId)) {
            return;
          }

          this.selectedPaymentId = paymentId;
        },

        restoreSelection() {
          const saved = JSON.parse(sessionStorage.getItem('bellura_checkout') || '{}');

          const savedShippingId = Number(saved.shipping_method_id ?? 0);
          const hasSavedShipping = this.shippingOptions.some(option => option.id === savedShippingId);
          this.selectedShippingId = hasSavedShipping
            ? savedShippingId
            : (this.shippingOptions[0]?.id ?? null);

          const savedPaymentId = Number(saved.payment_method_id ?? 0);
          const hasSavedPayment = this.paymentOptions.some(option => option.id === savedPaymentId);

          if (hasSavedPayment && !this.isPaymentDisabled(savedPaymentId)) {
            this.selectedPaymentId = savedPaymentId;
          } else {
            this.selectedPaymentId = this.firstEnabledPaymentId();
          }
        },

        async init() {
          this.restoreSelection();
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
          return this.items.reduce((sum, item) => sum + parseFloat(item.price) * item.qty, 0);
        },

        get total() {
          const shippingPrice = Number(this.shipping?.price ?? 0);
          const paymentFee = this.payment && !this.isPaymentDisabled(this.payment.id)
            ? Number(this.payment.fee ?? 0)
            : 0;

          return this.subtotal + shippingPrice + paymentFee;
        },

        proceed() {
          if (!this.shipping || !this.payment || this.isPaymentDisabled(this.payment.id)) {
            return;
          }

          sessionStorage.setItem('bellura_checkout', JSON.stringify({
            shipping_method_id: this.shipping.id,
            payment_method_id: this.payment.id,
          }));

          window.location.href = '{{ route('store.cart.details') }}';
        },

        fmtPrice(price) {
          return parseFloat(price).toFixed(2).replace('.', ',') + ' €';
        },
      };
    }
  </script>

</x-store.layout>
