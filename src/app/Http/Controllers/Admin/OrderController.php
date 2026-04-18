<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateOrderStatusRequest;
use App\Models\Order;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OrderController extends Controller
{
    public function index(Request $request): View
    {
        $query = Order::with(['shippingMethod', 'paymentMethod'])->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        if ($request->filled('q')) {
            $term = '%'.mb_strtolower($request->input('q')).'%';
            $query->where(function ($w) use ($term) {
                $w->whereRaw('LOWER(email) LIKE ?', [$term])
                    ->orWhereRaw('LOWER(first_name) LIKE ?', [$term])
                    ->orWhereRaw('LOWER(last_name) LIKE ?', [$term]);
            });
        }

        $orders = $query->paginate(20)->withQueryString();

        $counts = Order::query()
            ->selectRaw('status, COUNT(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status')
            ->toArray();

        return view('pages.admin.orders', compact('orders', 'counts'));
    }

    public function show(Order $order): JsonResponse
    {
        $order->load(['items', 'shippingMethod', 'paymentMethod']);

        return response()->json([
            'id' => $order->id,
            'status' => $order->status,
            'email' => $order->email,
            'phone' => $order->phone,
            'first_name' => $order->first_name,
            'last_name' => $order->last_name,
            'street' => $order->street,
            'city' => $order->city,
            'zip' => $order->zip,
            'country' => $order->country,
            'pickup_point' => $order->pickup_point,
            'shipping_method' => $order->shippingMethod?->name,
            'payment_method' => $order->paymentMethod?->name,
            'shipping_cost' => $order->shipping_cost,
            'payment_fee' => $order->payment_fee,
            'subtotal' => $order->subtotal,
            'total' => $order->total,
            'note' => $order->note,
            'created_at' => $order->created_at->format('d.m.Y'),
            'items' => $order->items->map(fn ($item) => [
                'product_name' => $item->product_name,
                'color_name' => $item->color_name,
                'size' => $item->size,
                'quantity' => $item->quantity,
                'unit_price' => $item->unit_price,
                'line_total' => $item->line_total,
            ]),
        ]);
    }

    public function update(UpdateOrderStatusRequest $request, Order $order): RedirectResponse
    {
        $order->update(['status' => $request->input('status')]);

        return redirect()->route('admin.orders')->with('success', 'Stav objednávky bol zmenený.');
    }

    public function destroy(Order $order): RedirectResponse
    {
        $order->delete();

        return redirect()->route('admin.orders')->with('success', 'Objednávka bola vymazaná.');
    }
}
