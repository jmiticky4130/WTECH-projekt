<x-store.layout title="Dodacie údaje — Bellura.sk">

  <x-store.step-indicator :currentStep="3" />

  <main class="flex-1" x-data="cartStep3()" x-init="init()">
    <div class="max-w-7xl mx-auto px-4 py-8">
      <div class="flex flex-col lg:flex-row gap-8 lg:items-start">

        <div class="flex-1 min-w-0">
          <h1 class="text-2xl font-bold mb-6">Dodacie údaje</h1>

          <!-- contact info -->
          <section class="mb-6">
            <h2 class="text-base font-bold mb-4">Kontaktné údaje</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
              <div>
                <label class="block text-sm mb-1">E-mail <span class="text-red-500">*</span></label>
                <input type="email" x-model="f.email" placeholder="vas@email.sk"
                       :class="err.email ? 'border-red-500' : 'border-gray-300'"
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
          </section>

          <!-- delivery address — shown for courier/post -->
          <section class="mb-6" x-show="needsAddress">
            <h2 class="text-base font-bold mb-4">Doručovacia adresa</h2>
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

          <!-- pickup point — shown for zasielkovna -->
          <section class="mb-6" x-show="isPickupPoint">
            <h2 class="text-base font-bold mb-4">Výdajné miesto</h2>
            <div class="border border-gray-200 rounded p-4 space-y-4">
              <div>
                <label class="block text-sm mb-1">Meno a priezvisko <span class="text-red-500">*</span></label>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                  <div>
                    <input type="text" x-model="f.pickupFirstName" placeholder="Meno"
                           :class="err.pickupFirstName ? 'border-red-500' : 'border-gray-300'"
                           class="w-full border px-3 py-2.5 text-sm focus:outline-none focus:border-brand-dark" />
                    <p class="text-xs text-red-500 mt-1" x-show="err.pickupFirstName" x-text="err.pickupFirstName"></p>
                  </div>
                  <div>
                    <input type="text" x-model="f.pickupLastName" placeholder="Priezvisko"
                           :class="err.pickupLastName ? 'border-red-500' : 'border-gray-300'"
                           class="w-full border px-3 py-2.5 text-sm focus:outline-none focus:border-brand-dark" />
                    <p class="text-xs text-red-500 mt-1" x-show="err.pickupLastName" x-text="err.pickupLastName"></p>
                  </div>
                </div>
              </div>
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
            </div>
          </section>

          <!-- personal pickup — shown for osobny odber -->
          <section class="mb-6" x-show="isPersonalPickup">
            <h2 class="text-base font-bold mb-4">Osobný odber</h2>
            <div class="border border-gray-200 rounded p-4 space-y-3">
              <p class="text-sm font-semibold">Pobočka Bratislava</p>
              <p class="text-sm text-gray-600">Obchodná 12, 811 06 Bratislava</p>
              <p class="text-sm text-gray-500">Po – Pi: 9:00 – 19:00 &nbsp;|&nbsp; So: 9:00 – 14:00</p>
              <p class="text-sm text-gray-500">Objednávka bude pripravená do 24 hodín od potvrdenia.</p>
              <div class="pt-2">
                <label class="block text-sm mb-1">Meno a priezvisko <span class="text-red-500">*</span></label>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                  <div>
                    <input type="text" x-model="f.personalFirstName" placeholder="Meno"
                           :class="err.personalFirstName ? 'border-red-500' : 'border-gray-300'"
                           class="w-full border px-3 py-2.5 text-sm focus:outline-none focus:border-brand-dark" />
                    <p class="text-xs text-red-500 mt-1" x-show="err.personalFirstName" x-text="err.personalFirstName"></p>
                  </div>
                  <div>
                    <input type="text" x-model="f.personalLastName" placeholder="Priezvisko"
                           :class="err.personalLastName ? 'border-red-500' : 'border-gray-300'"
                           class="w-full border px-3 py-2.5 text-sm focus:outline-none focus:border-brand-dark" />
                    <p class="text-xs text-red-500 mt-1" x-show="err.personalLastName" x-text="err.personalLastName"></p>
                  </div>
                </div>
              </div>
            </div>
          </section>

          <!-- card details — only for card payment -->
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

          <!-- google pay -->
          <section class="mb-8" x-show="isGooglePay">
            <h2 class="text-base font-bold mb-3">Google Pay</h2>
            <div class="border border-gray-200 rounded p-4">
              <p class="text-sm text-gray-600 mb-4">Platba bude spracovaná cez Google Pay pri dokončení objednávky.</p>
              <button type="button" class="flex items-center gap-2 border border-gray-300 rounded px-5 py-2.5 text-sm font-medium hover:bg-gray-50 transition-colors">
                <img src="{{ asset('icons/google.svg') }}" class="w-5 h-5" alt="" onerror="this.style.display='none'">
                Zaplatiť cez Google Pay
              </button>
            </div>
          </section>

          <!-- bank transfer -->
          <section class="mb-8" x-show="isBankTransfer">
            <h2 class="text-base font-bold mb-3">Bankový prevod</h2>
            <div class="border border-gray-200 rounded p-4 space-y-2 text-sm">
              <p class="text-gray-600">Po odoslaní objednávky vám zašleme platobné údaje e-mailom.</p>
              <p><span class="text-gray-500">IBAN:</span> <span class="font-medium">SK12 3456 7890 1234 5678 90</span></p>
              <p><span class="text-gray-500">BIC/SWIFT:</span> <span class="font-medium">TATRSKBX</span></p>
              <p class="text-gray-500 text-xs mt-2">Tovar bude odoslaný po pripísaní platby na účet.</p>
            </div>
          </section>

          <!-- cash on delivery -->
          <section class="mb-8" x-show="isCashOnDelivery">
            <h2 class="text-base font-bold mb-3">Dobierka</h2>
            <div class="border border-gray-200 rounded p-4 text-sm text-gray-600">
              <p>Platbu uhradíte pri prevzatí zásielky od kuriéra. Pripravte si sumu <span class="font-medium" x-text="fmtPrice(total)"></span>.</p>
            </div>
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
                <span class="text-gray-600" x-text="'Doprava (' + shipping.label + ')'"></span>
                <span class="font-medium" x-text="shipping.price === 0 ? 'Zadarmo' : fmtPrice(shipping.price)"></span>
              </div>
              <div class="flex justify-between" x-show="shipping?.type === 'address' && payment.surcharge > 0">
                <span class="text-gray-600" x-text="'Platba (' + payment.label + ')'"></span>
                <span class="font-medium" x-text="fmtPrice(payment.surcharge)"></span>
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
              <p x-text="'Doprava: ' + shipping.label"></p>
              <p x-text="'Platba: ' + payment.label"></p>
            </div>

            <button @click="submit()"
                    class="w-full bg-brand-dark hover:bg-brand-accent text-white font-bold text-sm tracking-widest py-4 transition-colors uppercase mb-3">
              Objednať a zaplatiť
            </button>

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
        items: [],
        loading: true,

        shippingOptions: [
          { label: 'Kuriér DPD', desc: 'Doručenie do 2–3 pracovných dní na adresu', price: 3.99, type: 'address' },
          { label: 'Slovenská pošta', desc: 'Doručenie do 3–5 pracovných dní', price: 2.49, type: 'address' },
          { label: 'Zásielkovňa (výdajné miesto)', desc: 'Vyzdvihnutie na najbližšom výdajnom mieste', price: 1.99, type: 'pickup_point' },
          { label: 'Osobný odber — pobočka Bratislava', desc: 'Obchodná 12, Bratislava — pripravené do 24h', price: 0, type: 'personal_pickup' },
        ],

        paymentOptions: [
          { label: 'Platba kartou online', surcharge: 0, type: 'card' },
          { label: 'Dobierka (platba pri prevzatí)', surcharge: 1.50, type: 'cod' },
          { label: 'Google Pay', surcharge: 0, type: 'google_pay' },
          { label: 'Bankový prevod', surcharge: 0, type: 'bank_transfer' },
        ],

        selectedShipping: 0,
        selectedPayment: 0,

        f: {
          email: '', phone: '',
          firstName: '', lastName: '', street: '', city: '', zip: '', country: 'Slovensko',
          pickupFirstName: '', pickupLastName: '', pickupPoint: '',
          personalFirstName: '', personalLastName: '',
          cardNumber: '', cardName: '', cardExpiry: '', cardCvv: '',
        },

        err: {},

        get shipping() { return this.shippingOptions[this.selectedShipping]; },
        get payment()  { return this.paymentOptions[this.selectedPayment]; },

        get needsAddress()     { return this.shipping?.type === 'address'; },
        get isPickupPoint()    { return this.shipping?.type === 'pickup_point'; },
        get isPersonalPickup() { return this.shipping?.type === 'personal_pickup'; },

        get isCardPayment()    { return this.payment?.type === 'card'; },
        get isGooglePay()      { return this.payment?.type === 'google_pay'; },
        get isBankTransfer()   { return this.payment?.type === 'bank_transfer'; },
        get isCashOnDelivery() { return this.payment?.type === 'cod'; },

        async init() {
          const saved = JSON.parse(sessionStorage.getItem('bellura_checkout') || '{}');
          this.selectedShipping = saved.shipping ?? 0;
          this.selectedPayment  = saved.payment  ?? 0;
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
          const paymentSurcharge = this.shipping?.type === 'address' ? (this.payment?.surcharge ?? 0) : 0;
          return this.subtotal + (this.shipping?.price ?? 0) + paymentSurcharge;
        },

        validate() {
          const e = {};
          const req = (v, key, msg) => { if (!v?.trim()) e[key] = msg; };

          // email — always required, basic format check
          if (!this.f.email?.trim()) {
            e.email = 'E-mail je povinný.';
          } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(this.f.email)) {
            e.email = 'Zadajte platný e-mail.';
          }

          // phone — optional, but if filled must look reasonable
          if (this.f.phone?.trim() && !/^[+\d\s\-()]{7,}$/.test(this.f.phone)) {
            e.phone = 'Zadajte platné telefónne číslo.';
          }

          // address fields
          if (this.needsAddress) {
            req(this.f.firstName, 'firstName', 'Meno je povinné.');
            req(this.f.lastName,  'lastName',  'Priezvisko je povinné.');
            req(this.f.street,    'street',    'Ulica je povinná.');
            req(this.f.city,      'city',      'Mesto je povinné.');
            if (!this.f.zip?.trim()) {
              e.zip = 'PSČ je povinné.';
            } else if (!/^\d{3}\s?\d{2}$/.test(this.f.zip.trim())) {
              e.zip = 'PSČ musí byť vo formáte XXX XX.';
            }
          }

          // pickup point fields
          if (this.isPickupPoint) {
            req(this.f.pickupFirstName, 'pickupFirstName', 'Meno je povinné.');
            req(this.f.pickupLastName,  'pickupLastName',  'Priezvisko je povinné.');
            if (!this.f.pickupPoint) e.pickupPoint = 'Vyberte výdajné miesto.';
          }

          // personal pickup fields
          if (this.isPersonalPickup) {
            req(this.f.personalFirstName, 'personalFirstName', 'Meno je povinné.');
            req(this.f.personalLastName,  'personalLastName',  'Priezvisko je povinné.');
          }

          // card fields
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
              const [m, y] = this.f.cardExpiry.split('/').map(s => parseInt(s.trim()));
              const now = new Date();
              const expYear = 2000 + y, expMonth = m;
              if (expYear < now.getFullYear() || (expYear === now.getFullYear() && expMonth < now.getMonth() + 1)) {
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

        submit() {
          if (this.validate()) {
            // proceed to order confirmation
            alert('Objednávka odoslaná!');
          } else {
            this.$nextTick(() => {
              document.querySelector('.border-red-500')?.scrollIntoView({ behavior: 'smooth', block: 'center' });
            });
          }
        },

        formatCardNumber(v) {
          return v.replace(/\D/g, '').slice(0, 16).replace(/(.{4})/g, '$1 ').trim();
        },

        formatExpiry(v) {
          const d = v.replace(/\D/g, '').slice(0, 4);
          return d.length >= 3 ? d.slice(0, 2) + ' / ' + d.slice(2) : d;
        },

        fmtPrice(p) {
          return parseFloat(p).toFixed(2).replace('.', ',') + ' €';
        },
      };
    }
  </script>

</x-store.layout>
