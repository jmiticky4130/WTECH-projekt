<x-store.layout title="Bellura.sk">

  <!-- hero section -->
  <section class="bg-gray-200 relative overflow-hidden">
    <div class="max-w-7xl mx-auto px-4 py-48 text-center relative z-10">
      <h1 class="text-4xl font-bold mb-2">Reklamný banner</h1>
      <p class="text-gray-600 mb-6">Podnadpis banneru</p>
      <a href="{{ route('store.category', ['p1' => 'zeny']) }}" class="inline-block bg-brand-dark text-white font-semibold text-sm tracking-widest px-8 py-3.5 hover:bg-brand-accent transition-colors uppercase">
        Nakupovať
      </a>
    </div>
  </section>

  <!-- main content -->
  <main class="flex-1">
    <div class="max-w-7xl mx-auto px-4 py-12 space-y-14">

      <!-- categories -->
      @php $categoryItems = \App\Support\CategoryMapping::STORE_SUB_NAV_ITEMS; @endphp
      @if (!empty($categoryItems))
        <section>
          <h2 class="text-xl font-bold underline underline-offset-4 mb-6">Kategórie</h2>
          <div class="grid grid-cols-2 md:grid-cols-4 gap-5">
            @foreach ($categoryItems as $item)
              <x-store.category-card
                :href="url('/kategoria/' . $item['slug'])"
                :image="'images/categories/placeholder.svg'"
                :alt="$item['label']"
                :label="$item['label']"
              />
            @endforeach
          </div>
        </section>
      @endif

      <!-- featured products -->
      @if ($featuredProducts->isNotEmpty())
        <section>
          <h2 class="text-xl font-bold underline underline-offset-4 mb-6">Odporúčané produkty</h2>
          <div class="grid grid-cols-2 md:grid-cols-4 gap-5">
            @foreach ($featuredProducts as $product)
              <x-store.product-card
                :href="route('store.product', ['slug' => $product->slug])"
                :image="$product->image_path"
                :brand="$product->brand_name"
                :name="$product->name"
                :sizes="$product->sizes"
                :price="number_format($product->min_price, 2, ',', ' ') . ' €'"
              />
            @endforeach
          </div>
        </section>
      @endif

      <!-- newest products -->
      @if ($newestProducts->isNotEmpty())
        <section>
          <h2 class="text-xl font-bold underline underline-offset-4 mb-6">Najnovšie produkty</h2>
          <div class="grid grid-cols-2 md:grid-cols-4 gap-5">
            @foreach ($newestProducts as $product)
              <x-store.product-card
                :href="route('store.product', ['slug' => $product->slug])"
                :image="$product->image_path"
                :brand="$product->brand_name"
                :name="$product->name"
                :sizes="$product->sizes"
                :price="number_format($product->min_price, 2, ',', ' ') . ' €'"
              />
            @endforeach
          </div>
        </section>
      @endif

      <!-- brands -->
      @if ($brands->isNotEmpty())
        <section>
          <h2 class="text-xl font-bold underline underline-offset-4 mb-6">Obľúbené značky</h2>
          <div class="grid grid-cols-3 md:grid-cols-6 gap-4">
            @foreach ($brands as $brand)
              <a href="{{ route('store.category', ['p1' => 'zeny']) }}?brand[]={{ $brand->id }}" class="border border-gray-300 py-2.5 px-4 text-center text-sm text-gray-500 hover:border-brand-dark hover:text-brand-dark transition-colors">
                {{ $brand->name }}
              </a>
            @endforeach
          </div>
        </section>
      @endif

    </div>
  </main>

</x-store.layout>
