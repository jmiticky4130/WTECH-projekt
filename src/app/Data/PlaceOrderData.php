<?php

namespace App\Data;

class PlaceOrderData
{
    public function __construct(
        public readonly ?int $userId,
        public readonly string $email,
        public readonly ?string $phone,
        public readonly int $shippingMethodId,
        public readonly int $paymentMethodId,
        public readonly ?string $firstName,
        public readonly ?string $lastName,
        public readonly ?string $street,
        public readonly ?string $city,
        public readonly ?string $zip,
        public readonly ?string $country,
        public readonly ?string $pickupFirstName,
        public readonly ?string $pickupLastName,
        public readonly ?string $pickupPoint,
        public readonly ?string $personalFirstName,
        public readonly ?string $personalLastName,
        public readonly bool $billingSameAsDelivery,
        public readonly ?string $billingFirstName,
        public readonly ?string $billingLastName,
        public readonly ?string $billingStreet,
        public readonly ?string $billingCity,
        public readonly ?string $billingZip,
        public readonly ?string $billingCountry,
        public readonly ?string $cardNumber,
        public readonly ?string $cardName,
        public readonly ?string $cardExpiry,
        public readonly ?string $cardCvv,
        public readonly array $items,
    ) {}

    public static function fromValidated(array $validated, ?int $userId): self
    {
        $items = collect($validated['items'] ?? [])
            ->map(fn (array $item): array => [
                'variant_id' => (int) ($item['variant_id'] ?? 0),
                'quantity' => (int) ($item['quantity'] ?? 0),
            ])
            ->all();

        return new self(
            userId: $userId,
            email: (string) $validated['email'],
            phone: $validated['phone'] ?? null,
            shippingMethodId: (int) $validated['shipping_method_id'],
            paymentMethodId: (int) $validated['payment_method_id'],
            firstName: $validated['first_name'] ?? null,
            lastName: $validated['last_name'] ?? null,
            street: $validated['street'] ?? null,
            city: $validated['city'] ?? null,
            zip: $validated['zip'] ?? null,
            country: $validated['country'] ?? null,
            pickupFirstName: $validated['pickup_first_name'] ?? null,
            pickupLastName: $validated['pickup_last_name'] ?? null,
            pickupPoint: $validated['pickup_point'] ?? null,
            personalFirstName: $validated['personal_first_name'] ?? null,
            personalLastName: $validated['personal_last_name'] ?? null,
            billingSameAsDelivery: (bool) ($validated['billing_same_as_delivery'] ?? true),
            billingFirstName: $validated['billing_first_name'] ?? null,
            billingLastName: $validated['billing_last_name'] ?? null,
            billingStreet: $validated['billing_street'] ?? null,
            billingCity: $validated['billing_city'] ?? null,
            billingZip: $validated['billing_zip'] ?? null,
            billingCountry: $validated['billing_country'] ?? null,
            cardNumber: $validated['card_number'] ?? null,
            cardName: $validated['card_name'] ?? null,
            cardExpiry: $validated['card_expiry'] ?? null,
            cardCvv: $validated['card_cvv'] ?? null,
            items: $items,
        );
    }
}
