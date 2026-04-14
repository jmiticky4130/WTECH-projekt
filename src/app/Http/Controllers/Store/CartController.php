<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use App\Models\CartItem;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CartController extends Controller
{
    private function itemQuery(): \Illuminate\Database\Query\Builder
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
                'cart_items.quantity as qty',
                'pi.image_path',
            ]);
    }

    public function data(Request $request): JsonResponse
    {
        if (auth()->check()) {
            $items = $this->itemQuery()
                ->where('cart_items.user_id', auth()->id())
                ->get();

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
                'pi.image_path',
            ])
            ->whereIn('pv.id', $variantIds)
            ->get()
            ->map(fn ($v) => array_merge((array) $v, [
                'item_id' => null,
                'qty' => $qtyMap[$v->variant_id] ?? 1,
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
                'pv.size', 'pv.price', 'pi.image_path'])
            ->where('pv.id', $variantId)
            ->first();

        $itemId = null;
        $cartCount = null;

        if (auth()->check()) {
            $userId = auth()->id();
            $item = CartItem::where('user_id', $userId)->where('variant_id', $variantId)->first();
            if ($item) {
                $item->increment('quantity', $qty);
                $item->refresh();
            } else {
                $item = CartItem::create(['user_id' => $userId, 'variant_id' => $variantId, 'quantity' => $qty]);
            }
            $itemId = $item->id;
            $cartCount = CartItem::where('user_id', $userId)->sum('quantity');
        }

        return response()->json([
            ...(array) $variant,
            'item_id' => $itemId,
            'qty' => $qty,
            'cart_count' => $cartCount,
        ]);
    }

    public function update(Request $request, CartItem $cartItem): JsonResponse
    {
        abort_unless(auth()->check() && $cartItem->user_id === auth()->id(), 403);

        $request->validate(['qty' => ['required', 'integer', 'min:1']]);
        $cartItem->update(['quantity' => $request->integer('qty')]);

        return response()->json(['ok' => true]);
    }

    public function remove(CartItem $cartItem): JsonResponse
    {
        abort_unless(auth()->check() && $cartItem->user_id === auth()->id(), 403);
        $cartItem->delete();

        return response()->json(['ok' => true]);
    }

    public function count(): JsonResponse
    {
        if (! auth()->check()) {
            return response()->json(['count' => 0]);
        }

        $count = CartItem::where('user_id', auth()->id())->sum('quantity');

        return response()->json(['count' => $count]);
    }
}
