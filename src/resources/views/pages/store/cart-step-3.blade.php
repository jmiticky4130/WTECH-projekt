<x-store.layout title="Dodacie údaje — Bellura.sk">

  <x-store.step-indicator :currentStep="3" />

  <main class="flex-1" x-data="cartStep3()" x-init="init()">
    <div class="max-w-7xl mx-auto px-4 py-8">
      <div class="flex flex-col lg:flex-row gap-8 lg:items-start">

        <div class="flex-1 min-w-0">
          <h1 class="text-2xl font-bold mb-6">Dodacie údaje</h1>

          {{-- ── PICKUP POINT: výdajné miesto dropdown ─────────────────────── --}}
          <section class="mb-6" x-show="isPickupPoint">
            <h2 class="text-base font-bold mb-4">Dodacie údaje</h2>
            <div>
              <label class="block text-sm mb-1">Vyberte výdajné miesto <span class="text-red-500">*</span></label>
              <select x-model="f.pickupPoint"
                      :class="err.pickupPoint ? 'border-red-500' : 'border-gray-300'"
                      class="w-full border px-3 py-2.5 text-sm focus:outline-none focus:border-brand-dark bg-white appearance-none">
                <option value="">— Vyberte miesto —</option>
                <option>Zásielkovňa Bratislava – Obchodná 5</option>
                <option>Zásielkovňa Bratislava – Račianska 12</option>
                <option>Zásielkovňa Košice – Hlavná 30</option>
                <option>Zásielkovňa Žilina – Mariánske nám. 1</option>
              </select>
              <p class="text-xs text-red-500 mt-1" x-show="err.pickupPoint" x-text="err.pickupPoint"></p>
            </div>
          </section>

          {{-- ── PERSONAL PICKUP: store info ──────────────────────────────── --}}
          <div class="border border-gray-200 rounded p-4 space-y-1 text-sm mb-6" x-show="isPersonalPickup">
            <p class="font-semibold">Pobočka Bratislava</p>
            <p class="text-gray-600">Obchodná 12, 811 06 Bratislava</p>
            <p class="text-gray-500">Po – Pi: 9:00 – 19:00 &nbsp;|&nbsp; So: 9:00 – 14:00</p>
            <p class="text-gray-500">Objednávka bude pripravená do 24 hodín od potvrdenia.</p>
          </div>

          {{-- ── MAIN FORM — "Dodacie údaje" for ADDRESS, "Fakturačné údaje" for others ── --}}
          <section class="mb-6">
            <h2 class="text-base font-bold mb-4"
                x-text="needsAddress ? 'Dodacie údaje' : 'Fakturačné údaje'"></h2>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
              <div>
                <label class="block text-sm mb-1">Meno <span class="text-red-500">*</span></label>
                <input type="text" x-model="f.firstName" placeholder="Meno"
                       :class="err.firstName ? 'border-red-500' : 'border-gray-300'"
                       class="w-full border px-3 py-2.5 text-sm focus:outline-none focus:border-brand-dark" />
                <p class="text-xs text-red-500 mt-1" x-show="err.firstName" x-text="err.firstName"></p>
              </div>
              <div>
                <label class="block text-sm mb-1">Priezvisko <span class="text-red-500">*</span></label>
                <input type="text" x-model="f.lastName" placeholder="Priezvisko"
                       :class="err.lastName ? 'border-red-500' : 'border-gray-300'"
                       class="w-full border px-3 py-2.5 text-sm focus:outline-none focus:border-brand-dark" />
                <p class="text-xs text-red-500 mt-1" x-show="err.lastName" x-text="err.lastName"></p>
              </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
              <div>
                <label class="block text-sm mb-1">E-mail <span class="text-red-500">*</span></label>
                <input type="email" x-model="f.email" placeholder="vas@email.sk"
                       :readonly="isAuth"
                       :class="[err.email ? 'border-red-500' : 'border-gray-300', isAuth ? 'bg-gray-50 text-gray-500 cursor-default' : '']"
                       class="w-full border px-3 py-2.5 text-sm focus:outline-none focus:border-brand-dark" />
                <p class="text-xs text-red-500 mt-1" x-show="err.email" x-text="err.email"></p>
              </div>
              <div>
                <label class="block text-sm mb-1">Telefón</label>
                <input type="tel" x-model="f.phone" placeholder="+421 9XX XXX XXX"
                       :class="err.phone ? 'border-red-500' : 'border-gray-300'"
                       class="w-full border px-3 py-2.5 text-sm focus:outline-none focus:border-brand-dark" />
                <p class="text-xs text-red-500 mt-1" x-show="err.phone" x-text="err.phone"></p>
              </div>
            </div>

            <div class="mb-4">
              <label class="block text-sm mb-1">Ulica a číslo domu <span class="text-red-500">*</span></label>
              <input type="text" x-model="f.street" placeholder="Ulica a číslo"
                     :class="err.street ? 'border-red-500' : 'border-gray-300'"
                     class="w-full border px-3 py-2.5 text-sm focus:outline-none focus:border-brand-dark" />
              <p class="text-xs text-red-500 mt-1" x-show="err.street" x-text="err.street"></p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-[1fr_7rem_10rem] gap-4">
              <div>
                <label class="block text-sm mb-1">Mesto <span class="text-red-500">*</span></label>
                <input type="text" x-model="f.city" placeholder="Mesto"
                       :class="err.city ? 'border-red-500' : 'border-gray-300'"
                       class="w-full border px-3 py-2.5 text-sm focus:outline-none focus:border-brand-dark" />
                <p class="text-xs text-red-500 mt-1" x-show="err.city" x-text="err.city"></p>
              </div>
              <div>
                <label class="block text-sm mb-1">PSČ <span class="text-red-500">*</span></label>
                <input type="text" x-model="f.zip" placeholder="XXX XX"
                       :class="err.zip ? 'border-red-500' : 'border-gray-300'"
                       class="w-full border px-3 py-2.5 text-sm focus:outline-none focus:border-brand-dark" />
                <p class="text-xs text-red-500 mt-1" x-show="err.zip" x-text="err.zip"></p>
              </div>
              <div>
                <label class="block text-sm mb-1">Krajina <span class="text-red-500">*</span></label>
                <select x-model="f.country"
                        class="w-full border border-gray-300 px-3 py-2.5 text-sm focus:outline-none focus:border-brand-dark bg-white appearance-none">
                  <option>Slovensko</option>
                  <option>Česká republika</option>
                  <option>Rakúsko</option>
                  <option>Maďarsko</option>
                  <option>Poľsko</option>
                </select>
              </div>
            </div>
          </section>

          {{-- ── ADDRESS ONLY: optional separate billing form ─────────────── --}}
          <section class="mb-6" x-show="needsAddress">
            <label class="flex items-center gap-2 text-sm font-medium cursor-pointer select-none mb-4">
              <input type="checkbox" x-model="f.billingSame" class="accent-brand-dark w-4 h-4" />
              <span>Fakturačná adresa je rovnaká ako dodacia</span>
            </label>

            <div x-show="!f.billingSame">
              <h2 class="text-base font-bold mb-4">Fakturačné údaje</h2>

              <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
                <div>
                  <label class="block text-sm mb-1">Meno <span class="text-red-500">*</span></label>
                  <input type="text" x-model="f.billingFirstName" placeholder="Meno"
                         :class="err.billingFirstName ? 'border-red-500' : 'border-gray-300'"
                         class="w-full border px-3 py-2.5 text-sm focus:outline-none focus:border-brand-dark" />
                  <p class="text-xs text-red-500 mt-1" x-show="err.billingFirstName" x-text="err.billingFirstName"></p>
                </div>
                <div>
                  <label class="block text-sm mb-1">Priezvisko <span class="text-red-500">*</span></label>
                  <input type="text" x-model="f.billingLastName" placeholder="Priezvisko"
                         :class="err.billingLastName ? 'border-red-500' : 'border-gray-300'"
                         class="w-full border px-3 py-2.5 text-sm focus:outline-none focus:border-brand-dark" />
                  <p class="text-xs text-red-500 mt-1" x-show="err.billingLastName" x-text="err.billingLastName"></p>
                </div>
              </div>

              <div class="mb-4">
                <label class="block text-sm mb-1">Ulica a číslo domu <span class="text-red-500">*</span></label>
                <input type="text" x-model="f.billingStreet" placeholder="Ulica a číslo"
                       :class="err.billingStreet ? 'border-red-500' : 'border-gray-300'"
                       class="w-full border px-3 py-2.5 text-sm focus:outline-none focus:border-brand-dark" />
                <p class="text-xs text-red-500 mt-1" x-show="err.billingStreet" x-text="err.billingStreet"></p>
              </div>

              <div class="grid grid-cols-1 sm:grid-cols-[1fr_7rem_10rem] gap-4">
                <div>
                  <label class="block text-sm mb-1">Mesto <span class="text-red-500">*</span></label>
                  <input type="text" x-model="f.billingCity" placeholder="Mesto"
                         :class="err.billingCity ? 'border-red-500' : 'border-gray-300'"
                         class="w-full border px-3 py-2.5 text-sm focus:outline-none focus:border-brand-dark" />
                  <p class="text-xs text-red-500 mt-1" x-show="err.billingCity" x-text="err.billingCity"></p>
                </div>
                <div>
                  <label class="block text-sm mb-1">PSČ <span class="text-red-500">*</span></label>
                  <input type="text" x-model="f.billingZip" placeholder="XXX XX"
                         :class="err.billingZip ? 'border-red-500' : 'border-gray-300'"
                         class="w-full border px-3 py-2.5 text-sm focus:outline-none focus:border-brand-dark" />
                  <p class="text-xs text-red-500 mt-1" x-show="err.billingZip" x-text="err.billingZip"></p>
                </div>
                <div>
                  <label class="block text-sm mb-1">Krajina <span class="text-red-500">*</span></label>
                  <select x-model="f.billingCountry"
                          :class="err.billingCountry ? 'border-red-500' : 'border-gray-300'"
                          class="w-full border px-3 py-2.5 text-sm focus:outline-none focus:border-brand-dark bg-white appearance-none">
                    <option>Slovensko</option>
                    <option>Česká republika</option>
                    <option>Rakúsko</option>
                    <option>Maďarsko</option>
                    <option>Poľsko</option>
                  </select>
                  <p class="text-xs text-red-500 mt-1" x-show="err.billingCountry" x-text="err.billingCountry"></p>
                </div>
              </div>
            </div>
          </section>

          {{-- ── Payment sections ─────────────────────────────────────────── --}}
          <section class="mb-8" x-show="isCardPayment">
            <h2 class="text-base font-bold mb-3">Údaje o karte</h2>
            <div class="border border-gray-200 rounded p-4 space-y-4">
              <div>
                <label class="block text-sm mb-1">Číslo karty <span class="text-red-500">*</span></label>
                <input type="text" x-model="f.cardNumber" placeholder="1234 5678 9012 3456" maxlength="19"
                       @input="f.cardNumber = formatCardNumber($event.target.value)"
                       :class="err.cardNumber ? 'border-red-500' : 'border-gray-300'"
                       class="w-full border px-3 py-2.5 text-sm focus:outline-none focus:border-brand-dark" />
                <p class="text-xs text-red-500 mt-1" x-show="err.cardNumber" x-text="err.cardNumber"></p>
              </div>
              <div>
                <label class="block text-sm mb-1">Meno držiteľa karty <span class="text-red-500">*</span></label>
                <input type="text" x-model="f.cardName" placeholder="Meno Priezvisko"
                       :class="err.cardName ? 'border-red-500' : 'border-gray-300'"
                       class="w-full border px-3 py-2.5 text-sm focus:outline-none focus:border-brand-dark" />
                <p class="text-xs text-red-500 mt-1" x-show="err.cardName" x-text="err.cardName"></p>
              </div>
              <div class="grid grid-cols-2 gap-4">
                <div>
                  <label class="block text-sm mb-1">Platnosť do <span class="text-red-500">*</span></label>
                  <input type="text" x-model="f.cardExpiry" placeholder="MM / RR" maxlength="7"
                         @input="f.cardExpiry = formatExpiry($event.target.value)"
                         :class="err.cardExpiry ? 'border-red-500' : 'border-gray-300'"
                         class="w-full border px-3 py-2.5 text-sm focus:outline-none focus:border-brand-dark" />
                  <p class="text-xs text-red-500 mt-1" x-show="err.cardExpiry" x-text="err.cardExpiry"></p>
                </div>
                <div>
                  <label class="block text-sm mb-1">CVV <span class="text-red-500">*</span></label>
                  <input type="text" x-model="f.cardCvv" placeholder="123" maxlength="4"
                         @input="f.cardCvv = f.cardCvv.replace(/\D/g, '')"
                         :class="err.cardCvv ? 'border-red-500' : 'border-gray-300'"
                         class="w-full border px-3 py-2.5 text-sm focus:outline-none focus:border-brand-dark" />
                  <p class="text-xs text-red-500 mt-1" x-show="err.cardCvv" x-text="err.cardCvv"></p>
                </div>
              </div>
            </div>
          </section>

          <section class="mb-8" x-show="isBankTransfer">
            <h2 class="text-base font-bold mb-3">Bankový prevod</h2>
            <div class="border border-gray-200 rounded p-4 space-y-2 text-sm">
              <p class="text-gray-600">Po odoslaní objednávky vám zašleme platobné údaje e-mailom.</p>
              <p><span class="text-gray-500">IBAN:</span> <span class="font-medium">SK12 3456 7890 1234 5678 90</span></p>
              <p><span class="text-gray-500">BIC/SWIFT:</span> <span class="font-medium">TATRSKBX</span></p>
              <p class="text-gray-500 text-xs mt-2">Tovar bude odoslaný po pripísaní platby na účet.</p>
            </div>
          </section>

          <section class="mb-8" x-show="isCashOnDelivery">
            <h2 class="text-base font-bold mb-3">Dobierka</h2>
            <div class="border border-gray-200 rounded p-4 text-sm text-gray-600">
              <p>Platbu uhradíte pri prevzatí zásielky od kuriéra. Pripravte si sumu <span class="font-medium" x-text="fmtPrice(total)"></span>.</p>
            </div>
          </section>

        </div>

        {{-- ── Order summary sidebar ────────────────────────────────────────── --}}
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
            <p class="text-xs text-gray-500 mb-4">
              Vrátane DPH <span x-text="loading ? '' : fmtPrice(total / 1.2 * 0.2)"></span>
            </p>

            <div class="text-xs text-gray-500 space-y-1 mb-5">
              <p x-text="'Doprava: ' + (shipping?.label ?? '—')"></p>
              <p x-text="'Platba: ' + (payment?.label ?? '—')"></p>
            </div>

            <button @click="submit()"
                    :disabled="submitting"
                    :class="submitting ? 'opacity-70 cursor-not-allowed' : ''"
                    class="w-full bg-brand-dark hover:bg-brand-accent text-white font-bold text-sm tracking-widest py-4 transition-colors uppercase mb-2">
              <span x-text="submitting ? 'Odosielam...' : 'Objednať a zaplatiť'"></span>
            </button>
            <p class="text-xs text-red-500 mb-3" x-show="submitError" x-text="submitError"></p>

            <div class="text-center">
              <a href="{{ route('store.cart.shipping') }}" class="text-sm text-gray-500 hover:text-brand-dark transition-colors">← Späť na dopravu a platbu</a>
            </div>
          </div>
        </aside>

      </div>
    </div>
  </main>

  <script>
    function cartStep3() {
      return {
        isAuth: window.__bellura?.isAuth ?? false,
        csrfToken: window.__bellura?.csrfToken ?? '',
        items: [],
        loading: true,
        submitting: false,
        submitError: '',

        shippingOptions: @json($shippingMethods),
        paymentOptions: @json($paymentMethods),

        selectedShippingId: null,
        selectedPaymentId: null,

        prefill: @json($prefill),

        f: {
          firstName: '', lastName: '', email: '', phone: '',
          street: '', city: '', zip: '', country: 'Slovensko',
          billingSame: true,
          billingFirstName: '', billingLastName: '',
          billingStreet: '', billingCity: '', billingZip: '', billingCountry: 'Slovensko',
          pickupPoint: '',
          cardNumber: '', cardName: '', cardExpiry: '', cardCvv: '',
        },

        err: {},

        get shipping() {
          return this.shippingOptions.find(o => o.id === this.selectedShippingId) ?? null;
        },

        get payment() {
          return this.paymentOptions.find(o => o.id === this.selectedPaymentId) ?? null;
        },

        get needsAddress() {
          return this.shipping?.type === @json(\App\Enums\ShippingType::ADDRESS->value);
        },

        get isPickupPoint() {
          return this.shipping?.type === @json(\App\Enums\ShippingType::PICKUP_POINT->value);
        },

        get isPersonalPickup() {
          return this.shipping?.type === @json(\App\Enums\ShippingType::PERSONAL_PICKUP->value);
        },

        get isCardPayment() {
          return this.payment?.label?.toLowerCase().includes('kart');
        },

        get isBankTransfer() {
          return this.payment?.label?.toLowerCase().includes('prevod');
        },

        get isCashOnDelivery() {
          return this.payment?.label?.toLowerCase().includes('dobierka');
        },

        isPaymentDisabled(paymentId) {
          const allowed = this.shipping?.allowed_payment_ids ?? [];
          if (allowed.length === 0) return false;
          return !allowed.includes(paymentId);
        },

        firstEnabledPaymentId() {
          return this.paymentOptions.find(o => !this.isPaymentDisabled(o.id))?.id ?? null;
        },

        restoreSelection() {
          const saved = JSON.parse(sessionStorage.getItem('bellura_checkout') || '{}');

          const savedShippingId = Number(saved.shipping_method_id ?? 0);
          this.selectedShippingId = this.shippingOptions.some(o => o.id === savedShippingId)
            ? savedShippingId
            : (this.shippingOptions[0]?.id ?? null);

          const savedPaymentId = Number(saved.payment_method_id ?? 0);
          if (this.paymentOptions.some(o => o.id === savedPaymentId) && !this.isPaymentDisabled(savedPaymentId)) {
            this.selectedPaymentId = savedPaymentId;
          } else {
            this.selectedPaymentId = this.firstEnabledPaymentId();
          }
        },

        async init() {
          this.restoreSelection();
          this.applyPrefill();
          await this.loadCart();
        },

        applyPrefill() {
          if (!this.prefill) return;
          const p = this.prefill;
          this.f.email            = p.email            ?? this.f.email;
          this.f.phone            = p.phone            ?? this.f.phone;
          this.f.firstName        = p.firstName        ?? this.f.firstName;
          this.f.lastName         = p.lastName         ?? this.f.lastName;
          this.f.street           = p.street           ?? this.f.street;
          this.f.city             = p.city             ?? this.f.city;
          this.f.zip              = p.zip              ?? this.f.zip;
          this.f.country          = p.country          ?? this.f.country;
          this.f.billingSame      = p.billingSame      ?? this.f.billingSame;
          this.f.billingFirstName = p.billingFirstName ?? this.f.billingFirstName;
          this.f.billingLastName  = p.billingLastName  ?? this.f.billingLastName;
          this.f.billingStreet    = p.billingStreet    ?? this.f.billingStreet;
          this.f.billingCity      = p.billingCity      ?? this.f.billingCity;
          this.f.billingZip       = p.billingZip       ?? this.f.billingZip;
          this.f.billingCountry   = p.billingCountry   ?? this.f.billingCountry;
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
          return this.items.reduce((sum, item) => sum + parseFloat(item.price) * item.qty, 0);
        },

        get total() {
          return this.subtotal + Number(this.shipping?.price ?? 0) + Number(this.payment?.fee ?? 0);
        },

        validate() {
          const e = {};
          const req = (val, key, msg) => { if (!val?.trim()) e[key] = msg; };

          if (!this.f.email?.trim()) {
            e.email = 'E-mail je povinný.';
          } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(this.f.email)) {
            e.email = 'Zadajte platný e-mail.';
          }

          if (this.f.phone?.trim() && !/^[+\d\s\-()]{7,}$/.test(this.f.phone)) {
            e.phone = 'Zadajte platné telefónne číslo.';
          }

          req(this.f.firstName, 'firstName', 'Meno je povinné.');
          req(this.f.lastName, 'lastName', 'Priezvisko je povinné.');
          req(this.f.street, 'street', 'Ulica je povinná.');
          req(this.f.city, 'city', 'Mesto je povinné.');

          if (!this.f.zip?.trim()) {
            e.zip = 'PSČ je povinné.';
          } else if (!/^\d{3}\s?\d{2}$/.test(this.f.zip.trim())) {
            e.zip = 'PSČ musí byť vo formáte XXX XX.';
          }

          if (this.isPickupPoint && !this.f.pickupPoint) {
            e.pickupPoint = 'Vyberte výdajné miesto.';
          }

          if (this.needsAddress && !this.f.billingSame) {
            req(this.f.billingFirstName, 'billingFirstName', 'Meno vo fakturačnej adrese je povinné.');
            req(this.f.billingLastName, 'billingLastName', 'Priezvisko vo fakturačnej adrese je povinné.');
            req(this.f.billingStreet, 'billingStreet', 'Ulica vo fakturačnej adrese je povinná.');
            req(this.f.billingCity, 'billingCity', 'Mesto vo fakturačnej adrese je povinné.');
            req(this.f.billingCountry, 'billingCountry', 'Krajina vo fakturačnej adrese je povinná.');

            if (!this.f.billingZip?.trim()) {
              e.billingZip = 'PSČ vo fakturačnej adrese je povinné.';
            } else if (!/^\d{3}\s?\d{2}$/.test(this.f.billingZip.trim())) {
              e.billingZip = 'PSČ vo fakturačnej adrese musí byť vo formáte XXX XX.';
            }
          }

          if (this.isCardPayment) {
            const digits = this.f.cardNumber.replace(/\s/g, '');
            if (!digits) {
              e.cardNumber = 'Číslo karty je povinné.';
            } else if (!/^\d{13,19}$/.test(digits)) {
              e.cardNumber = 'Číslo karty musí mať 13–19 číslic.';
            }

            req(this.f.cardName, 'cardName', 'Meno držiteľa karty je povinné.');

            if (!this.f.cardExpiry?.trim()) {
              e.cardExpiry = 'Platnosť karty je povinná.';
            } else if (!/^(0[1-9]|1[0-2])\s?\/\s?\d{2}$/.test(this.f.cardExpiry.trim())) {
              e.cardExpiry = 'Formát musí byť MM / RR.';
            } else {
              const [m, y] = this.f.cardExpiry.split('/').map(p => parseInt(p.trim(), 10));
              const now = new Date();
              const ey = 2000 + y;
              if (ey < now.getFullYear() || (ey === now.getFullYear() && m < now.getMonth() + 1)) {
                e.cardExpiry = 'Platnosť karty vypršala.';
              }
            }

            if (!this.f.cardCvv?.trim()) {
              e.cardCvv = 'CVV je povinné.';
            } else if (!/^\d{3,4}$/.test(this.f.cardCvv.trim())) {
              e.cardCvv = 'CVV musí mať 3 alebo 4 číslice.';
            }
          }

          this.err = e;
          return Object.keys(e).length === 0;
        },

        mapBackendField(field) {
          const map = {
            first_name: 'firstName', last_name: 'lastName',
            pickup_point: 'pickupPoint',
            billing_first_name: 'billingFirstName', billing_last_name: 'billingLastName',
            billing_street: 'billingStreet', billing_city: 'billingCity',
            billing_zip: 'billingZip', billing_country: 'billingCountry',
            card_number: 'cardNumber', card_name: 'cardName',
            card_expiry: 'cardExpiry', card_cvv: 'cardCvv',
          };
          return map[field] ?? field;
        },

        async submit() {
          this.submitError = '';

          if (!this.shipping || !this.payment || this.isPaymentDisabled(this.payment.id)) {
            this.submitError = 'Vybraný spôsob dopravy alebo platby už nie je dostupný.';
            return;
          }

          if (!this.validate()) {
            this.$nextTick(() => {
              document.querySelector('.border-red-500')?.scrollIntoView({ behavior: 'smooth', block: 'center' });
            });
            return;
          }

          this.submitting = true;

          const billingSameAsDelivery = this.needsAddress ? this.f.billingSame : false;
          const separateBilling = this.needsAddress && !this.f.billingSame;

          const payload = {
            email: this.f.email,
            phone: this.f.phone,
            shipping_method_id: this.shipping.id,
            payment_method_id: this.payment.id,

            first_name: this.f.firstName,
            last_name: this.f.lastName,
            street: this.f.street,
            city: this.f.city,
            zip: this.f.zip,
            country: this.f.country,

            pickup_point: this.isPickupPoint ? this.f.pickupPoint : null,

            billing_same_as_delivery: billingSameAsDelivery,
            billing_first_name: separateBilling ? this.f.billingFirstName : null,
            billing_last_name: separateBilling ? this.f.billingLastName : null,
            billing_street: separateBilling ? this.f.billingStreet : null,
            billing_city: separateBilling ? this.f.billingCity : null,
            billing_zip: separateBilling ? this.f.billingZip : null,
            billing_country: separateBilling ? this.f.billingCountry : null,

            card_number: this.f.cardNumber,
            card_name: this.f.cardName,
            card_expiry: this.f.cardExpiry,
            card_cvv: this.f.cardCvv,

            items: this.items.map(item => ({
              variant_id: item.variant_id,
              quantity: Number(item.qty) || 1,
            })),

            subtotal: this.subtotal,
            total: this.total,
          };

          try {
            const res = await fetch('{{ route('store.cart.place') }}', {
              method: 'POST',
              headers: {
                'Content-Type': 'application/json',
                Accept: 'application/json',
                'X-CSRF-TOKEN': this.csrfToken,
              },
              body: JSON.stringify(payload),
            });

            if (!res.ok) {
              const data = await res.json().catch(() => ({}));

              if (res.status === 422) {
                const backendErrors = data.errors ?? {};
                this.err = Object.fromEntries(
                  Object.entries(backendErrors).map(([field, messages]) => [
                    this.mapBackendField(field),
                    Array.isArray(messages) && messages.length > 0 ? messages[0] : 'Neplatná hodnota.',
                  ])
                );
                this.submitError = data.message ?? 'Skontrolujte prosím formulár.';
                this.$nextTick(() => {
                  document.querySelector('.border-red-500')?.scrollIntoView({ behavior: 'smooth', block: 'center' });
                });
                return;
              }

              const backendMessage = typeof data.message === 'string' ? data.message : '';
              this.submitError = backendMessage || `Objednávku sa nepodarilo odoslať (kód ${res.status}). Skúste to prosím znova.`;
              return;
            }

            const data = await res.json().catch(() => ({}));

            if (!data.order_id) {
              this.submitError = 'Objednávku sa nepodarilo dokončiť. Skúste to prosím znova.';
              return;
            }

            localStorage.removeItem('bellura_cart');
            sessionStorage.removeItem('bellura_checkout');
            window.dispatchEvent(new CustomEvent('bellura:cart-updated', { detail: { count: 0 } }));
            window.location.href = `/kosik/hotovo/${data.order_id}`;
          } catch (e) {
            this.submitError = 'Objednávku sa nepodarilo odoslať. Skúste to prosím znova.';
          } finally {
            this.submitting = false;
          }
        },

        formatCardNumber(value) {
          return value.replace(/\D/g, '').slice(0, 16).replace(/(.{4})/g, '$1 ').trim();
        },

        formatExpiry(value) {
          const digits = value.replace(/\D/g, '').slice(0, 4);
          return digits.length >= 3 ? digits.slice(0, 2) + ' / ' + digits.slice(2) : digits;
        },

        fmtPrice(price) {
          return parseFloat(price).toFixed(2).replace('.', ',') + ' €';
        },
      };
    }
  </script>

</x-store.layout>
