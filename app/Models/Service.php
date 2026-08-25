<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    protected $fillable = [
        'name', 'slug', 'icon', 'cover_image', 'short_description',
        'full_description', 'features', 'sort_order', 'is_active',
    ];

    protected $casts = [
        'features' => 'array',
        'is_active' => 'boolean',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true)->orderBy('sort_order');
    }

    public function getCoverImageUrlAttribute(): string
    {
        if ($this->cover_image) {
            if (str_starts_with($this->cover_image, 'http://') || str_starts_with($this->cover_image, 'https://')) {
                return $this->cover_image;
            }
            $path = ltrim($this->cover_image, '/');
            if (str_starts_with($path, 'storage/')) {
                $path = substr($path, 8);
            }
            return asset('storage/' . $path);
        }

        $slug = strtolower($this->slug ?? '');
        $name = strtolower($this->name ?? '');

        if (str_contains($slug, 'garage') || str_contains($name, 'garage')) {
            return asset('storage/portfolio/cat-garage-conversion.jpg');
        }
        if (str_contains($slug, 'loft') || str_contains($name, 'loft')) {
            return asset('storage/portfolio/cat-loft-conversion.jpg');
        }
        if (str_contains($slug, 'extension') || str_contains($name, 'extension')) {
            return asset('storage/portfolio/cat-extension.jpg');
        }
        if (str_contains($slug, 'new-build') || str_contains($name, 'new build')) {
            return asset('storage/portfolio/cat-new-build.jpg');
        }
        if (str_contains($slug, 'outbuilding') || str_contains($name, 'outbuilding')) {
            return asset('storage/portfolio/cat-outbuilding.jpg');
        }
        if (str_contains($slug, 'internal') || str_contains($name, 'internal') || str_contains($name, 'alteration') || str_contains($name, 'change')) {
            return asset('storage/portfolio/cat-internal-changes.jpg');
        }
        if (str_contains($slug, 'cgi') || str_contains($slug, '3d') || str_contains($name, 'cgi') || str_contains($name, 'render')) {
            return 'https://images.unsplash.com/photo-1600585154340-be6161a56a0c?auto=format&fit=crop&w=800&q=80';
        }
        if (str_contains($slug, 'hmo') || str_contains($name, 'hmo')) {
            return asset('storage/portfolio/cat-extension.jpg');
        }

        return 'https://images.unsplash.com/photo-1600585154340-be6161a56a0c?auto=format&fit=crop&w=800&q=80';
    }
}
