<?php

namespace App\Filament\Admin\Resources;

use App\Enums\OfferScope;
use App\Enums\OfferType;
use App\Filament\Admin\Resources\OfferResource\Pages;
use App\Models\Offer;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class OfferResource extends Resource
{
    protected static ?string $model = Offer::class;

    protected static ?string $navigationIcon = 'heroicon-o-ticket';

    protected static ?string $navigationLabel = 'Offers';

    protected static ?int $navigationSort = 3;

    protected static bool $isScopedToTenant = false;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Basic')
                    ->schema([
                        Forms\Components\TextInput::make('title')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('e.g. Summer special')
                            ->helperText('Short title shown to customers.'),
                        Forms\Components\Textarea::make('description')
                            ->rows(3)
                            ->placeholder('Optional description.')
                            ->helperText('Optional. Internal or customer-facing note.'),
                    ])
                    ->columns(1),

                Forms\Components\Section::make('Type & Value')
                    ->schema([
                        Forms\Components\Select::make('type')
                            ->options(collect(OfferType::cases())->mapWithKeys(fn (OfferType $t) => [$t->value => $t->label()]))
                            ->required()
                            ->live()
                            ->native(false),
                        Forms\Components\TextInput::make('value')
                            ->required()
                            ->numeric()
                            ->minValue(0.01)
                            ->step(0.01)
                            ->prefix(fn (Forms\Get $get) => $get('type') === OfferType::Percentage->value ? null : '৳')
                            ->suffix(fn (Forms\Get $get) => $get('type') === OfferType::Percentage->value ? '%' : null)
                            ->rules([
                                fn (Forms\Get $get) => function (string $attribute, $value, \Closure $fail) use ($get) {
                                    if ($get('type') === OfferType::Percentage->value) {
                                        if ($value < 1 || $value > 100) {
                                            $fail('Percentage must be between 1 and 100.');
                                        }
                                    } else {
                                        if ($value <= 0) {
                                            $fail('Fixed value must be greater than 0.');
                                        }
                                    }
                                },
                            ])
                            ->helperText(fn (Forms\Get $get) => $get('type') === OfferType::Percentage->value
                                ? 'Discount percentage (1–100).'
                                : 'Fixed discount amount (e.g. 50).'),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Scope')
                    ->schema([
                        Forms\Components\Select::make('scope')
                            ->options(collect(OfferScope::cases())->mapWithKeys(fn (OfferScope $s) => [$s->value => $s->label()]))
                            ->required()
                            ->live()
                            ->native(false)
                            ->afterStateUpdated(fn (Forms\Set $set, ?string $state) => in_array($state, [OfferScope::All->value, OfferScope::Items->value], true) ? $set('category_id', null) : null)
                            ->helperText('Where this offer applies.'),
                        Forms\Components\Select::make('category_id')
                            ->relationship('category', 'name')
                            ->searchable()
                            ->preload()
                            ->placeholder('Select category')
                            ->visible(fn (Forms\Get $get) => $get('scope') === OfferScope::Category->value)
                            ->required(fn (Forms\Get $get) => $get('scope') === OfferScope::Category->value)
                            ->helperText('Required when scope is Category.'),
                        Forms\Components\Placeholder::make('items_placeholder')
                            ->label('')
                            ->content('Item-scoped offers will be enabled in Phase 2.')
                            ->visible(fn (Forms\Get $get) => $get('scope') === OfferScope::Items->value),
                    ])
                    ->columns(1),

                Forms\Components\Section::make('Schedule')
                    ->schema([
                        Forms\Components\DateTimePicker::make('start_at')
                            ->nullable()
                            ->helperText('Optional. If empty, offer can be active immediately (use is_active).'),
                        Forms\Components\DateTimePicker::make('end_at')
                            ->nullable()
                            ->rules([
                                fn (Forms\Get $get) => function (string $attribute, $value, \Closure $fail) use ($get) {
                                    $start = $get('start_at');
                                    if (filled($value) && filled($start) && \Carbon\Carbon::parse($value)->lt(\Carbon\Carbon::parse($start))) {
                                        $fail('End date must be on or after start date.');
                                    }
                                },
                            ])
                            ->helperText('Must be on or after start date when both are set.'),
                        Forms\Components\Toggle::make('is_active')
                            ->label('Active')
                            ->default(true)
                            ->required()
                            ->helperText('Inactive offers are not applied.'),
                    ])
                    ->columns(1),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('type')
                    ->formatStateUsing(fn (OfferType $state) => $state->label())
                    ->badge()
                    ->color(fn (OfferType $state) => $state->color())
                    ->sortable(),
                Tables\Columns\TextColumn::make('value')
                    ->formatStateUsing(function ($state, Offer $record) {
                        return $record->type === OfferType::Percentage
                            ? "{$state}%"
                            : '৳'.number_format((float) $state, 2);
                    })
                    ->sortable(),
                Tables\Columns\TextColumn::make('scope')
                    ->formatStateUsing(fn (OfferScope $state) => $state->label())
                    ->badge()
                    ->color(fn (OfferScope $state) => $state->color())
                    ->sortable(),
                Tables\Columns\ToggleColumn::make('is_active')
                    ->label('Active')
                    ->sortable(),
                Tables\Columns\TextColumn::make('start_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('end_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->striped()
            ->filters([
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Status')
                    ->placeholder('All')
                    ->trueLabel('Active')
                    ->falseLabel('Inactive'),
                Tables\Filters\SelectFilter::make('type')
                    ->options(collect(OfferType::cases())->mapWithKeys(fn (OfferType $t) => [$t->value => $t->label()])),
                Tables\Filters\SelectFilter::make('scope')
                    ->options(collect(OfferScope::cases())->mapWithKeys(fn (OfferScope $s) => [$s->value => $s->label()])),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateHeading('No offers yet.')
            ->emptyStateDescription('Add your first offer or promotion.');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOffers::route('/'),
            'create' => Pages\CreateOffer::route('/create'),
            'edit' => Pages\EditOffer::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery();
    }
}
