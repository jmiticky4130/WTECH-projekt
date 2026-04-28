<?php

namespace App\Services;

use App\Models\Brand;
use App\Models\Color;
use App\Models\Material;
use App\Models\ProductVariant;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class FilterDataService
{
    public function getBrands(): Collection
    {
        return Brand::withTrashed()->orderBy('name')->get();
    }

    public function getColors(): Collection
    {
        return Color::withTrashed()->orderBy('name')->get();
    }

    public function getMaterials(): Collection
    {
        return Material::withTrashed()->orderBy('name')->get();
    }

    public function getGlobalMinPrice(): int
    {
        return Cache::remember('filter:min_price', 3600, fn () => (int) floor(ProductVariant::min('price') ?? 0));
    }

    public function getGlobalMaxPrice(): int
    {
        return Cache::remember('filter:max_price', 3600, fn () => (int) ceil(ProductVariant::max('price') ?? 1000));
    }
}
