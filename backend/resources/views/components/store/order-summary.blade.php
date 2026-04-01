@props([
  'items' => [],
  'subtotal' => '',
  'shipping' => null,
  'shippingLabel' => null,
  'payment' => null,
  'paymentLabel' => null,
  'total' => '',
  'vat' => null,
  'buttonText' => 'Pokračovať',
  'buttonLink' => null,
  'backLink' => '#',
  'backText' => '← Pokračovať v nákupe',
  'shippingInfo' => null,
  'paymentInfo' => null,
])

<aside class="w-full lg:w-80 shrink-0 lg:sticky lg:top-4">
  <div class="border border-gray-200 p-5">
    <h2 class="text-base font-bold mb-4">Súhrn objednávky</h2>

    @if (count($items) > 0)
      <div class="space-y-2 mb-4">
        @foreach ($items as $item)
          <div class="flex justify-between text-sm">
            <span class="text-gray-600">{{ $item['name'] }}</span>
            <span class="font-medium shrink-0 ml-2">{{ $item['price'] }}</span>
          </div>
        @endforeach
      </div>

      <div class="border-t border-gray-200 my-4"></div>
    @endif

    @if (!$items)
      <div class="flex justify-between text-sm mb-4">
        <span class="text-gray-600">Medzisúčet</span>
        <span class="font-bold">{{ $subtotal }}</span>
      </div>
    @endif

    @if ($shippingLabel || $paymentLabel)
      <div class="space-y-2 text-sm mb-4">
        <div class="flex justify-between">
          <span class="text-gray-600">Medzisúčet</span>
          <span class="font-medium">{{ $subtotal }}</span>
        </div>
        @if ($shippingLabel)
          <div class="flex justify-between">
            <span class="text-gray-600">Doprava ({{ $shippingLabel }})</span>
            <span class="font-medium">{{ $shipping }}</span>
          </div>
        @endif
        @if ($paymentLabel)
          <div class="flex justify-between">
            <span class="text-gray-600">Platba ({{ $paymentLabel }})</span>
            <span class="font-bold">{{ $payment }}</span>
          </div>
        @endif
      </div>
    @endif

    <div class="border-t border-gray-200 my-4"></div>

    <div class="flex justify-between items-baseline mb-1">
      <span class="text-base font-bold">Celkom</span>
      <span class="text-xl font-bold">{{ $total }}</span>
    </div>
    <p class="text-xs text-gray-500 mb-{{ $shippingInfo || $paymentInfo ? '4' : '5' }}">
      {{ $vat ? "Vrátane DPH $vat" : 'Vrátane DPH' }}
    </p>

    @if ($shippingInfo || $paymentInfo)
      <div class="text-xs text-gray-500 space-y-1 mb-5">
        @if ($shippingInfo)
          <p>Doprava: {{ $shippingInfo }}</p>
        @endif
        @if ($paymentInfo)
          <p>Platba: {{ $paymentInfo }}</p>
        @endif
      </div>
    @endif

    @if ($buttonLink)
      <a href="{{ $buttonLink }}" class="block w-full bg-brand-dark hover:bg-brand-accent text-white font-bold text-sm tracking-widest py-4 transition-colors uppercase mb-3 text-center">
        {{ $buttonText }}
      </a>
    @else
      <button class="w-full bg-brand-dark hover:bg-brand-accent text-white font-bold text-sm tracking-widest py-4 transition-colors uppercase mb-3">
        {{ $buttonText }}
      </button>
    @endif

    <div class="text-center">
      <a href="{{ $backLink }}" class="text-sm text-gray-500 hover:text-brand-dark transition-colors">{{ $backText }}</a>
    </div>
  </div>
</aside>
