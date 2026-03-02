<?php

namespace App\Filament\Admin\Resources\OfferResource\Pages;

use App\Enums\OfferScope;
use App\Filament\Admin\Resources\OfferResource;
use Filament\Resources\Pages\CreateRecord;

class CreateOffer extends CreateRecord
{
    protected static string $resource = OfferResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        if (($data['scope'] ?? '') !== OfferScope::Category->value) {
            $data['category_id'] = null;
        }

        return $data;
    }
}
