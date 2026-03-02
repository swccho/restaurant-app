<?php

namespace App\Filament\Admin\Widgets;

use App\Enums\OrderStatus;
use App\Filament\Admin\Resources\OrderResource;
use App\Models\Order;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Carbon;

class TodaysOrdersTableWidget extends BaseWidget
{
    protected static ?int $sort = 2;

    protected int|string|array $columnSpan = 'full';

    protected function getTableHeading(): string
    {
        return "Today's orders";
    }

    protected function getTableRecordsPerPageSelectOptions(): array
    {
        return [10];
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Order::query()
                    ->whereDate('created_at', Carbon::today())
                    ->orderByDesc('created_at')
            )
            ->columns([
                Tables\Columns\TextColumn::make('order_code')
                    ->label('Order code')
                    ->searchable(false)
                    ->sortable(false),
                Tables\Columns\TextColumn::make('customer_name')
                    ->label('Customer')
                    ->searchable(false)
                    ->sortable(false),
                Tables\Columns\TextColumn::make('status')
                    ->formatStateUsing(fn (OrderStatus $state) => $state->label())
                    ->badge()
                    ->color(fn (OrderStatus $state) => $state->color())
                    ->sortable(false),
                Tables\Columns\TextColumn::make('total')
                    ->money()
                    ->sortable(false),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Time')
                    ->dateTime()
                    ->sortable(false),
            ])
            ->actions([
                Tables\Actions\Action::make('view')
                    ->label('View')
                    ->icon('heroicon-o-eye')
                    ->url(fn (Order $record): string => OrderResource::getUrl('view', ['record' => $record])),
            ])
            ->paginated([10])
            ->defaultPaginationPageOption(10)
            ->striped();
    }

    protected function paginateTableQuery(Builder $query): Paginator
    {
        $total = $query->count();
        $items = (clone $query)->limit(10)->get();

        return new LengthAwarePaginator($items, $total, 10, 1, [
            'pageName' => $this->getTablePaginationPageName(),
        ]);
    }
}
