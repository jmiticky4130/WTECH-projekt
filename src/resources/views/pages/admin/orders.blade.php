<x-admin.layout title="Objednávky — Bellura.sk" active="orders">
  <style>
    #modal-order-detail, #modal-delete-order { display: none; }
    #modal-order-detail:target, #modal-delete-order:target { display: flex; }
  </style>

  <div class="px-3 py-4 sm:px-6 sm:py-6 max-w-6xl">

    @if (session('success'))
      <div class="mb-4 bg-green-50 border border-green-200 text-green-700 text-sm px-4 py-3 rounded">{{ session('success') }}</div>
    @endif

    <!-- page header -->
    <div class="flex items-center justify-between mb-6">
      <div>
        <p class="text-xs text-gray-400 mb-0.5">Administrácia / Objednávky</p>
        <h1 class="text-2xl font-bold text-brand-dark">Objednávky</h1>
      </div>
    </div>

    <!-- summary cards -->
    @php $statusLabels = ['pending' => 'Čakajúce', 'paid' => 'Zaplatené', 'shipped' => 'Odoslané', 'delivered' => 'Doručené', 'cancelled' => 'Zrušené']; @endphp
    <div class="grid grid-cols-2 wide:grid-cols-5 gap-4 mb-6">
      @foreach ($statusLabels as $key => $label)
        <div class="bg-white rounded shadow px-4 py-4">
          <p class="text-xs text-gray-400 uppercase tracking-wider mb-1">{{ $label }}</p>
          <p class="text-2xl font-bold text-brand-dark">{{ $counts[$key] ?? 0 }}</p>
        </div>
      @endforeach
    </div>

    <!-- search & filter bar -->
    <form method="GET" action="{{ route('admin.orders') }}" class="bg-white shadow rounded mb-4 px-4 py-3 flex flex-wrap gap-3 items-center">
      <input
        type="text"
        name="q"
        value="{{ request('q') }}"
        placeholder="Hľadať objednávku (email / meno)..."
        class="flex-1 min-w-[180px] border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:border-brand-dark"
      />
      <select name="status" class="border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:border-brand-dark bg-white">
        <option value="">Všetky stavy</option>
        @foreach ($statusLabels as $key => $label)
          <option value="{{ $key }}" @selected(request('status') === $key)>{{ $label }}</option>
        @endforeach
      </select>
      <button type="submit" class="bg-brand-dark hover:bg-brand-accent text-white text-sm px-4 py-2 transition-colors">
        Hľadať
      </button>
    </form>

    <!-- mobile cards -->
    <div class="wide:hidden space-y-2">
      @forelse ($orders as $order)
        @php $statusColors = ['pending' => 'gray', 'paid' => 'blue', 'shipped' => 'yellow', 'delivered' => 'green', 'cancelled' => 'red']; $c = $statusColors[$order->status] ?? 'gray'; @endphp
        <div class="bg-white rounded shadow flex items-stretch overflow-hidden hover:bg-gray-50 transition-colors">
          <button type="button" onclick="openOrderDetail({{ $order->id }})" class="flex-1 flex items-start justify-between gap-3 px-4 py-3 min-w-0 text-left">
            <div class="min-w-0">
              <p class="font-semibold text-brand-dark truncate">{{ $order->first_name }} {{ $order->last_name }}</p>
              <p class="text-xs text-gray-400 truncate">{{ $order->email }}</p>
              <p class="text-xs text-gray-400 mt-0.5">{{ $order->created_at->format('d.m.Y') }} · {{ $order->shippingMethod?->name }}</p>
            </div>
            <div class="flex flex-col items-end gap-2 shrink-0">
              <span class="font-semibold text-sm">{{ number_format($order->total, 2, ',', ' ') }} €</span>
              <span class="text-xs bg-{{ $c }}-100 text-{{ $c }}-700 px-2 py-0.5 rounded font-medium">{{ $order->status }}</span>
            </div>
          </button>
          <button type="button" onclick="openDeleteOrderModal({{ $order->id }})" class="flex items-center px-3 border-l border-gray-100 text-gray-300 hover:text-red-500 hover:bg-red-50 transition-colors shrink-0">
            <img src="{{ asset('icons/trash.svg') }}" class="w-5 h-5 opacity-40 hover:opacity-100" alt="Vymazať" />
          </button>
        </div>
      @empty
        <p class="text-center text-gray-400 text-sm py-8">Žiadne objednávky.</p>
      @endforelse
    </div>

    <!-- desktop table -->
    <div class="hidden wide:block bg-white rounded shadow overflow-x-auto">
      <table class="w-full text-sm">
        <thead class="border-b border-gray-200 text-gray-500 text-xs uppercase tracking-wider">
          <tr>
            <th class="px-4 py-3 text-left font-semibold">#</th>
            <th class="px-4 py-3 text-left font-semibold">Zákazník</th>
            <th class="px-4 py-3 text-left font-semibold">Dátum</th>
            <th class="px-4 py-3 text-left font-semibold">Celkom</th>
            <th class="px-4 py-3 text-left font-semibold">Doprava</th>
            <th class="px-4 py-3 text-left font-semibold">Stav</th>
            <th class="px-4 py-3 text-left font-semibold">Akcie</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
          @forelse ($orders as $order)
            <tr class="hover:bg-gray-50 transition-colors">
              <td class="px-4 py-3 font-mono text-xs text-gray-500">#{{ $order->id }}</td>
              <td class="px-4 py-3">
                <p class="font-semibold text-brand-dark">{{ $order->first_name }} {{ $order->last_name }}</p>
                <p class="text-xs text-gray-400">{{ $order->email }}</p>
              </td>
              <td class="px-4 py-3 text-gray-500 text-xs">{{ $order->created_at->format('d.m.Y') }}</td>
              <td class="px-4 py-3 font-semibold">{{ number_format($order->total, 2, ',', ' ') }} €</td>
              <td class="px-4 py-3 text-xs text-gray-500">{{ $order->shippingMethod?->name }}</td>
              <td class="px-4 py-3">
                <form method="POST" action="{{ route('admin.orders.update', $order) }}">
                  @csrf
                  @method('PATCH')
                  <select name="status" onchange="this.form.submit()" class="border border-gray-200 px-2 py-1 text-xs bg-white focus:outline-none focus:border-brand-dark rounded">
                    @foreach (['pending', 'paid', 'shipped', 'delivered', 'cancelled'] as $s)
                      <option value="{{ $s }}" @selected($order->status === $s)>{{ $s }}</option>
                    @endforeach
                  </select>
                </form>
              </td>
              <td class="px-4 py-3">
                <div class="flex items-center gap-2">
                  <button type="button" onclick="openOrderDetail({{ $order->id }})" title="Detail" class="opacity-50 hover:opacity-100 transition-opacity">
                    <img src="{{ asset('icons/eye.svg') }}" class="w-5 h-5" alt="Detail" />
                  </button>
                  <button type="button" onclick="openDeleteOrderModal({{ $order->id }})" title="Vymazať" class="opacity-50 hover:opacity-100 transition-opacity">
                    <img src="{{ asset('icons/trash.svg') }}" class="w-5 h-5" alt="Vymazať" />
                  </button>
                </div>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="7" class="px-4 py-8 text-center text-gray-400 text-sm">Žiadne objednávky.</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    @if ($orders->hasPages())
      <div class="mt-4">{{ $orders->links() }}</div>
    @endif

  </div>


  <!-- modal: order detail -->
  <div id="modal-order-detail" class="fixed inset-0 bg-black/40 z-50 items-start justify-center px-4 py-8 overflow-y-auto">
    <div class="bg-white w-full max-w-lg mx-auto shadow-xl my-auto">
      <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200">
        <h2 class="text-lg font-bold" id="detail-title">Objednávka</h2>
        <a href="#!" class="text-gray-400 hover:text-brand-dark transition-colors text-xl leading-none">&#x2715;</a>
      </div>
      <div class="px-6 py-6 space-y-5" id="detail-body">
        <p class="text-sm text-gray-400">Načítavam...</p>
      </div>
      <div class="flex justify-end gap-3 px-6 py-4 border-t border-gray-100">
        <a href="#!" class="px-5 py-2.5 border border-gray-300 text-sm font-medium hover:bg-gray-50 transition-colors">Zavrieť</a>
        <button type="button" onclick="saveOrderStatus()" class="bg-brand-dark hover:bg-brand-accent text-white text-sm font-bold tracking-widest uppercase px-5 py-2.5 transition-colors">Uložiť stav</button>
      </div>
    </div>
  </div>


  <!-- modal: delete order -->
  <div id="modal-delete-order" class="fixed inset-0 bg-black/40 z-50 items-center justify-center px-4">
    <div class="bg-white w-full max-w-sm shadow-xl">
      <div class="px-6 py-6">
        <h2 class="text-lg font-bold mb-2">Vymazať objednávku</h2>
        <p class="text-sm text-gray-600 mb-6">Naozaj chcete vymazať objednávku <strong id="delete-order-num"></strong>? Táto akcia je nevratná.</p>
        <form id="form-delete-order" method="POST" action="">
          @csrf
          @method('DELETE')
          <div class="flex justify-end gap-3">
            <a href="#!" class="px-4 py-2.5 border border-gray-300 text-sm font-medium hover:bg-gray-50 transition-colors">Zrušiť</a>
            <button type="submit" class="px-4 py-2.5 bg-red-600 hover:bg-red-700 text-white text-sm font-bold transition-colors">Vymazať</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <script>
    let currentOrderId = null;

    function openOrderDetail(id) {
      currentOrderId = id;
      document.getElementById('detail-title').textContent = `Objednávka #${id}`;
      document.getElementById('detail-body').innerHTML = '<p class="text-sm text-gray-400">Načítavam...</p>';
      window.location.hash = 'modal-order-detail';
      fetch(`/admin/orders/${id}`)
        .then(r => r.json())
        .then(renderOrderDetail)
        .catch(() => { document.getElementById('detail-body').innerHTML = '<p class="text-sm text-red-500">Chyba načítania.</p>'; });
    }

    function renderOrderDetail(o) {
      const delivery = [o.street, o.city && o.zip ? `${o.zip} ${o.city}` : (o.city || o.zip), o.country].filter(Boolean).join(', ');
      const items = o.items.map(i => `
        <tr>
          <td class="px-3 py-2">${i.product_name}</td>
          <td class="px-3 py-2 text-xs text-gray-500">${i.color_name || '—'} / ${i.size} × ${i.quantity}</td>
          <td class="px-3 py-2 text-right font-semibold">${fmt(i.line_total)} €</td>
        </tr>
      `).join('');
      document.getElementById('detail-body').innerHTML = `
        <section>
          <h3 class="text-xs font-bold uppercase tracking-wider text-gray-400 mb-2">Zákazník</h3>
          <p class="text-sm font-semibold text-brand-dark">${o.first_name} ${o.last_name}</p>
          <p class="text-sm text-gray-500">${o.email}</p>
          ${o.phone ? `<p class="text-sm text-gray-500">${o.phone}</p>` : ''}
          ${delivery ? `<p class="text-sm text-gray-500 mt-1">${delivery}</p>` : ''}
          ${o.pickup_point ? `<p class="text-sm text-gray-500">Výdajné miesto: ${o.pickup_point}</p>` : ''}
        </section>
        <section>
          <h3 class="text-xs font-bold uppercase tracking-wider text-gray-400 mb-2">Položky</h3>
          <div class="border border-gray-200 rounded overflow-hidden">
            <table class="w-full text-sm">
              <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                  <th class="px-3 py-2 text-left text-xs font-semibold text-gray-500">Produkt</th>
                  <th class="px-3 py-2 text-left text-xs font-semibold text-gray-500">Variant</th>
                  <th class="px-3 py-2 text-right text-xs font-semibold text-gray-500">Cena</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-gray-100">${items}</tbody>
            </table>
          </div>
        </section>
        <section class="grid grid-cols-2 gap-4">
          <div>
            <h3 class="text-xs font-bold uppercase tracking-wider text-gray-400 mb-1">Doprava</h3>
            <p class="text-sm text-brand-dark">${o.shipping_method || '—'}</p>
            <p class="text-xs text-gray-400">${fmt(o.shipping_cost)} €</p>
          </div>
          <div>
            <h3 class="text-xs font-bold uppercase tracking-wider text-gray-400 mb-1">Platba</h3>
            <p class="text-sm text-brand-dark">${o.payment_method || '—'}</p>
            ${o.payment_fee > 0 ? `<p class="text-xs text-gray-400">Poplatok: ${fmt(o.payment_fee)} €</p>` : ''}
          </div>
        </section>
        <div class="flex justify-between items-center border-t border-gray-100 pt-4">
          <span class="text-sm font-bold">Celkom</span>
          <span class="text-lg font-bold text-brand-dark">${fmt(o.total)} €</span>
        </div>
        ${o.note ? `<div><h3 class="text-xs font-bold uppercase tracking-wider text-gray-400 mb-1">Poznámka</h3><p class="text-sm text-gray-600">${o.note}</p></div>` : ''}
        <div>
          <label class="block text-xs font-bold uppercase tracking-wider text-gray-400 mb-1.5">Stav objednávky</label>
          <select id="detail-status" class="w-full border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:border-brand-dark bg-white">
            ${['pending','paid','shipped','delivered','cancelled'].map(s => `<option value="${s}" ${s === o.status ? 'selected' : ''}>${s}</option>`).join('')}
          </select>
        </div>
      `;
    }

    function saveOrderStatus() {
      if (!currentOrderId) return;
      const status = document.getElementById('detail-status').value;
      const form = document.createElement('form');
      form.method = 'POST';
      form.action = `/admin/orders/${currentOrderId}`;
      form.innerHTML = `
        <input type="hidden" name="_token" value="{{ csrf_token() }}" />
        <input type="hidden" name="_method" value="PATCH" />
        <input type="hidden" name="status" value="${status}" />
      `;
      document.body.appendChild(form);
      form.submit();
    }

    function openDeleteOrderModal(id) {
      document.getElementById('delete-order-num').textContent = `#${id}`;
      document.getElementById('form-delete-order').action = `/admin/orders/${id}`;
      window.location.hash = 'modal-delete-order';
    }

    function fmt(n) {
      return parseFloat(n).toFixed(2).replace('.', ',');
    }
  </script>

</x-admin.layout>
