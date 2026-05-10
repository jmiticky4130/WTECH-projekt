<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreShippingMethodRequest;
use App\Http\Requests\Admin\UpdateShippingMethodRequest;
use App\Models\ShippingMethod;
use Illuminate\Http\RedirectResponse;

class ShippingMethodController extends Controller
{
    public function store(StoreShippingMethodRequest $request): RedirectResponse
    {
        $shippingMethod = ShippingMethod::create($request->validated());
        if ($request->has('payment_methods')) {
            $shippingMethod->paymentMethods()->sync($request->validated('payment_methods'));
        }

        return redirect()->route('admin.settings')->with('success', 'Spôsob dopravy bol pridaný.');
    }

    public function update(UpdateShippingMethodRequest $request, ShippingMethod $shippingMethod): RedirectResponse
    {
        $shippingMethod->update($request->validated());
        $shippingMethod->paymentMethods()->sync($request->validated('payment_methods') ?? []);

        return redirect()->route('admin.settings')->with('success', 'Spôsob dopravy bol upravený.');
    }

    public function destroy(ShippingMethod $shippingMethod): RedirectResponse
    {
        $shippingMethod->delete();

        return redirect()->route('admin.settings')->with('success', 'Spôsob dopravy bol vymazaný.');
    }
}
