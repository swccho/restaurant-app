<?php

namespace App\Filament\Admin\Resources;

use App\Enums\OrderStatus;
use App\Filament\Admin\Resources\OrderResource\Pages;
use App\Filament\Admin\Resources\OrderResource\RelationManagers\ItemsRelationManager;
use App\Models\Order;
use Filament\Infolists\Components\Grid;
use Filament\Infolists\Components\Group;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-bag';

    protected static ?string $navigationLabel = 'Orders';

    protected static ?int $navigationSort = 4;

    protected static bool $isScopedToTenant = false;

    public static function canCreate(): bool
    {
        return false;
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Section::make('Order')
                    ->schema([
                        Grid::make(3)
                            ->schema([
                                TextEntry::make('order_code')->label('Order code'),
                                TextEntry::make('status')
                                    ->formatStateUsing(fn (OrderStatus $state) => $state->label())
                                    ->badge()
                                    ->color(fn (OrderStatus $state) => $state->color()),
                                TextEntry::make('order_type')->label('Type'),
                            ]),
                        Grid::make(2)
                            ->schema([
                                Group::make([
                                    TextEntry::make('customer_name'),
                                    TextEntry::make('customer_phone'),
                                    TextEntry::make('customer_address')->placeholder('—'),
                                ])->label('Customer'),
                                Group::make([
                                    TextEntry::make('subtotal')->money(),
                                    TextEntry::make('discount')->money(),
                                    TextEntry::make('total')->money(),
                                ])->label('Totals'),
                            ]),
                        TextEntry::make('notes')->placeholder('—')->columnSpanFull(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->query(fn (): Builder => static::getEloquentQuery())
            ->columns([
                Tables\Columns\TextColumn::make('order_code')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('customer_name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('customer_phone')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->formatStateUsing(fn (OrderStatus $state) => $state->label())
                    ->badge()
                    ->color(fn (OrderStatus $state) => $state->color())
                    ->sortable(),
                Tables\Columns\TextColumn::make('total')
                    ->money()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->defaultPaginationPageOption(25)
            ->paginated([10, 25, 50])
            ->striped()
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options(collect(OrderStatus::cases())->mapWithKeys(fn (OrderStatus $s) => [$s->value => $s->label()])),
                Tables\Filters\SelectFilter::make('order_type')
                    ->options([
                        'delivery' => 'Delivery',
                        'pickup' => 'Pickup',
                    ]),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([])
            ->emptyStateHeading('No orders yet.')
            ->emptyStateDescription('New orders will appear here when placed.');
    }

    public static function getRelations(): array
    {
        return [
            ItemsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOrders::route('/'),
            'view' => Pages\ViewOrder::route('/{record}'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        $model = static::$model ?? Order::class;

        return $model::query()
            ->select([
                'id',
                'order_code',
                'customer_name',
                'customer_phone',
                'customer_address',
                'order_type',
                'status',
                'subtotal',
                'discount',
                'total',
                'notes',
                'created_at',
                'status_updated_at',
                'status_updated_by',
            ]);
    }
}
