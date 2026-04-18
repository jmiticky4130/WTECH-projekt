<?php

namespace App\Services;

use App\Data\PlaceOrderData;
use App\Models\CartItem;
use App\Models\Order;
use App\Models\PaymentMethod;
use App\Models\ProductVariant;
use App\Models\ShippingMethod;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class OrderService
{
    public function place(PlaceOrderData $dto): Order
    {
        return DB::transaction(function () use ($dto): Order {
            $shippingMethod = ShippingMethod::query()
                ->where('id', $dto->shippingMethodId)
                ->whereRaw('is_active is true')
                ->first();

            if (! $shippingMethod) {
                throw ValidationException::withMessages([
                    'shipping_method_id' => 'Vybraný spôsob dopravy už nie je dostupný.',
                ]);
            }

            $paymentMethod = PaymentMethod::query()
                ->where('id', $dto->paymentMethodId)
                ->whereRaw('is_active is true')
                ->first();

            if (! $paymentMethod) {
                throw ValidationException::withMessages([
                    'payment_method_id' => 'Vybraný spôsob platby už nie je dostupný.',
                ]);
            }

            if ($paymentMethod->requires_address && $shippingMethod->type !== 'address') {
                throw ValidationException::withMessages([
                    'payment_method_id' => 'Dobierka je dostupná iba pri doručení na adresu.',
                ]);
            }

            $itemsByVariant = $this->resolveCheckoutItems($dto);
            $variantIds = array_keys($itemsByVariant);

            $variants = ProductVariant::query()
                ->with(['product:id,name', 'color:id,name'])
                ->whereIn('id', $variantIds)
                ->lockForUpdate()
                ->get()
                ->keyBy('id');

            if ($variants->count() !== count($itemsByVariant)) {
                throw ValidationException::withMessages([
                    'items' => 'Niektoré položky v košíku už nie sú dostupné.',
                ]);
            }

            $subtotal = 0.0;
            $orderItems = [];

            foreach ($itemsByVariant as $variantId => $quantity) {
                $variant = $variants->get($variantId);

                if (! $variant) {
                    throw ValidationException::withMessages([
                        'items' => 'Niektoré položky v košíku už nie sú dostupné.',
                    ]);
                }

                if ((int) $variant->stock_quantity < $quantity) {
                    throw ValidationException::withMessages([
                        'items' => 'Niektoré položky nemajú dostatočné skladové množstvo.',
                    ]);
                }

                $unitPrice = round((float) $variant->price, 2);
                $lineTotal = round($unitPrice * $quantity, 2);
                $subtotal += $lineTotal;

                $orderItems[] = [
                    'variant_id' => (int) $variantId,
                    'product_name' => (string) ($variant->product?->name ?? ''),
                    'color_name' => (string) ($variant->color?->name ?? ''),
                    'size' => (string) $variant->size,
                    'unit_price' => $unitPrice,
                    'line_total' => $lineTotal,
                    'quantity' => (int) $quantity,
                ];
            }

            $shippingType = (string) $shippingMethod->type;
            [$firstName, $lastName] = $this->resolveOrderNamePair($dto, $shippingType);

            $isAddress = $shippingType === 'address';
            $isPickupPoint = $shippingType === 'pickup_point';
            $billingSameAsDelivery = $isAddress ? $dto->billingSameAsDelivery : true;
            $billingSameAsDeliveryValue = DB::getDriverName() === 'pgsql'
                ? DB::raw($billingSameAsDelivery ? 'true' : 'false')
                : $billingSameAsDelivery;

            [$billingFirstName, $billingLastName, $billingStreet, $billingCity, $billingZip, $billingCountry] =
                $this->resolveBillingData($dto, $isAddress, $billingSameAsDelivery);

            $shippingCost = round((float) $shippingMethod->price, 2);
            $paymentFee = round((float) $paymentMethod->fee, 2);
            $total = round($subtotal + $shippingCost + $paymentFee, 2);

            $order = Order::query()->create([
                'user_id' => $dto->userId,
                'status' => 'pending',
                'email' => $dto->email,
                'phone' => $dto->phone,
                'shipping_method_id' => (int) $shippingMethod->id,
                'payment_method_id' => (int) $paymentMethod->id,
                'shipping_cost' => $shippingCost,
                'payment_fee' => $paymentFee,
                'subtotal' => round($subtotal, 2),
                'total' => $total,
                'first_name' => $firstName,
                'last_name' => $lastName,
                'street' => $isAddress ? $dto->street : null,
                'city' => $isAddress ? $dto->city : null,
                'zip' => $isAddress ? $dto->zip : null,
                'country' => $isAddress ? $dto->country : null,
                'pickup_point' => $isPickupPoint ? $dto->pickupPoint : null,
                'billing_first_name' => $billingFirstName,
                'billing_last_name' => $billingLastName,
                'billing_street' => $billingStreet,
                'billing_city' => $billingCity,
                'billing_zip' => $billingZip,
                'billing_country' => $billingCountry,
                'billing_same_as_delivery' => $billingSameAsDeliveryValue,
            ]);

            $order->items()->createMany($orderItems);

            foreach ($itemsByVariant as $variantId => $quantity) {
                ProductVariant::query()
                    ->whereKey($variantId)
                    ->decrement('stock_quantity', $quantity);
            }

            if ($dto->userId) {
                CartItem::query()->where('user_id', $dto->userId)->delete();
            }

            return $order;
        });
    }

    private function resolveCheckoutItems(PlaceOrderData $dto): array
    {
        if ($dto->userId) {
            $rawItems = CartItem::query()
                ->where('user_id', $dto->userId)
                ->select(['variant_id', 'quantity'])
                ->get()
                ->map(fn (CartItem $item): array => [
                    'variant_id' => (int) $item->variant_id,
                    'quantity' => (int) $item->quantity,
                ])
                ->all();
        } else {
            $rawItems = $dto->items;
        }

        $itemsByVariant = [];

        foreach ($rawItems as $item) {
            $variantId = (int) ($item['variant_id'] ?? 0);
            $quantity = (int) ($item['quantity'] ?? 0);

            if ($variantId < 1 || $quantity < 1) {
                continue;
            }

            $itemsByVariant[$variantId] = ($itemsByVariant[$variantId] ?? 0) + $quantity;
        }

        if ($itemsByVariant === []) {
            throw ValidationException::withMessages([
                'items' => 'Košík je prázdny.',
            ]);
        }

        return $itemsByVariant;
    }

    private function resolveOrderNamePair(PlaceOrderData $dto, string $shippingType): array
    {
        if ($shippingType === 'pickup_point') {
            return [
                trim((string) ($dto->pickupFirstName ?? '')),
                trim((string) ($dto->pickupLastName ?? '')),
            ];
        }

        if ($shippingType === 'personal_pickup') {
            return [
                trim((string) ($dto->personalFirstName ?? '')),
                trim((string) ($dto->personalLastName ?? '')),
            ];
        }

        return [
            trim((string) ($dto->firstName ?? '')),
            trim((string) ($dto->lastName ?? '')),
        ];
    }

    private function resolveBillingData(PlaceOrderData $dto, bool $isAddress, bool $billingSameAsDelivery): array
    {
        if (! $isAddress) {
            return [null, null, null, null, null, null];
        }

        if ($billingSameAsDelivery) {
            return [
                $dto->firstName,
                $dto->lastName,
                $dto->street,
                $dto->city,
                $dto->zip,
                $dto->country,
            ];
        }

        return [
            $dto->billingFirstName,
            $dto->billingLastName,
            $dto->billingStreet,
            $dto->billingCity,
            $dto->billingZip,
            $dto->billingCountry,
        ];
    }
}
