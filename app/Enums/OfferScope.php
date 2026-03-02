<?php

namespace App\Enums;

enum OfferScope: string
{
    case All = 'all';
    case Category = 'category';
    case Items = 'items';

    public function label(): string
    {
        return match ($this) {
            self::All => 'All menu',
            self::Category => 'Category',
            self::Items => 'Specific items',
        };
    }

    /** Filament badge color key. */
    public function color(): string
    {
        return match ($this) {
            self::All => 'gray',
            self::Category => 'primary',
            self::Items => 'warning',
        };
    }
}
