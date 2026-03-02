<?php

namespace App\Filament\Admin\Resources\OfferResource\Pages;

use App\Enums\OfferScope;
use App\Filament\Admin\Resources\OfferResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditOffer extends EditRecord
{
    protected static string $resource = OfferResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        if (($data['scope'] ?? '') !== OfferScope::Category->value) {
            $data['category_id'] = null;
        }

        return $data;
    }
}
