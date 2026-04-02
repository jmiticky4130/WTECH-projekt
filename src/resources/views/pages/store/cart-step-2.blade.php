<x-store.layout title="Doprava a platba — Bellura.sk">

  <x-store.step-indicator :currentStep="2" />

  <main class="flex-1">
    <div class="max-w-7xl mx-auto px-4 py-8">
      <div class="flex flex-col lg:flex-row gap-8 lg:items-start">

        <div class="flex-1 min-w-0">
          <h1 class="text-2xl font-bold mb-6">Doprava a platba</h1>

          <!-- shipping methods -->
          <section class="mb-8">
            <h2 class="text-base font-bold mb-3">Spôsob dopravy</h2>

            @php
              $shippingOptions = [
                ['name' => 'doprava', 'label' => 'Kuriér DPD', 'desc' => 'Doručenie do 2–3 pracovných dní na adresu', 'price' => '3,99 €', 'checked' => true],
                ['name' => 'doprava', 'label' => 'Slovenská pošta', 'desc' => 'Doručenie do 3–5 pracovných dní', 'price' => '2,49 €', 'checked' => false],
                ['name' => 'doprava', 'label' => 'Zásielkovňa (výdajné miesto)', 'desc' => 'Vyzdvihnutie na najbližšom výdajnom mieste', 'price' => '1,99 €', 'checked' => false],
                ['name' => 'doprava', 'label' => 'Osobný odber — pobočka Bratislava', 'desc' => 'Obchodná 12, Bratislava — pripravené do 24h', 'price' => 'Zadarmo', 'checked' => false],
              ];
            @endphp

            @foreach ($shippingOptions as $option)
              <label class="flex items-center gap-4 {{ $option['checked'] ? 'border-2 border-brand-dark' : 'border border-gray-200 hover:border-gray-400' }} rounded p-4 mb-2 cursor-pointer transition-colors">
                <input type="radio" name="doprava" {{ $option['checked'] ? 'checked' : '' }} class="accent-brand-dark w-4 h-4 shrink-0" />
                <div class="flex-1 min-w-0">
                  <p class="font-semibold text-sm">{{ $option['label'] }}</p>
                  <p class="text-xs text-gray-500 mt-0.5">{{ $option['desc'] }}</p>
                </div>
                <span class="text-sm font-semibold shrink-0">{{ $option['price'] }}</span>
              </label>
            @endforeach
          </section>

          <!-- payment methods -->
          <section>
            <h2 class="text-base font-bold mb-3">Spôsob platby</h2>

            @php
              $paymentOptions = [
                ['label' => 'Platba kartou online', 'price' => null, 'checked' => true],
                ['label' => 'Dobierka (platba pri prevzatí)', 'price' => '+1,50 €', 'checked' => false],
                ['label' => 'Google Pay', 'price' => null, 'checked' => false],
                ['label' => 'Bankový prevod', 'price' => null, 'checked' => false],
              ];
            @endphp

            @foreach ($paymentOptions as $option)
              <label class="flex items-center gap-4 {{ $option['checked'] ? 'border-2 border-brand-dark' : 'border border-gray-200 hover:border-gray-400' }} rounded p-4 mb-2 cursor-pointer transition-colors">
                <input type="radio" name="platba" {{ $option['checked'] ? 'checked' : '' }} class="accent-brand-dark w-4 h-4 shrink-0" />
                <div class="flex-1 min-w-0">
                  <p class="font-semibold text-sm">{{ $option['label'] }}</p>
                </div>
                @if ($option['price'])
                  <span class="text-sm font-semibold shrink-0">{{ $option['price'] }}</span>
                @endif
              </label>
            @endforeach
          </section>
        </div>

        <!-- order summary -->
        <x-store.order-summary
          :items="[
            ['name' => 'Kvetinové midi šaty × 1', 'price' => '39,99 €'],
            ['name' => 'Oversized bavlnené tričko × 2', 'price' => '29,98 €'],
          ]"
          subtotal="69,97 €"
          shippingLabel="Kuriér DPD"
          shipping="3,99 €"
          paymentLabel="Kartou"
          payment="Zadarmo"
          total="73,96 €"
          vat="12,33 €"
          buttonText="Pokračovať"
          :buttonLink="route('store.cart.details')"
          :backLink="route('store.cart')"
          backText="← Späť na košík"
        />

      </div>
    </div>
  </main>

</x-store.layout>
