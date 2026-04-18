<?php

namespace App\Http\Requests;

use App\Models\PaymentMethod;
use App\Models\ShippingMethod;
use Carbon\CarbonImmutable;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class PlaceOrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'email' => ['required', 'string', 'email', 'max:120'],
            'phone' => ['nullable', 'regex:/^[+\d\s\-()]{7,}$/'],

            'shipping_method_id' => ['required', 'integer', 'exists:shipping_methods,id'],
            'payment_method_id' => ['required', 'integer', 'exists:payment_methods,id'],

            'first_name' => ['nullable', 'string', 'max:80'],
            'last_name' => ['nullable', 'string', 'max:80'],
            'street' => ['nullable', 'string', 'max:120'],
            'city' => ['nullable', 'string', 'max:80'],
            'zip' => ['nullable', 'regex:/^\d{3}\s?\d{2}$/'],
            'country' => ['nullable', 'string', 'max:80'],
            'billing_same_as_delivery' => ['sometimes', 'boolean'],
            'billing_first_name' => ['nullable', 'string', 'max:80'],
            'billing_last_name' => ['nullable', 'string', 'max:80'],
            'billing_street' => ['nullable', 'string', 'max:120'],
            'billing_city' => ['nullable', 'string', 'max:80'],
            'billing_zip' => ['nullable', 'regex:/^\d{3}\s?\d{2}$/'],
            'billing_country' => ['nullable', 'string', 'max:80'],

            'pickup_first_name' => ['nullable', 'string', 'max:80'],
            'pickup_last_name' => ['nullable', 'string', 'max:80'],
            'pickup_point' => ['nullable', 'string', 'max:140'],

            'personal_first_name' => ['nullable', 'string', 'max:80'],
            'personal_last_name' => ['nullable', 'string', 'max:80'],

            'card_number' => ['nullable', 'string', 'max:23', 'regex:/^[\d\s]+$/'],
            'card_name' => ['nullable', 'string', 'max:120'],
            'card_expiry' => ['nullable', 'regex:/^(0[1-9]|1[0-2])\s?\/\s?\d{2}$/'],
            'card_cvv' => ['nullable', 'regex:/^\d{3,4}$/'],

            'items' => ['required', 'array', 'min:1'],
            'items.*.variant_id' => ['required', 'integer', 'exists:product_variants,id'],
            'items.*.quantity' => ['required', 'integer', 'min:1'],

            'subtotal' => ['nullable', 'numeric', 'min:0'],
            'total' => ['nullable', 'numeric', 'min:0'],
        ];
    }

    public function messages(): array
    {
        return [
            'email.required' => 'E-mail je povinný.',
            'email.email' => 'Zadajte platný e-mail.',
            'phone.regex' => 'Zadajte platné telefónne číslo.',

            'shipping_method_id.required' => 'Vyberte spôsob dopravy.',
            'shipping_method_id.exists' => 'Vybraný spôsob dopravy je neplatný.',
            'payment_method_id.required' => 'Vyberte spôsob platby.',
            'payment_method_id.exists' => 'Vybraný spôsob platby je neplatný.',

            'zip.regex' => 'PSČ musí byť vo formáte XXX XX.',
            'billing_zip.regex' => 'PSČ fakturačnej adresy musí byť vo formáte XXX XX.',

            'card_number.regex' => 'Číslo karty môže obsahovať iba čísla a medzery.',
            'card_expiry.regex' => 'Formát musí byť MM / RR.',
            'card_cvv.regex' => 'CVV musí mať 3 alebo 4 číslice.',

            'items.required' => 'Košík je prázdny.',
            'items.array' => 'Košík má neplatný formát.',
            'items.min' => 'Košík je prázdny.',
            'items.*.variant_id.required' => 'Položka v košíku nemá variant.',
            'items.*.variant_id.exists' => 'Niektorá položka už nie je dostupná.',
            'items.*.quantity.required' => 'Položka v košíku nemá množstvo.',
            'items.*.quantity.integer' => 'Množstvo položky musí byť celé číslo.',
            'items.*.quantity.min' => 'Množstvo položky musí byť aspoň 1.',
        ];
    }

    protected function prepareForValidation(): void
    {
        $legacyFieldMap = [
            'firstName' => 'first_name',
            'lastName' => 'last_name',
            'pickupFirstName' => 'pickup_first_name',
            'pickupLastName' => 'pickup_last_name',
            'pickupPoint' => 'pickup_point',
            'personalFirstName' => 'personal_first_name',
            'personalLastName' => 'personal_last_name',
            'billingSame' => 'billing_same_as_delivery',
            'billingFirstName' => 'billing_first_name',
            'billingLastName' => 'billing_last_name',
            'billingStreet' => 'billing_street',
            'billingCity' => 'billing_city',
            'billingZip' => 'billing_zip',
            'billingCountry' => 'billing_country',
            'cardNumber' => 'card_number',
            'cardName' => 'card_name',
            'cardExpiry' => 'card_expiry',
            'cardCvv' => 'card_cvv',
        ];

        $stringFields = [
            'email', 'phone',
            'first_name', 'last_name', 'street', 'city', 'zip', 'country',
            'billing_first_name', 'billing_last_name', 'billing_street',
            'billing_city', 'billing_zip', 'billing_country',
            'pickup_first_name', 'pickup_last_name', 'pickup_point',
            'personal_first_name', 'personal_last_name',
            'card_number', 'card_name', 'card_expiry', 'card_cvv',
        ];

        $normalized = [];

        foreach ($legacyFieldMap as $legacy => $normalizedField) {
            if (! $this->has($normalizedField) && $this->has($legacy)) {
                $normalized[$normalizedField] = $this->input($legacy);
            }
        }

        if (! $this->has('billing_same_as_delivery')) {
            $normalized['billing_same_as_delivery'] = true;
        }

        foreach ($stringFields as $field) {
            if (! $this->has($field)) {
                continue;
            }

            $value = $this->input($field);
            if (! is_string($value)) {
                continue;
            }

            $trimmed = trim($value);
            $normalized[$field] = $trimmed === '' ? null : $trimmed;
        }

        $items = $this->input('items');
        if (is_array($items)) {
            $normalizedItems = [];

            foreach ($items as $index => $item) {
                if (! is_array($item)) {
                    continue;
                }

                $normalizedItem = $item;

                if (! array_key_exists('quantity', $normalizedItem) && array_key_exists('qty', $normalizedItem)) {
                    $normalizedItem['quantity'] = $normalizedItem['qty'];
                }

                unset($normalizedItem['qty']);
                $normalizedItems[$index] = $normalizedItem;
            }

            $normalized['items'] = $normalizedItems;
        }

        if ($normalized !== []) {
            $this->merge($normalized);
        }
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator): void {
            $shippingMethod = ShippingMethod::query()->find($this->input('shipping_method_id'));
            $paymentMethod = PaymentMethod::query()->find($this->input('payment_method_id'));

            if (! $shippingMethod || ! $paymentMethod) {
                return;
            }

            $shippingType = (string) $shippingMethod->type;
            $paymentType = (string) $paymentMethod->type;
            $billingSame = $this->boolean('billing_same_as_delivery', true);

            if ($shippingType === 'address') {
                $this->requireFilled($validator, 'first_name', 'Meno je povinné.');
                $this->requireFilled($validator, 'last_name', 'Priezvisko je povinné.');
                $this->requireFilled($validator, 'street', 'Ulica je povinná.');
                $this->requireFilled($validator, 'city', 'Mesto je povinné.');
                $this->requireFilled($validator, 'zip', 'PSČ je povinné.');
                $this->requireFilled($validator, 'country', 'Krajina je povinná.');

                if (! $billingSame) {
                    $this->requireFilled($validator, 'billing_first_name', 'Meno vo fakturačnej adrese je povinné.');
                    $this->requireFilled($validator, 'billing_last_name', 'Priezvisko vo fakturačnej adrese je povinné.');
                    $this->requireFilled($validator, 'billing_street', 'Ulica vo fakturačnej adrese je povinná.');
                    $this->requireFilled($validator, 'billing_city', 'Mesto vo fakturačnej adrese je povinné.');
                    $this->requireFilled($validator, 'billing_zip', 'PSČ vo fakturačnej adrese je povinné.');
                    $this->requireFilled($validator, 'billing_country', 'Krajina vo fakturačnej adrese je povinná.');
                }
            }

            if ($shippingType === 'pickup_point') {
                $this->requireFilled($validator, 'pickup_first_name', 'Meno je povinné.');
                $this->requireFilled($validator, 'pickup_last_name', 'Priezvisko je povinné.');
                $this->requireFilled($validator, 'pickup_point', 'Vyberte výdajné miesto.');
            }

            if ($shippingType === 'personal_pickup') {
                $this->requireFilled($validator, 'personal_first_name', 'Meno je povinné.');
                $this->requireFilled($validator, 'personal_last_name', 'Priezvisko je povinné.');
            }

            if ($paymentType === 'card') {
                $this->requireFilled($validator, 'card_number', 'Číslo karty je povinné.');
                $this->requireFilled($validator, 'card_name', 'Meno držiteľa karty je povinné.');
                $this->requireFilled($validator, 'card_expiry', 'Platnosť karty je povinná.');
                $this->requireFilled($validator, 'card_cvv', 'CVV je povinné.');

                $this->validateCardNumber($validator);
                $this->validateCardExpiry($validator);
            }

            if ($paymentMethod->requires_address && $shippingType !== 'address') {
                $validator->errors()->add('payment_method_id', 'Dobierka je dostupná iba pri doručení na adresu.');
            }
        });
    }

    private function requireFilled(Validator $validator, string $field, string $message): void
    {
        $value = $this->input($field);

        if (! is_string($value) || trim($value) === '') {
            $validator->errors()->add($field, $message);
        }
    }

    private function validateCardNumber(Validator $validator): void
    {
        if ($validator->errors()->has('card_number')) {
            return;
        }

        $digits = preg_replace('/\D+/', '', (string) $this->input('card_number'));

        if (! is_string($digits) || ! preg_match('/^\d{13,19}$/', $digits)) {
            $validator->errors()->add('card_number', 'Číslo karty musí mať 13-19 číslic.');
        }
    }

    private function validateCardExpiry(Validator $validator): void
    {
        if ($validator->errors()->has('card_expiry')) {
            return;
        }

        $value = preg_replace('/\s+/', '', (string) $this->input('card_expiry'));

        if (! is_string($value) || ! preg_match('/^(0[1-9]|1[0-2])\/(\d{2})$/', $value, $matches)) {
            $validator->errors()->add('card_expiry', 'Formát musí byť MM / RR.');

            return;
        }

        $expiryMonth = (int) $matches[1];
        $expiryYear = 2000 + (int) $matches[2];

        $today = CarbonImmutable::today();
        $currentYear = (int) $today->format('Y');
        $currentMonth = (int) $today->format('n');

        if ($expiryYear < $currentYear || ($expiryYear === $currentYear && $expiryMonth < $currentMonth)) {
            $validator->errors()->add('card_expiry', 'Platnosť karty vypršala.');
        }
    }
}
