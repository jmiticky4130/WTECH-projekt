<x-store.layout title="Objednávka dokončená — Bellura.sk">

  <main class="flex-1">
    <div class="max-w-3xl mx-auto px-4 py-16">
      <div class="border border-gray-200 p-8 sm:p-10 text-center">
        <p class="text-sm tracking-widest uppercase text-gray-500 mb-3">Objednávka odoslaná</p>
        <h1 class="text-3xl font-bold mb-4">Ďakujeme za nákup</h1>
        <p class="text-gray-600 mb-2">Vaša objednávka bola úspešne prijatá.</p>
        <p class="text-gray-600 mb-6">Číslo objednávky: <span class="font-semibold">#{{ $order->id }}</span></p>
        <div class="flex flex-col sm:flex-row gap-3 justify-center">
          <a href="{{ route('home') }}"
             class="inline-block bg-brand-dark hover:bg-brand-accent text-white font-bold text-sm tracking-widest py-3 px-6 transition-colors uppercase">
            Pokračovať v nákupe
          </a>
          <a href="{{ route('store.cart') }}"
             class="inline-block border border-gray-300 hover:bg-gray-50 text-sm py-3 px-6 transition-colors">
            Späť na košík
          </a>
        </div>
      </div>
    </div>
  </main>

</x-store.layout>
