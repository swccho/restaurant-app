<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Category extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    protected static function booted(): void
    {
        static::creating(function (Category $category): void {
            if (blank($category->slug)) {
                $category->slug = static::uniqueSlug($category->name ?? '');
            } else {
                $category->slug = Str::lower(Str::slug($category->slug, '-'));
                if (static::query()->where('slug', $category->slug)->exists()) {
                    $category->slug = static::uniqueSlug($category->slug);
                }
            }
        });

        static::updating(function (Category $category): void {
            if ($category->isDirty('slug')) {
                $category->slug = filled($category->slug)
                    ? Str::lower(Str::slug($category->slug, '-'))
                    : static::uniqueSlug($category->name ?? '', $category->id);
                if (static::query()->where('slug', $category->slug)->where('id', '!=', $category->id)->exists()) {
                    $category->slug = static::uniqueSlug($category->slug, $category->id);
                }
            }
            if ($category->isDirty('name') && blank($category->slug)) {
                $category->slug = static::uniqueSlug($category->name, $category->id);
            }
        });
    }

    /**
     * Generate a unique slug (append number if duplicate).
     */
    public static function uniqueSlug(string $base, ?int $ignoreId = null): string
    {
        $slug = Str::lower(Str::slug($base, '-'));
        $query = static::query()->where('slug', $slug);
        if ($ignoreId !== null) {
            $query->where('id', '!=', $ignoreId);
        }
        if ($query->exists()) {
            $suffix = 1;
            while (static::query()->where('slug', $slug.'-'.$suffix)->when($ignoreId !== null, fn ($q) => $q->where('id', '!=', $ignoreId))->exists()) {
                $suffix++;
            }

            return $slug.'-'.$suffix;
        }

        return $slug;
    }
}
