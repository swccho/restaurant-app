<?php

namespace App\Filament\Admin\Widgets;

use App\Filament\Admin\Resources\MenuItemResource;
use App\Models\MenuItem;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class LowAvailabilityWidget extends BaseWidget
{
    protected static ?int $sort = 3;

    protected int|string|array $columnSpan = 1;

    protected function getStats(): array
    {
        $unavailableCount = MenuItem::query()
            ->where('is_available', false)
            ->count();

        if ($unavailableCount === 0) {
            return [
                Stat::make('Menu availability', 'All items available')
                    ->description('No action needed')
                    ->icon('heroicon-o-check-circle')
                    ->color('success'),
            ];
        }

        $menuItemsUrl = MenuItemResource::getUrl('index').'?tableFilters[is_available]=0';

        return [
            Stat::make('Unavailable items', (string) $unavailableCount)
                ->description('Currently off the menu')
                ->descriptionIcon('heroicon-m-exclamation-triangle')
                ->icon('heroicon-o-exclamation-triangle')
                ->color('warning')
                ->url($menuItemsUrl),
        ];
    }

    public static function canView(): bool
    {
        return true;
    }
}
