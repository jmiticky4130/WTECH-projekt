<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreColorRequest;
use App\Models\Color;
use Illuminate\Http\RedirectResponse;

class ColorController extends Controller
{
    public function store(StoreColorRequest $request): RedirectResponse
    {
        Color::create($request->validated());

        return redirect()->route('admin.settings')->with('success', 'Farba bola pridaná.');
    }

    public function update(StoreColorRequest $request, Color $color): RedirectResponse
    {
        $color->update($request->validated());

        return redirect()->route('admin.settings')->with('success', 'Farba bola upravená.');
    }

    public function destroy(Color $color): RedirectResponse
    {
        $color->delete();

        return redirect()->route('admin.settings')->with('success', 'Farba bola vymazaná.');
    }
}
