<?php

namespace App\Filament\Admin\Resources\OrderResource\RelationManagers;

use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class ItemsRelationManager extends RelationManager
{
    protected static string $relationship = 'items';

    protected static ?string $title = 'Order items';

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name_snapshot')
            ->columns([
                Tables\Columns\TextColumn::make('name_snapshot')->label('Item'),
                Tables\Columns\TextColumn::make('price_snapshot')->money()->label('Unit price'),
                Tables\Columns\TextColumn::make('qty'),
                Tables\Columns\TextColumn::make('line_total')->money(),
            ])
            ->filters([])
            ->headerActions([])
            ->actions([])
            ->bulkActions([]);
    }
}
