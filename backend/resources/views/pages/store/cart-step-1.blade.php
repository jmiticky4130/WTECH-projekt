<x-store.layout title="Nákupný košík — Bellura.sk">

  <x-store.step-indicator :currentStep="1" />

  <main class="flex-1">
    <div class="max-w-7xl mx-auto px-4 py-8">
      <div class="flex flex-col lg:flex-row gap-8 lg:items-start">

        <!-- cart items -->
        <div class="flex-1 min-w-0">
          <h1 class="text-2xl font-bold mb-6">
            Nákupný košík
            <span class="text-base font-normal text-gray-500 ml-2">(2 položky)</span>
          </h1>

          <div class="border border-gray-200">
            <!-- desktop header -->
            <div class="hidden md:grid grid-cols-[1fr_5rem_6rem_8rem_7rem_5rem] px-4 py-3 border-b border-gray-200 bg-gray-50 text-xs font-bold uppercase tracking-wider text-gray-500">
              <span>Produkt</span>
              <span class="text-center">Veľkosť</span>
              <span class="text-center">Cena</span>
              <span class="text-center">Množstvo</span>
              <span class="text-right pr-4">Spolu</span>
              <span class="text-center">Odstrániť</span>
            </div>

            <div class="px-4 py-4 border-b border-gray-200">
              <x-store.cart-item
                image="images/products/satin-blouse.jpg"
                brand="H&M"
                name="Saténová blúzka"
                color="ružová"
                size="M"
                price="39,99 €"
                :quantity="1"
                total="39,99 €"
              />
            </div>

            <div class="px-4 py-4">
              <x-store.cart-item
                image="images/products/satin-blouse.jpg"
                brand="H&M"
                name="Oversized bavlnené tričko"
                color="biela"
                size="L"
                price="14,99 €"
                :quantity="2"
                total="29,98 €"
              />
            </div>
          </div>
        </div>

        <!-- order summary -->
        <x-store.order-summary
          :items="[]"
          subtotal="69,97 €"
          total="69,97 €"
          buttonText="Pokračovať"
          :buttonLink="route('store.cart.shipping')"
          :backLink="route('home')"
          backText="← Pokračovať v nákupe"
        />

      </div>
    </div>
  </main>

</x-store.layout>
