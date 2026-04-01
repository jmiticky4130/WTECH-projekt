<x-store.layout title="Bellura.sk">

  <!-- hero section -->
  <section class="bg-gray-200 relative overflow-hidden">
    <div class="max-w-7xl mx-auto px-4 py-48 text-center relative z-10">
      <h1 class="text-4xl font-bold mb-2">Reklamný banner</h1>
      <p class="text-gray-600 mb-6">Podnadpis banneru</p>
      <a href="#" class="inline-block bg-brand-dark text-white font-semibold text-sm tracking-widest px-8 py-3.5 hover:bg-brand-accent transition-colors uppercase">
        Nakupovať
      </a>
    </div>
  </section>

  <!-- main content -->
  <main class="flex-1">
    <div class="max-w-7xl mx-auto px-4 py-12 space-y-14">

      <!-- categories -->
      <section>
        <h2 class="text-xl font-bold underline underline-offset-4 mb-6">Kategórie</h2>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-5">
          <x-store.category-card href="#" image="images/products/dress.jpg" label="Šaty" />
          <x-store.category-card href="#" image="images/products/dress.jpg" label="Topánky" />
          <x-store.category-card href="#" image="images/products/dress.jpg" label="Doplnky" />
          <x-store.category-card href="#" image="images/products/dress.jpg" label="Výpredaj" />
        </div>
      </section>

      <!-- featured products -->
      <section>
        <h2 class="text-xl font-bold underline underline-offset-4 mb-6">Odporúčané produkty</h2>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-5">
          <x-store.product-card href="#" image="images/products/satin-blouse.jpg" brand="H&M" name="Saténová blúzka" sizes="XS, S, M, L" price="39,99 €" />
          <x-store.product-card href="#" image="images/products/satin-blouse.jpg" brand="H&M" name="Saténová blúzka" sizes="XS, S, M, L" price="39,99 €" />
          <x-store.product-card href="#" image="images/products/satin-blouse.jpg" brand="H&M" name="Saténová blúzka" sizes="XS, S, M, L" price="39,99 €" />
          <x-store.product-card href="#" image="images/products/satin-blouse.jpg" brand="H&M" name="Saténová blúzka" sizes="XS, S, M, L" price="39,99 €" />
        </div>
      </section>

      <!-- new products -->
      <section>
        <h2 class="text-xl font-bold underline underline-offset-4 mb-6">Najnovšie produkty</h2>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-5">
          <x-store.product-card href="#" image="images/products/satin-blouse.jpg" brand="H&M" name="Saténová blúzka" sizes="XS, S, M, L" price="39,99 €" />
          <x-store.product-card href="#" image="images/products/satin-blouse.jpg" brand="H&M" name="Saténová blúzka" sizes="XS, S, M, L" price="39,99 €" />
          <x-store.product-card href="#" image="images/products/satin-blouse.jpg" brand="H&M" name="Saténová blúzka" sizes="XS, S, M, L" price="39,99 €" />
          <x-store.product-card href="#" image="images/products/satin-blouse.jpg" brand="H&M" name="Saténová blúzka" sizes="XS, S, M, L" price="39,99 €" />
        </div>
      </section>

      <!-- brands -->
      <section>
        <h2 class="text-xl font-bold underline underline-offset-4 mb-6">Obľúbené značky</h2>
        <div class="grid grid-cols-3 md:grid-cols-6 gap-4">
          @foreach (['Zara', 'H&M', 'Reserved', 'Mango', 'Bershka', 'Pull&Bear'] as $brand)
            <a href="#" class="border border-gray-300 py-2.5 px-4 text-center text-sm text-gray-500 hover:border-brand-dark hover:text-brand-dark transition-colors">
              {{ $brand }}
            </a>
          @endforeach
        </div>
      </section>

    </div>
  </main>

</x-store.layout>
