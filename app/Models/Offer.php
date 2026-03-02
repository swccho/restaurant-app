<?php

namespace App\Models;

use App\Enums\OfferScope;
use App\Enums\OfferType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Offer extends Model
{
    protected $fillable = [
        'title',
        'description',
        'type',
        'value',
        'scope',
        'category_id',
        'is_active',
        'start_at',
        'end_at',
    ];

    protected $casts = [
        'type' => OfferType::class,
        'scope' => OfferScope::class,
        'value' => 'decimal:2',
        'is_active' => 'boolean',
        'start_at' => 'datetime',
        'end_at' => 'datetime',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }
}
