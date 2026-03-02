# Cafe Delight Admin — Reference Patterns

## Migration Index Patterns

```php
// menu_items
$table->index(['category_id', 'is_available']);

// offers
$table->index(['is_active', 'start_at', 'end_at']);

// orders
$table->index(['status', 'created_at']);
```

## Order Status Enum

```php
enum OrderStatus: string
{
    case Received = 'received';
    case Preparing = 'preparing';
    case Ready = 'ready';
    case OutForDelivery = 'out_for_delivery';
    case Delivered = 'delivered';
    case Cancelled = 'cancelled';

    public function canTransitionTo(self $status): bool
    {
        return match ($this) {
            self::Received => in_array($status, [self::Preparing, self::Cancelled]),
            self::Preparing => in_array($status, [self::Ready, self::Cancelled]),
            self::Ready => in_array($status, [self::OutForDelivery, self::Cancelled]),
            self::OutForDelivery => $status === self::Delivered,
            self::Delivered, self::Cancelled => false,
        };
    }
}
```

## Order Snapshot in OrderItem

```php
// When creating order items, copy from menu_item:
$orderItem->name_snapshot = $menuItem->name;
$orderItem->price_snapshot = $menuItem->price;
```

## Filament Conditional Fields (Offer Scope)

```php
Select::make('scope')
    ->options(OfferScope::class)
    ->required()
    ->live(),

Select::make('category_id')
    ->relationship('category', 'name')
    ->required()
    ->visible(fn (Get $get) => $get('scope') === OfferScope::Category),

Select::make('menu_item_ids')
    ->multiple()
    ->relationship('menuItems', 'name')
    ->required()
    ->visible(fn (Get $get) => $get('scope') === OfferScope::Items),
```

## Spatie Media Library on MenuItem

```php
// Model
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class MenuItem extends Model implements HasMedia
{
    use InteractsWithMedia;

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('images')->useDisk('public');
    }

    public function registerMediaConversions(?Media $media = null): void
    {
        $this->addMediaConversion('thumb')->width(150)->height(150);
    }
}

// Filament table
ImageColumn::make('image')
    ->getStateUsing(fn (MenuItem $record) => $record->getFirstMediaUrl('images', 'thumb'))
    ->circular(),
```

## Filament Role Restriction

```php
// In App\Providers\Filament\AdminPanelProvider or similar
->authMiddleware(['auth'])
->authGuard('web')
// Then in User model or FilamentUser:
public function canAccessPanel(Panel $panel): bool
{
    return $this->hasRole(['owner', 'staff']);
}
```

## Offer Date Validation

```php
// In Filament form (use Get for start_at)
Rule::after(function ($attribute, $value, $fail) use ($get) {
    $startAt = $get('start_at');
    if ($startAt && $value && $value < $startAt) {
        $fail('End date must be after start date.');
    }
}),
```
