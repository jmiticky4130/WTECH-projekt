<x-store.layout title="Saténová blúzka — Bellura.sk">

  <x-store.breadcrumb :items="[
    ['label' => 'Domov', 'href' => url('/')],
    ['label' => 'Ženy', 'href' => '#'],
    ['label' => 'Oblečenie', 'href' => '#'],
    ['label' => 'Saténová blúzka'],
  ]" />

  <main class="flex-1">
    <div class="max-w-7xl mx-auto px-4 py-8">

      <!-- product section -->
      <div class="grid grid-cols-1 md:grid-cols-2 gap-10 mb-12">

        <!-- images -->
        <div class="flex flex-col gap-3">
          <!-- product name above image (mobile only) -->
          <div class="md:hidden">
            <p class="text-xs text-gray-500 mb-0.5">Značka</p>
            <h2 class="text-lg font-bold leading-tight">Saténová blúzka</h2>
          </div>
          <div class="aspect-[4/5] bg-gray-100 border border-gray-200 overflow-hidden relative">
            <img src="{{ asset('images/products/satin-blouse.jpg') }}" class="w-full absolute top-1/2 -translate-y-1/2" alt="Saténová blúzka">
          </div>
          <div class="grid grid-cols-4 gap-3">
            @for ($i = 0; $i < 4; $i++)
              <div class="aspect-[2/3] bg-gray-100 {{ $i === 0 ? 'border-2 border-brand-dark' : 'border border-gray-200' }} overflow-hidden relative">
                <img src="{{ asset('images/products/satin-blouse.jpg') }}" class="w-full absolute top-1/2 -translate-y-1/2" alt="Saténová blúzka">
              </div>
            @endfor
          </div>
        </div>

        <!-- product info -->
        <div class="flex flex-col">
          <p class="text-sm text-gray-500 mb-1">Značka</p>
          <h1 class="text-2xl font-bold mb-2">Saténová blúzka</h1>
          <p class="text-2xl font-bold mb-1">39,99 €</p>
          <p class="text-xs text-gray-400 mb-5">Vrátane DPH</p>

          <hr class="border-gray-200 mb-5" />

          <!-- color selector -->
          <div class="mb-5">
            <p class="text-sm font-semibold mb-2">Farba: <span class="font-normal text-gray-500">Ružová</span></p>
            <div class="flex gap-2">
              <div class="w-8 h-8 rounded-full bg-gray-300 ring-2 ring-offset-2 ring-brand-dark"></div>
              <div class="w-8 h-8 rounded-full bg-gray-400"></div>
              <div class="w-8 h-8 rounded-full bg-gray-600"></div>
            </div>
          </div>

          <!-- size selector -->
          <div class="mb-5">
            <p class="text-sm font-semibold mb-2">Veľkosť</p>
            <div class="flex gap-2 flex-wrap">
              @foreach (['XS', 'S', 'M', 'L', 'XL'] as $size)
                <div class="w-11 h-11 border {{ $size === 'M' ? 'border-2 border-brand-dark bg-brand-dark text-white font-semibold' : 'border-gray-300' }} text-sm flex items-center justify-center">
                  {{ $size }}
                </div>
              @endforeach
            </div>
          </div>

          <!-- quantity (desktop only) -->
          <div class="hidden md:block mb-5">
            <p class="text-sm font-semibold mb-2">Množstvo</p>
            <x-store.quantity-selector :quantity="1" />
          </div>

          <!-- stock status -->
          <div id="sticky-trigger" class="flex items-center gap-2 md:mb-6 text-sm text-gray-600">
            <span class="w-2 h-2 rounded-full bg-gray-400 shrink-0"></span>
            <span>Skladom — doručenie do 2–3 dní</span>
          </div>

          <!-- add to cart (desktop only) -->
          <div class="hidden md:block">
            <button class="w-full bg-brand-dark hover:bg-brand-accent text-white font-bold text-sm tracking-widest py-4 transition-colors uppercase">
              Pridať do košíka
            </button>
          </div>
        </div>
      </div>

      <!-- tabs -->
      <div class="border-b border-gray-200 mb-6">
        <div class="flex gap-0 text-sm">
          <div class="px-4 py-3 font-semibold border-b-2 border-brand-dark text-brand-dark -mb-px cursor-default">Popis</div>
          <div class="px-4 py-3 text-gray-500 border-b-2 border-transparent -mb-px cursor-pointer hover:text-brand-dark transition-colors">Parametre</div>
        </div>
      </div>

      <!-- description -->
      <div class="mb-12 text-sm text-gray-700 leading-relaxed space-y-4">
        <p>Jemná saténová blúzka s hodvábnym leskom, vhodná na bežné nosenie aj na večerné príležitosti.</p>
        <div class="space-y-1">
          <p>Materiál: 100% polyester (saténová úprava)</p>
          <p>Údržba: pranie pri 30°C, nežehliť priamo</p>
          <p>Krajina pôvodu: Turecko</p>
        </div>
      </div>

      <!-- similar products -->
      <section>
        <h2 class="text-xl font-bold underline underline-offset-4 mb-6">Podobné produkty</h2>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-5">
          @for ($i = 0; $i < 4; $i++)
            <x-store.product-card href="#" image="images/products/satin-blouse.jpg" brand="H&M" name="Saténová blúzka" sizes="XS, S, M, L" price="39,99 €" />
          @endfor
        </div>
      </section>

    </div>
  </main>

  <!-- sticky mobile add-to-cart bar -->
  <script>
    document.addEventListener('DOMContentLoaded', () => {
      const bar = document.getElementById('sticky-bar');
      const trigger = document.getElementById('sticky-trigger');
      function update() {
        const rect = trigger.getBoundingClientRect();
        const passed = rect.top < window.innerHeight;
        bar.classList.toggle('translate-y-full', !passed);
        bar.classList.toggle('translate-y-0', passed);
      }
      window.addEventListener('scroll', update, { passive: true });
      update();
    });
  </script>
  <div id="sticky-bar" class="md:hidden fixed bottom-0 left-0 right-0 z-50 bg-white border-t border-gray-200 px-3 py-3 flex items-center gap-3 translate-y-full transition-transform duration-300">
    <div class="flex flex-1 items-stretch h-13 min-h-[3.25rem]">
      <div class="flex items-stretch border border-gray-300 border-r-0 shrink-0">
        <div class="w-10 flex items-center justify-center text-lg font-light text-brand-dark select-none px-1">&minus;</div>
        <div class="w-10 flex items-center justify-center text-sm font-semibold border-x border-gray-300">1</div>
        <div class="w-10 flex items-center justify-center text-lg font-light text-brand-dark select-none px-1">+</div>
      </div>
      <button class="flex-1 bg-brand-dark text-white font-bold text-sm tracking-widest uppercase transition-colors active:bg-brand-accent">
        Pridať do košíka
      </button>
    </div>
    <a href="#" class="shrink-0 flex items-center justify-center w-13 h-13 min-w-[3.25rem] min-h-[3.25rem] border border-gray-300">
      <img src="{{ asset('icons/shopping-cart.svg') }}" class="w-7 h-7" alt="Košík" />
    </a>
  </div>

</x-store.layout>
