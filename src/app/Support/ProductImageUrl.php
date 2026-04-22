<?php

namespace App\Support;

class ProductImageUrl
{
    public static function resolve(?string $path): ?string
    {
        if (! $path) {
            return null;
        }

        if (preg_match('#^https?://#i', $path) === 1) {
            return $path;
        }

        $normalized = ltrim(str_replace('\\', '/', $path), '/');

        if ($normalized === '') {
            return null;
        }

        if (str_starts_with($normalized, 'public/')) {
            $normalized = substr($normalized, 7);
        }

        if (str_starts_with($normalized, 'app/public/')) {
            $normalized = substr($normalized, 11);
        }

        if (str_starts_with($normalized, 'images/') || str_starts_with($normalized, 'storage/')) {
            return asset($normalized);
        }

        return asset('storage/'.$normalized);
    }
}
