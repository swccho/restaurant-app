<?php

namespace App\Filament\Admin\Widgets;

use App\Enums\OrderStatus;
use App\Models\Order;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Carbon;

class RestaurantStatsOverviewWidget extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $today = Carbon::today();

        $newOrders = Order::query()
            ->where('status', OrderStatus::Received)
            ->count();

        $preparingOrders = Order::query()
            ->where('status', OrderStatus::Preparing)
            ->count();

        $outForDelivery = Order::query()
            ->where('status', OrderStatus::OutForDelivery)
            ->count();

        $todaysRevenue = Order::query()
            ->where('status', OrderStatus::Delivered)
            ->whereDate('created_at', $today)
            ->sum('total');

        return [
            Stat::make('New orders', (string) $newOrders)
                ->description('Awaiting kitchen')
                ->descriptionIcon('heroicon-m-document-text')
                ->icon('heroicon-o-document-text')
                ->color('gray'),
            Stat::make('Preparing', (string) $preparingOrders)
                ->description('In progress')
                ->descriptionIcon('heroicon-m-beaker')
                ->icon('heroicon-o-beaker')
                ->color('info'),
            Stat::make('Out for delivery', (string) $outForDelivery)
                ->description('On the way')
                ->descriptionIcon('heroicon-m-truck')
                ->icon('heroicon-o-truck')
                ->color('primary'),
            Stat::make("Today's revenue", number_format($todaysRevenue, 2))
                ->description('Delivered today')
                ->descriptionIcon('heroicon-m-currency-dollar')
                ->icon('heroicon-o-currency-dollar')
                ->color('success'),
        ];
    }
}
