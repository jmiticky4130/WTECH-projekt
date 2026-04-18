<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreMaterialRequest;
use App\Models\Material;
use Illuminate\Http\RedirectResponse;

class MaterialController extends Controller
{
    public function store(StoreMaterialRequest $request): RedirectResponse
    {
        Material::create($request->validated());

        return redirect()->route('admin.settings')->with('success', 'Materiál bol pridaný.');
    }

    public function update(StoreMaterialRequest $request, Material $material): RedirectResponse
    {
        $material->update($request->validated());

        return redirect()->route('admin.settings')->with('success', 'Materiál bol upravený.');
    }

    public function destroy(Material $material): RedirectResponse
    {
        $material->delete();

        return redirect()->route('admin.settings')->with('success', 'Materiál bol vymazaný.');
    }
}
