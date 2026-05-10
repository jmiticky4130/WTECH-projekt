<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreSubcategoryRequest;
use App\Models\Subcategory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

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

    public function updateLandingPage(Request $request): RedirectResponse
    {
        $request->validate([
            'show_on_landing'   => ['nullable', 'array'],
            'show_on_landing.*' => ['integer', 'exists:subcategories,id'],
            'images.*'          => ['nullable', 'image', 'max:4096'],
        ]);

        $selectedIds = array_map('intval', $request->input('show_on_landing', []));

        if (count($selectedIds) > 4) {
            return redirect()->route('admin.settings')
                ->with('error', 'Môžete vybrať najviac 4 podkategórie na úvodnej stránke.');
        }

        foreach (Subcategory::all() as $sub) {
            $data = ['show_on_landing' => in_array($sub->id, $selectedIds)];

            $file = $request->file('images.' . $sub->id);
            if ($file) {
                if ($sub->landing_image) {
                    Storage::disk('public')->delete($sub->landing_image);
                }
                $data['landing_image'] = $file->storeAs(
                    'subcategory-images',
                    $sub->slug . '.' . $file->extension(),
                    'public'
                );
            }

            $sub->update($data);
        }

        $this->forgetCache();

        return redirect()->route('admin.settings')->with('success', 'Nastavenia úvodnej stránky boli uložené.');
    }

    private function forgetCache(): void
    {
        Cache::forget('subnav:all');
    }
}
