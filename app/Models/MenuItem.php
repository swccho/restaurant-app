<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class MenuItem extends Model implements HasMedia
{
    use InteractsWithMedia, SoftDeletes;

    protected $fillable = [
        'category_id',
        'name',
        'slug',
        'description',
        'price',
        'is_available',
        'is_featured',
        'sort_order',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'is_available' => 'boolean',
        'is_featured' => 'boolean',
        'sort_order' => 'integer',
    ];

    protected static function booted(): void
    {
        static::creating(function (MenuItem $item): void {
            if (blank($item->slug)) {
                $item->slug = static::uniqueSlug($item->name ?? '');
            } else {
                $item->slug = Str::lower(Str::slug($item->slug, '-'));
                if (static::query()->where('slug', $item->slug)->exists()) {
                    $item->slug = static::uniqueSlug($item->slug);
                }
            }
        });

        static::updating(function (MenuItem $item): void {
            if ($item->isDirty('slug')) {
                $item->slug = filled($item->slug)
                    ? Str::lower(Str::slug($item->slug, '-'))
                    : static::uniqueSlug($item->name ?? '', $item->id);
                if (static::query()->where('slug', $item->slug)->where('id', '!=', $item->id)->exists()) {
                    $item->slug = static::uniqueSlug($item->slug, $item->id);
                }
            }
            if ($item->isDirty('name') && blank($item->slug)) {
                $item->slug = static::uniqueSlug($item->name, $item->id);
            }
        });
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('menu_images')
            ->singleFile()
            ->acceptsFile(fn ($file) => str_starts_with($file->mimeType ?? '', 'image/'));
    }

    public function registerMediaConversions(?\Spatie\MediaLibrary\MediaCollections\Models\Media $media = null): void
    {
        $this->addMediaConversion('thumb')
            ->width(80)
            ->height(80)
            ->sharpen(10);
    }

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
