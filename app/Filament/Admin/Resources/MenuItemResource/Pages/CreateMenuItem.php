<?php

namespace App\Filament\Admin\Resources\MenuItemResource\Pages;

use App\Filament\Admin\Resources\MenuItemResource;
use Filament\Resources\Pages\CreateRecord;

class CreateMenuItem extends CreateRecord
{
    protected static string $resource = MenuItemResource::class;

    protected function afterCreate(): void
    {
        $data = $this->form->getState();
        $path = $data['image'] ?? null;
        if (filled($path)) {
            $path = is_array($path) ? ($path[0] ?? null) : $path;
            if ($path) {
                $this->record->clearMediaCollection('menu_images');
                $this->record->addMediaFromDisk($path, 'public')->toMediaCollection('menu_images');
            }
        }
    }
}
