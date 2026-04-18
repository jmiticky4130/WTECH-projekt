<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Color;
use App\Models\Material;
use App\Models\PaymentMethod;
use App\Models\ShippingMethod;
use App\Models\Subcategory;
use Illuminate\View\View;

class SettingsController extends Controller
{
    public function index(): View
    {
        return view('pages.admin.settings', [
            'categories' => Category::orderBy('name')->get(),
            'subcategories' => Subcategory::orderBy('name')->get(),
            'brands' => Brand::orderBy('name')->get(),
            'colors' => Color::orderBy('name')->get(),
            'materials' => Material::orderBy('name')->get(),
            'paymentMethods' => PaymentMethod::orderBy('sort_order')->get(),
            'shippingMethods' => ShippingMethod::orderBy('sort_order')->get(),
        ]);
    }
}
