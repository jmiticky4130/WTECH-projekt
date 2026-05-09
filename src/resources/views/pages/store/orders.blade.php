<x-store.layout title="Moje objednávky — Bellura.sk">

  <main class="flex-1">
    <div class="max-w-4xl mx-auto px-4 py-10">

      <h1 class="text-2xl font-bold mb-8">Moje objednávky</h1>

      @if($orders->isEmpty())
        <div class="border border-gray-200 p-10 text-center">
          <p class="text-gray-500 mb-4">Zatiaľ nemáte žiadne objednávky.</p>
          <a href="{{ route('home') }}"
             class="inline-block bg-brand-dark hover:bg-brand-accent text-white font-bold text-sm tracking-widest py-3 px-6 transition-colors uppercase">
            Začať nakupovať
          </a>
        </div>
      @else
        <div class="space-y-4">
          @foreach($orders as $order)
            <div x-data="{ open: false }" class="border border-gray-200">

              <button @click="open = !open"
                      class="w-full flex flex-col sm:flex-row sm:items-center justify-between gap-3 px-5 py-4 text-left hover:bg-gray-50 transition-colors">
                <div class="flex flex-col sm:flex-row sm:items-center gap-2 sm:gap-6">
                  <span class="font-semibold text-sm">#{{ $order->id }}</span>
                  <span class="text-sm text-gray-500">{{ $order->created_at->format('d.m.Y') }}</span>
                  <span class="text-sm">
                    @php
                      $statusLabels = [
                        'pending'    => ['label' => 'Čaká na spracovanie', 'class' => 'bg-yellow-100 text-yellow-800'],
                        'processing' => ['label' => 'Spracováva sa',        'class' => 'bg-blue-100 text-blue-800'],
                        'shipped'    => ['label' => 'Odoslaná',             'class' => 'bg-indigo-100 text-indigo-800'],
                        'delivered'  => ['label' => 'Doručená',             'class' => 'bg-green-100 text-green-800'],
                        'cancelled'  => ['label' => 'Zrušená',              'class' => 'bg-red-100 text-red-800'],
                      ];
                      $s = $statusLabels[$order->status] ?? ['label' => $order->status, 'class' => 'bg-gray-100 text-gray-700'];
                    @endphp
                    <span class="inline-block px-2 py-0.5 text-xs font-medium rounded {{ $s['class'] }}">{{ $s['label'] }}</span>
                  </span>
                </div>
                <div class="flex items-center gap-4 shrink-0">
                  <span class="font-bold text-sm">{{ number_format($order->total, 2, ',', ' ') }} €</span>
                  <img src="{{ asset('icons/chevron-down.svg') }}" alt=""
                       :class="open ? 'rotate-180' : ''"
                       class="w-4 h-4 transition-transform opacity-50" />
                </div>
              </button>

              <div x-show="open" class="border-t border-gray-200 px-5 py-5">

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 mb-5">
                  <div>
                    <p class="text-xs text-gray-500 mb-1 uppercase tracking-wide">Doprava</p>
                    <p class="text-sm">{{ $order->shippingMethod?->name ?? '—' }}</p>
                  </div>
                  <div>
                    <p class="text-xs text-gray-500 mb-1 uppercase tracking-wide">Platba</p>
                    <p class="text-sm">{{ $order->paymentMethod?->name ?? '—' }}</p>
                  </div>
                  @if($order->street)
                  <div>
                    <p class="text-xs text-gray-500 mb-1 uppercase tracking-wide">Doručovacia adresa</p>
                    <p class="text-sm">{{ $order->first_name }} {{ $order->last_name }}</p>
                    <p class="text-sm">{{ $order->street }}</p>
                    <p class="text-sm">{{ $order->zip }} {{ $order->city }}</p>
                    <p class="text-sm">{{ $order->country }}</p>
                  </div>
                  @elseif($order->pickup_point)
                  <div>
                    <p class="text-xs text-gray-500 mb-1 uppercase tracking-wide">Výdajné miesto</p>
                    <p class="text-sm">{{ $order->first_name }} {{ $order->last_name }}</p>
                    <p class="text-sm">{{ $order->pickup_point }}</p>
                  </div>
                  @else
                  <div>
                    <p class="text-xs text-gray-500 mb-1 uppercase tracking-wide">Osobný odber</p>
                    <p class="text-sm">{{ $order->first_name }} {{ $order->last_name }}</p>
                  </div>
                  @endif
                </div>

                <div class="border-t border-gray-100 pt-4">
                  <p class="text-xs text-gray-500 mb-3 uppercase tracking-wide">Položky</p>
                  <div class="space-y-2">
                    @foreach($order->items as $item)
                      <div class="flex justify-between text-sm">
                        <span class="text-gray-700">
                          {{ $item->product_name }}
                          <span class="text-gray-400">— {{ $item->color_name }}, {{ $item->size }} × {{ $item->quantity }}</span>
                        </span>
                        <span class="font-medium shrink-0 ml-4">{{ number_format($item->line_total, 2, ',', ' ') }} €</span>
                      </div>
                    @endforeach
                  </div>
                  <div class="border-t border-gray-100 mt-3 pt-3 space-y-1 text-sm">
                    <div class="flex justify-between text-gray-500">
                      <span>Medzisúčet</span>
                      <span>{{ number_format($order->subtotal, 2, ',', ' ') }} €</span>
                    </div>
                    @if($order->shipping_cost > 0)
                    <div class="flex justify-between text-gray-500">
                      <span>Doprava</span>
                      <span>{{ number_format($order->shipping_cost, 2, ',', ' ') }} €</span>
                    </div>
                    @endif
                    @if($order->payment_fee > 0)
                    <div class="flex justify-between text-gray-500">
                      <span>Poplatok za platbu</span>
                      <span>{{ number_format($order->payment_fee, 2, ',', ' ') }} €</span>
                    </div>
                    @endif
                    <div class="flex justify-between font-bold pt-1">
                      <span>Celkom</span>
                      <span>{{ number_format($order->total, 2, ',', ' ') }} €</span>
                    </div>
                  </div>
                </div>

              </div>
            </div>
          @endforeach
        </div>

        <div class="mt-6">
          {{ $orders->links() }}
        </div>
      @endif

    </div>
  </main>

</x-store.layout>
