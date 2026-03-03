<?php

namespace App\Filament\Admin\Resources\MenuItemResource\Pages;

use App\Filament\Admin\Resources\MenuItemResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Storage;

class EditMenuItem extends EditRecord
{
    protected static string $resource = MenuItemResource::class;

    /** Path from form state when user uploaded a new image (stripped before model update). */
    protected ?string $pendingImagePath = null;

    /** Filament stores new uploads under this directory on the public disk. */
    private const UPLOAD_DIRECTORY = 'menu-items';

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    public function mutateFormDataBeforeFill(array $data): array
    {
        $media = $this->record->getFirstMedia('menu_images');
        if ($media) {
            $path = $media->getPathRelativeToRoot();
            $data['image'] = [\Illuminate\Support\Str::uuid()->toString() => $path !== '' ? $path : '__existing_media__'];
        }

        return $data;
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $path = $data['image'] ?? null;
        if (is_array($path)) {
            $path = array_values($path)[0] ?? null;
        }
        $path = is_string($path) ? trim($path) : null;
        if ($path !== '' && $path !== null && $path !== '__existing_media__') {
            $this->pendingImagePath = $path;
        } else {
            $this->pendingImagePath = null;
        }
        unset($data['image']);

        return $data;
    }

    protected function afterSave(): void
    {
        if ($this->pendingImagePath === null) {
            return;
        }

        $path = $this->pendingImagePath;
        $this->pendingImagePath = null;

        // Only replace media when this is a new upload from Filament (under our directory).
        // If the path looks like existing Spatie media (e.g. "123/filename.jpg"), the user
        // did not change the image — do not clear or re-add (we would delete the file then fail).
        $isNewUpload = str_starts_with($path, self::UPLOAD_DIRECTORY.'/');

        if (! $isNewUpload) {
            return;
        }

        $disk = Storage::disk('public');
        if (! $disk->exists($path)) {
            return;
        }

        $this->record->clearMediaCollection('menu_images');
        $this->record->addMediaFromDisk($path, 'public')->toMediaCollection('menu_images', 'public');
    }
}
