<?php

namespace App\Http\Controllers\Store;

use App\Data\PlaceOrderData;
use App\Http\Controllers\Controller;
use App\Http\Requests\PlaceOrderRequest;
use App\Models\CartItem;
use App\Models\Order;
use App\Models\PaymentMethod;
use App\Models\ShippingMethod;
use App\Services\OrderService;
use App\Support\ProductImageUrl;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CartController extends Controller
{
    private function itemQuery(): Builder
    {
        return DB::table('cart_items')
            ->join('product_variants as pv', 'cart_items.variant_id', '=', 'pv.id')
            ->join('products as p', 'p.id', '=', 'pv.product_id')
            ->leftJoin('brands as b', 'b.id', '=', 'p.brand_id')
            ->join('colors as c', 'c.id', '=', 'pv.color_id')
            ->leftJoinSub(
                DB::table('product_images')
                    ->select(['product_id', DB::raw('MIN(image_path) as image_path')])
                    ->whereRaw('"is_primary" = true')
                    ->groupBy('product_id'),
                'pi',
                'pi.product_id',
                '=',
                'p.id'
            )
            ->select([
                'cart_items.id as item_id',
                'pv.id as variant_id',
                'p.id as product_id',
                'p.name',
                'p.slug',
                DB::raw('b.name as brand_name'),
                DB::raw('c.name as color_name'),
                'pv.size',
                'pv.price',
                'pv.stock_quantity',
                'cart_items.quantity as qty',
                'pi.image_path',
            ]);
    }

    public function data(Request $request): JsonResponse
    {
        if (Auth::check()) {
            $items = $this->itemQuery()
                ->where('cart_items.user_id', Auth::id())
                ->get()
                ->map(function ($item) {
                    $item->image_url = ProductImageUrl::resolve($item->image_path);

                    return $item;
                });

            return response()->json(['items' => $items]);
        }

        $rawItems = collect($request->input('items', []));
        if ($rawItems->isEmpty()) {
            return response()->json(['items' => []]);
        }

        $variantIds = $rawItems->pluck('variant_id')->filter()->map(fn ($v) => (int) $v)->values();
        $qtyMap = $rawItems->keyBy('variant_id')->map(fn ($i) => (int) ($i['qty'] ?? 1));

        $variants = DB::table('product_variants as pv')
            ->join('products as p', 'p.id', '=', 'pv.product_id')
            ->leftJoin('brands as b', 'b.id', '=', 'p.brand_id')
            ->join('colors as c', 'c.id', '=', 'pv.color_id')
            ->leftJoinSub(
                DB::table('product_images')
                    ->select(['product_id', DB::raw('MIN(image_path) as image_path')])
                    ->whereRaw('"is_primary" = true')
                    ->groupBy('product_id'),
                'pi',
                'pi.product_id',
                '=',
                'p.id'
            )
            ->select([
                'pv.id as variant_id',
                'p.id as product_id',
                'p.name',
                'p.slug',
                DB::raw('b.name as brand_name'),
                DB::raw('c.name as color_name'),
                'pv.size',
                'pv.price',
                'pv.stock_quantity',
                'pi.image_path',
            ])
            ->whereIn('pv.id', $variantIds)
            ->get()
            ->map(fn ($v) => array_merge((array) $v, [
                'item_id' => null,
                'qty' => $qtyMap[$v->variant_id] ?? 1,
                'image_url' => ProductImageUrl::resolve($v->image_path),
            ]));

        return response()->json(['items' => $variants]);
    }

    public function add(Request $request): JsonResponse
    {
        $request->validate([
            'variant_id' => ['required', 'integer', 'exists:product_variants,id'],
            'qty' => ['required', 'integer', 'min:1'],
        ]);

        $variantId = $request->integer('variant_id');
        $qty = $request->integer('qty', 1);

        $variant = DB::table('product_variants as pv')
            ->join('products as p', 'p.id', '=', 'pv.product_id')
            ->leftJoin('brands as b', 'b.id', '=', 'p.brand_id')
            ->join('colors as c', 'c.id', '=', 'pv.color_id')
            ->leftJoinSub(
                DB::table('product_images')
                    ->select(['product_id', DB::raw('MIN(image_path) as image_path')])
                    ->whereRaw('"is_primary" = true')
                    ->groupBy('product_id'),
                'pi',
                'pi.product_id',
                '=',
                'p.id'
            )
            ->select(['pv.id as variant_id', 'p.id as product_id', 'p.name', 'p.slug',
                DB::raw('b.name as brand_name'), DB::raw('c.name as color_name'),
                'pv.size', 'pv.price', 'pv.stock_quantity', 'pi.image_path'])
            ->where('pv.id', $variantId)
            ->first();

        if (! $variant) {
            return response()->json([
                'message' => 'Vybrany variant uz nie je dostupny.',
            ], 422);
        }

        $availableStock = max(0, (int) ($variant->stock_quantity ?? 0));

        if ($availableStock < 1) {
            return response()->json([
                'message' => 'Vybrany variant je momentalne vypredany.',
                'available_stock' => 0,
            ], 422);
        }

        if ($qty > $availableStock) {
            return response()->json([
                'message' => "Na sklade je dostupnych uz len {$availableStock} ks.",
                'available_stock' => $availableStock,
            ], 422);
        }

        $variant->image_url = ProductImageUrl::resolve($variant->image_path);

        $itemId = null;
        $cartCount = null;
        $responseQty = $qty;

        if (Auth::check()) {
            $userId = Auth::id();
            $item = CartItem::where('user_id', $userId)->where('variant_id', $variantId)->first();

            if ($item) {
                $newQty = (int) $item->quantity + $qty;

                if ($newQty > $availableStock) {
                    return response()->json([
                        'message' => "Na sklade je dostupnych uz len {$availableStock} ks.",
                        'available_stock' => $availableStock,
                    ], 422);
                }

                $item->update(['quantity' => $newQty]);
            } else {
                $item = CartItem::create(['user_id' => $userId, 'variant_id' => $variantId, 'quantity' => $qty]);
            }

            $item->refresh();
            $itemId = $item->id;
            $responseQty = (int) $item->quantity;
            $cartCount = CartItem::where('user_id', $userId)->sum('quantity');
        }

        return response()->json([
            ...(array) $variant,
            'item_id' => $itemId,
            'qty' => $responseQty,
            'available_stock' => $availableStock,
            'cart_count' => $cartCount,
        ]);
    }

    public function update(Request $request, CartItem $cartItem): JsonResponse
    {
        abort_unless(Auth::check() && $cartItem->user_id === Auth::id(), 403);

        $request->validate(['qty' => ['required', 'integer', 'min:1']]);

        $newQty = $request->integer('qty');
        $availableStock = (int) DB::table('product_variants')
            ->where('id', $cartItem->variant_id)
            ->value('stock_quantity');

        if ($availableStock < 1) {
            return response()->json([
                'message' => 'Vybrany variant je momentalne vypredany.',
                'available_stock' => 0,
            ], 422);
        }

        if ($newQty > $availableStock) {
            return response()->json([
                'message' => "Na sklade je dostupnych uz len {$availableStock} ks.",
                'available_stock' => $availableStock,
            ], 422);
        }

        $cartItem->update(['quantity' => $newQty]);

        return response()->json(['ok' => true]);
    }

    public function remove(CartItem $cartItem): JsonResponse
    {
        abort_unless(Auth::check() && $cartItem->user_id === Auth::id(), 403);
        $cartItem->delete();

        return response()->json(['ok' => true]);
    }

    public function merge(Request $request): JsonResponse
    {
        if (! Auth::check()) {
            return response()->json(['ok' => false], 401);
        }

        $items = $request->input('items', []);
        $userId = Auth::id();

        foreach ($items as $item) {
            $variantId = isset($item['variant_id']) ? (int) $item['variant_id'] : null;
            $qty = isset($item['qty']) ? (int) $item['qty'] : 1;

            if (! $variantId || $qty < 1) {
                continue;
            }

            $stock = (int) DB::table('product_variants')->where('id', $variantId)->value('stock_quantity');
            if ($stock < 1) {
                continue;
            }

            $existing = CartItem::where('user_id', $userId)->where('variant_id', $variantId)->first();
            if ($existing) {
                $newQty = min($existing->quantity + $qty, $stock);
                $existing->update(['quantity' => $newQty]);
            } else {
                CartItem::create(['user_id' => $userId, 'variant_id' => $variantId, 'quantity' => min($qty, $stock)]);
            }
        }

        return response()->json(['ok' => true]);
    }

    public function count(): JsonResponse
    {
        if (! Auth::check()) {
            return response()->json(['count' => 0]);
        }

        $count = CartItem::where('user_id', Auth::id())->sum('quantity');

        return response()->json(['count' => $count]);
    }

    public function shipping(): View
    {
        return view('pages.store.cart-step-2', $this->checkoutMethods());
    }

    public function details(): View
    {
        return view('pages.store.cart-step-3', $this->checkoutMethods());
    }

    public function thanks(Order $order): View
    {
        return view('pages.store.cart-thanks', ['order' => $order]);
    }

    private function checkoutMethods(): array
    {
        $shippingMethods = ShippingMethod::active()
            ->get()
            ->map(fn (ShippingMethod $method) => [
                'id' => (int) $method->id,
                'label' => (string) $method->name,
                'desc' => $method->description,
                'price' => (float) $method->price,
                'type' => (string) $method->type,
            ])
            ->values()
            ->all();

        $paymentMethods = PaymentMethod::active()
            ->get()
            ->map(fn (PaymentMethod $method) => [
                'id' => (int) $method->id,
                'label' => (string) $method->name,
                'fee' => (float) $method->fee,
                'type' => (string) $method->type,
                'requires_address' => (bool) $method->requires_address,
            ])
            ->values()
            ->all();

        return [
            'shippingMethods' => $shippingMethods,
            'paymentMethods' => $paymentMethods,
        ];
    }

    public function place(PlaceOrderRequest $request, OrderService $orderService): JsonResponse
    {
        $dto = PlaceOrderData::fromValidated($request->validated(), Auth::id());
        $order = $orderService->place($dto);

        return response()->json(['order_id' => $order->id]);
    }
}
