<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreSubcategoryRequest;
use App\Models\Subcategory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Cache;

class SubcategoryController extends Controller
{
    public function store(StoreSubcategoryRequest $request): RedirectResponse
    {
        Subcategory::create($request->validated());
        $this->forgetCache();

        return redirect()->route('admin.settings')->with('success', 'Podkategória bola pridaná.');
    }

    public function update(StoreSubcategoryRequest $request, Subcategory $subcategory): RedirectResponse
    {
        $subcategory->update($request->validated());
        $this->forgetCache();

        return redirect()->route('admin.settings')->with('success', 'Podkategória bola upravená.');
    }

    public function destroy(Subcategory $subcategory): RedirectResponse
    {
        $subcategory->delete();
        $this->forgetCache();

        return redirect()->route('admin.settings')->with('success', 'Podkategória bola vymazaná.');
    }

    private function forgetCache(): void
    {
        Cache::forget('subnav:all');
    }
}
