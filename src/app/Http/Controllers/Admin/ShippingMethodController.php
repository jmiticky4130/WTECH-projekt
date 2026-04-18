<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreShippingMethodRequest;
use App\Models\ShippingMethod;
use Illuminate\Http\RedirectResponse;

class ShippingMethodController extends Controller
{
    public function store(StoreShippingMethodRequest $request): RedirectResponse
    {
        ShippingMethod::create($request->validated());

        return redirect()->route('admin.settings')->with('success', 'Spôsob dopravy bol pridaný.');
    }

    public function update(StoreShippingMethodRequest $request, ShippingMethod $shippingMethod): RedirectResponse
    {
        $shippingMethod->update($request->validated());

        return redirect()->route('admin.settings')->with('success', 'Spôsob dopravy bol upravený.');
    }

    public function destroy(ShippingMethod $shippingMethod): RedirectResponse
    {
        $shippingMethod->delete();

        return redirect()->route('admin.settings')->with('success', 'Spôsob dopravy bol vymazaný.');
    }
}
