@php
  $pageTitle  = $product->name . ' — Bellura.sk';
  $primaryImg = $images->firstWhere('is_primary', true) ?? $images->first();
  $primaryImgUrl = $primaryImg ? \App\Support\ProductImageUrl::resolve($primaryImg->image_path) : '';
@endphp

<x-store.layout :title="$pageTitle">

  <x-store.breadcrumb :items="$breadcrumb" />

  {{-- Wrapper provides shared Alpine scope for both main content and sticky mobile bar --}}
  <div x-data="{
      activeImg: '{{ $primaryImgUrl }}',
         activeColor: {{ $colors->isNotEmpty() ? $colors->first()->color_id : 'null' }},
         activeColorName: '{{ $colors->isNotEmpty() ? $colors->first()->color_name : '' }}',
         activeSize: null,
         qty: 1,
         addingToCart: false,
         addedToCart: false,
         addError: '',
         variants: {{ Js::from($variants) }},
         get allSizes() {
           const order = { XS: 0, S: 1, M: 2, L: 3, XL: 4, XXL: 5 };
           const sizes = [...new Set(this.variants.map(v => v.size))];
           sizes.sort((a, b) => {
             const aNum = !isNaN(a), bNum = !isNaN(b);
             if (aNum && bNum) return +a - +b;
             if (!aNum && !bNum) return (order[a] ?? 99) - (order[b] ?? 99);
             return aNum ? 1 : -1;
           });
           return sizes;
         },
         get availableSizes() {
           return this.allSizes.map(size => {
             const v = this.variants.find(v => v.color_id == this.activeColor && v.size === size);
             return { size, stock: v ? v.stock_quantity : 0, forColor: !!v };
           });
         },
         get selectedVariant() {
           if (!this.activeSize) return null;
           return this.variants.find(v => v.color_id == this.activeColor && v.size === this.activeSize) ?? null;
         },
         get maxSelectableQty() {
           return this.selectedVariant ? Math.max(0, Number(this.selectedVariant.stock_quantity) || 0) : 0;
         },
         get price() {
           return this.selectedVariant
             ? parseFloat(this.selectedVariant.price)
             : {{ (float) ($minPrice ?? 0) }};
         },
         get inStock() {
           return this.selectedVariant ? this.selectedVariant.stock_quantity > 0 : null;
         },
         selectColor(colorId, colorName) {
           this.activeColor = colorId;
           this.activeColorName = colorName;
           this.activeSize = null;
           this.qty = 1;
           this.addError = '';
         },
         fmtPrice(p) {
           return parseFloat(p).toFixed(2).replace('.', ',') + ' €';
         },
         async addToCart() {
           if (!this.selectedVariant || !this.inStock || this.addingToCart) return;
           if (this.qty > this.maxSelectableQty) {
             this.qty = this.maxSelectableQty;
             this.addError = `Na sklade je dostupnych uz len ${this.maxSelectableQty} ks.`;
             return;
           }

           this.addError = '';
           this.addingToCart = true;
           try {
             if (window.__bellura?.isAuth) {
               const res = await fetch('/cart/add', {
                 method: 'POST',
                 headers: {
                   'Content-Type': 'application/json',
                   'X-CSRF-TOKEN': window.__bellura.csrfToken,
                   'Accept': 'application/json',
                 },
                 body: JSON.stringify({ variant_id: this.selectedVariant.id, qty: this.qty }),
               });
               const data = await res.json().catch(() => ({}));

               if (!res.ok) {
                 this.addError = data.message ?? 'Polozku sa nepodarilo pridat do kosika.';
                 return;
               }

               window.dispatchEvent(new CustomEvent('cart-updated', { detail: { count: data.cart_count } }));
             } else {
               const cart = JSON.parse(localStorage.getItem('bellura_cart') || '[]');
               const existing = cart.find(i => i.variant_id === this.selectedVariant.id);
               const requestedQty = Number(this.qty) || 1;
               const maxQty = this.maxSelectableQty;

               if (existing) {
                 const before = Number(existing.qty) || 0;
                 existing.qty = Math.min(maxQty, before + requestedQty);
                 if (existing.qty < before + requestedQty) {
                   this.addError = `Na sklade je dostupnych uz len ${maxQty} ks.`;
                 }
               } else {
                 const newQty = Math.min(maxQty, requestedQty);
                 cart.push({ variant_id: this.selectedVariant.id, qty: newQty });
                 if (newQty < requestedQty) {
                   this.addError = `Na sklade je dostupnych uz len ${maxQty} ks.`;
                 }
               }
               localStorage.setItem('bellura_cart', JSON.stringify(cart));
               const total = cart.reduce((s, i) => s + i.qty, 0);
               window.dispatchEvent(new CustomEvent('cart-updated', { detail: { count: total } }));
             }
             this.addedToCart = true;
             setTimeout(() => { this.addedToCart = false; }, 2000);
           } catch (e) {
             this.addError = 'Polozku sa nepodarilo pridat do kosika.';
           } finally {
             this.addingToCart = false;
           }
         },
       }">

    <main class="flex-1">
      <div class="max-w-7xl mx-auto px-4 py-8">

        <div class="grid grid-cols-1 md:grid-cols-2 gap-10 mb-12">

          <!-- images -->
          <div class="flex flex-col gap-3">
            <!-- product name above image (mobile only) -->
            <div class="md:hidden">
              <p class="text-xs text-gray-500 mb-0.5">{{ $product->brand_name }}</p>
              <h2 class="text-lg font-bold leading-tight">{{ $product->name }}</h2>
            </div>

            <div class="aspect-4/5 bg-gray-100 border border-gray-200 overflow-hidden relative">
              <template x-if="activeImg">
                <img :src="activeImg" class="w-full absolute top-1/2 -translate-y-1/2" alt="{{ $product->name }}">
              </template>
            </div>

            @if ($images->count() > 1)
              <div class="grid grid-cols-4 gap-3">
                @foreach ($images as $img)
                  @php $imgUrl = \App\Support\ProductImageUrl::resolve($img->image_path); @endphp
                  <div class="aspect-2/3 bg-gray-100 overflow-hidden relative cursor-pointer"
                       :class="activeImg === '{{ $imgUrl }}' ? 'border-2 border-brand-dark' : 'border border-gray-200'"
                       @click="activeImg = '{{ $imgUrl }}'">
                    <img src="{{ $imgUrl }}" class="w-full absolute top-1/2 -translate-y-1/2" alt="{{ $product->name }}">
                  </div>
                @endforeach
              </div>
            @endif
          </div>

          <!-- product info -->
          <div class="flex flex-col">
            <p class="text-sm text-gray-500 mb-1">{{ $product->brand_name }}</p>
            <h1 class="text-2xl font-bold mb-2">{{ $product->name }}</h1>
            <p class="text-2xl font-bold mb-1" x-text="fmtPrice(price)">{{ number_format($minPrice ?? 0, 2, ',', ' ') }} €</p>
            <p class="text-xs text-gray-400 mb-5">Vrátane DPH</p>

            <hr class="border-gray-200 mb-5" />

            <!-- color selector -->
            @if ($colors->isNotEmpty())
              <div class="mb-5">
                <p class="text-sm font-semibold mb-2">
                  Farba: <span class="font-normal text-gray-500" x-text="activeColorName"></span>
                </p>
                <div class="flex gap-2">
                  @foreach ($colors as $color)
                    <button type="button"
                            class="w-8 h-8 rounded-full border-2 transition-all focus:outline-none"
                            :class="activeColor === {{ $color->color_id }}
                              ? 'ring-2 ring-brand-dark ring-offset-2 border-transparent'
                              : 'border-gray-300 hover:ring-2 hover:ring-gray-400'"
                            style="background-color: {{ $color->hex_code }}"
                            title="{{ $color->color_name }}"
                            @click="selectColor({{ $color->color_id }}, '{{ $color->color_name }}', '{{ $color->hex_code }}')">
                    </button>
                  @endforeach
                </div>
              </div>
            @endif

            <!-- size selector -->
            <div class="mb-5">
              <p class="text-sm font-semibold mb-2">Veľkosť</p>
              @if ($isShoe)
                <select x-model="activeSize" @change="qty = 1"
                        class="w-full border border-gray-300 px-3 py-2.5 text-sm focus:outline-none focus:border-brand-dark bg-white">
                  <option value="" disabled selected>Vyberte veľkosť (EU)</option>
                  <template x-for="sv in availableSizes.filter(s => s.forColor)" :key="sv.size">
                    <option :value="sv.size"
                            :disabled="sv.stock === 0"
                            x-text="sv.stock === 0 ? sv.size + ' — vypredané' : sv.size">
                    </option>
                  </template>
                </select>
              @else
                <div class="flex gap-2 flex-wrap">
                  <template x-for="sv in availableSizes" :key="sv.size">
                    <button type="button"
                            class="w-11 h-11 border text-sm flex items-center justify-center transition-colors"
                            :class="{
                              'border-2 border-brand-dark bg-brand-dark text-white font-semibold': activeSize === sv.size,
                              'border-gray-200 text-gray-300 cursor-not-allowed line-through': !sv.forColor || sv.stock === 0,
                              'border-gray-300 hover:border-brand-dark': sv.forColor && sv.stock > 0 && activeSize !== sv.size,
                            }"
                            :disabled="!sv.forColor || sv.stock === 0"
                            @click="if (sv.forColor && sv.stock > 0) { activeSize = sv.size; qty = 1; }"
                            x-text="sv.size">
                    </button>
                  </template>
                </div>
              @endif
            </div>

            <!-- quantity (desktop only) -->
            <div class="hidden md:block mb-5">
              <p class="text-sm font-semibold mb-2">Množstvo</p>
              <p class="text-xs text-gray-500 mb-2" x-show="selectedVariant" x-text="`Dostupne: ${maxSelectableQty} ks`"></p>
              <div class="flex items-stretch border border-gray-300 w-fit h-11">
                <button type="button" class="w-10 flex items-center justify-center text-lg font-light text-brand-dark select-none hover:bg-gray-50"
                        @click="qty = Math.max(1, qty - 1)">&minus;</button>
                <div class="w-10 flex items-center justify-center text-sm font-semibold border-x border-gray-300" x-text="qty"></div>
                <button type="button" class="w-10 flex items-center justify-center text-lg font-light text-brand-dark select-none hover:bg-gray-50"
                        @click="if (selectedVariant && qty < maxSelectableQty) qty = qty + 1">+</button>
              </div>
            </div>

            <!-- stock status -->
            <div id="sticky-trigger" class="flex items-center gap-2 md:mb-6 text-sm text-gray-600">
              <template x-if="inStock === null">
                <span class="flex items-center gap-2">
                  <span class="w-2 h-2 rounded-full bg-gray-300 shrink-0"></span>
                  <span>Vyberte veľkosť</span>
                </span>
              </template>
              <template x-if="inStock === true">
                <span class="flex items-center gap-2">
                  <span class="w-2 h-2 rounded-full bg-green-500 shrink-0"></span>
                  <span x-text="selectedVariant && selectedVariant.stock_quantity <= 5
                    ? `Skladom uz len ${selectedVariant.stock_quantity} ks`
                    : `Skladom ${selectedVariant ? selectedVariant.stock_quantity : 0} ks`"></span>
                </span>
              </template>
              <template x-if="inStock === false">
                <span class="flex items-center gap-2">
                  <span class="w-2 h-2 rounded-full bg-red-400 shrink-0"></span>
                  <span>Vypredané</span>
                </span>
              </template>
            </div>

            <!-- add to cart (desktop only) -->
            <div class="hidden md:block">
              <button @click="addToCart()"
                      :disabled="!activeSize || !inStock || addingToCart"
                      class="w-full bg-brand-dark hover:bg-brand-accent text-white font-bold text-sm tracking-widest py-4 transition-colors uppercase disabled:opacity-40 disabled:cursor-not-allowed">
                <span x-show="!addedToCart" x-text="addingToCart ? 'Pridávam...' : 'Pridať do košíka'"></span>
                <span x-show="addedToCart">Pridané ✓</span>
              </button>
              <p class="mt-2 text-xs text-red-500" x-show="addError" x-text="addError"></p>
            </div>
          </div>

        </div>

        <!-- tabs -->
        <div x-data="{ tab: 'desc' }" class="mb-12">
          <div class="border-b border-gray-200 mb-6">
            <div class="flex gap-0 text-sm">
              <button @click="tab = 'desc'"
                      :class="tab === 'desc' ? 'border-b-2 border-brand-dark text-brand-dark font-semibold' : 'border-b-2 border-transparent text-gray-500 hover:text-brand-dark transition-colors'"
                      class="px-4 py-3 -mb-px">Popis</button>
              <button @click="tab = 'params'"
                      :class="tab === 'params' ? 'border-b-2 border-brand-dark text-brand-dark font-semibold' : 'border-b-2 border-transparent text-gray-500 hover:text-brand-dark transition-colors'"
                      class="px-4 py-3 -mb-px">Parametre</button>
            </div>
          </div>

          <div x-show="tab === 'desc'" class="text-sm text-gray-700 leading-relaxed">
            <p>{{ $product->description }}</p>
          </div>

          <div x-show="tab === 'params'" class="text-sm text-gray-700 leading-relaxed space-y-1">
            <p>Značka: {{ $product->brand_name }}</p>
            <p>Materiál: {{ $product->material_name }}</p>
            @if ($product->subcategory_name)
              <p>Kategória: {{ $product->subcategory_name }}</p>
            @endif
          </div>
        </div>

        <!-- similar products -->
        @if ($similarProducts->isNotEmpty())
          <section>
            <h2 class="text-xl font-bold underline underline-offset-4 mb-6">Podobné produkty</h2>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-5">
              @foreach ($similarProducts as $similar)
                <x-store.product-card
                  :href="route('store.product', $similar->slug)"
                  :image="$similar->image_path ?? ''"
                  :brand="$similar->brand_name"
                  :name="$similar->name"
                  :sizes="$similar->sizes ?? ''"
                  :price="number_format($similar->min_price, 2, ',', ' ') . ' €'"
                />
              @endforeach
            </div>
          </section>
        @endif

      </div>
    </main>

    <!-- sticky mobile add-to-cart bar -->
    <script>
      document.addEventListener('DOMContentLoaded', () => {
        const bar = document.getElementById('sticky-bar');
        const trigger = document.getElementById('sticky-trigger');
        function update() {
          const rect = trigger.getBoundingClientRect();
          bar.classList.toggle('translate-y-full', rect.top >= window.innerHeight);
          bar.classList.toggle('translate-y-0',    rect.top <  window.innerHeight);
        }
        window.addEventListener('scroll', update, { passive: true });
        update();
      });
    </script>
    <div id="sticky-bar"
         class="md:hidden fixed bottom-0 left-0 right-0 z-50 bg-white border-t border-gray-200 px-3 py-3 flex items-center gap-3 translate-y-full transition-transform duration-300"
         :class="{ 'opacity-50 pointer-events-none': !activeSize || !inStock }">
      <div class="flex flex-1 items-stretch h-13 min-h-13">
        <div class="flex items-stretch border border-gray-300 border-r-0 shrink-0">
          <button type="button" class="w-10 flex items-center justify-center text-lg font-light text-brand-dark select-none px-1"
                  @click="qty = Math.max(1, qty - 1)">&minus;</button>
          <div class="w-10 flex items-center justify-center text-sm font-semibold border-x border-gray-300" x-text="qty ?? 1"></div>
          <button type="button" class="w-10 flex items-center justify-center text-lg font-light text-brand-dark select-none px-1"
            @click="if (selectedVariant && qty < maxSelectableQty) qty = qty + 1">+</button>
        </div>
        <button @click="addToCart()"
                :disabled="!activeSize || !inStock || addingToCart"
                class="flex-1 bg-brand-dark text-white font-bold text-sm tracking-widest uppercase transition-colors active:bg-brand-accent disabled:opacity-40">
          <span x-show="!addedToCart" x-text="addingToCart ? 'Pridávam...' : 'Pridať do košíka'"></span>
          <span x-show="addedToCart">Pridané ✓</span>
        </button>
      </div>
      <a href="{{ route('store.cart') }}" class="shrink-0 flex items-center justify-center w-13 h-13 min-w-13 min-h-13 border border-gray-300">
        <img src="{{ asset('icons/shopping-cart.svg') }}" class="w-7 h-7" alt="Košík" />
      </a>
    </div>

  </div>{{-- end shared Alpine scope --}}

</x-store.layout>
