<?php

namespace App\Filament\Admin\Resources\MenuItemResource\Pages;

use App\Filament\Admin\Resources\MenuItemResource;
use Filament\Resources\Pages\CreateRecord;

class CreateMenuItem extends CreateRecord
{
    protected static string $resource = MenuItemResource::class;

    /** Path from form state when user uploaded an image (stripped before model create). */
    protected ?string $pendingImagePath = null;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $path = $data['image'] ?? null;
        if (is_array($path)) {
            $path = array_values($path)[0] ?? null;
        }
        $path = is_string($path) ? trim($path) : null;
        if ($path !== '' && $path !== null) {
            $this->pendingImagePath = $path;
        } else {
            $this->pendingImagePath = null;
        }
        unset($data['image']);

        return $data;
    }

    protected function afterCreate(): void
    {
        if ($this->pendingImagePath === null) {
            return;
        }
        $this->record->addMediaFromDisk($this->pendingImagePath, 'public')->toMediaCollection('menu_images', 'public');
        $this->pendingImagePath = null;
    }
}
