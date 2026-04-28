@php
  use App\Models\PaymentMethod;
  use App\Models\Subcategory;
  use App\Support\CategoryMapping;
  use Illuminate\Support\Facades\Cache;

  $paymentMethods = PaymentMethod::active()->get();
  $subnavItems = Cache::remember('subnav:all', 3600, function () {
    return Subcategory::orderBy('sort_order')->orderBy('id')->get(['name', 'slug'])->map(fn ($s) => [
      'label' => $s->name,
      'slug'  => $s->slug,
    ])->all();
  });
@endphp

<footer class="bg-brand-dark text-white mt-8">
  <div class="max-w-7xl mx-auto px-4 py-10">
    <div class="grid grid-cols-2 md:grid-cols-4 gap-8 text-sm">

      {{-- Brand --}}
      <div class="col-span-2 md:col-span-1">
        <p class="text-lg font-bold tracking-wide mb-3">Bellura.sk</p>
        <p class="text-gray-400 leading-relaxed">Slovenský módny e-shop s oblečením, topánkami a doplnkami pre ženy, mužov a deti.</p>
      </div>

      {{-- Categories (genders) --}}
      <div>
        <p class="font-bold uppercase tracking-wider mb-3">Kategórie</p>
        <ul class="space-y-2 text-gray-300">
          @foreach(CategoryMapping::GENDER_NAMES as $slug => $name)
            <li>
              <a href="{{ url('/kategoria/' . $slug) }}" class="hover:text-white transition-colors">
                {{ $name }}
              </a>
            </li>
          @endforeach
        </ul>
      </div>

      {{-- Subcategories (product types) --}}
      <div>
        <p class="font-bold uppercase tracking-wider mb-3">Nakupovanie</p>
        <ul class="space-y-2 text-gray-300">
          @foreach($subnavItems as $item)
            <li>
              <a href="{{ url('/kategoria/' . $item['slug']) }}" class="hover:text-white transition-colors">
                {{ $item['label'] }}
              </a>
            </li>
          @endforeach
        </ul>
      </div>

      {{-- Payment methods --}}
      <div>
        <p class="font-bold uppercase tracking-wider mb-3">Platba</p>
        @if($paymentMethods->isNotEmpty())
          <ul class="space-y-2 text-gray-300">
            @foreach($paymentMethods as $method)
              <li>{{ $method->name }}</li>
            @endforeach
          </ul>
        @else
          <p class="text-gray-500">Kartou, prevodom, dobierkou</p>
        @endif
      </div>

    </div>
  </div>

  <div class="border-t border-gray-600">
    <div class="max-w-7xl mx-auto px-4 py-4 text-xs text-gray-400">
      &copy; {{ date('Y') }} Bellura s.r.o. Všetky práva vyhradené.
    </div>
  </div>
</footer>
