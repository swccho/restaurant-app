<?php

namespace App\Enums;

enum OrderStatus: string
{
    case Received = 'received';
    case Preparing = 'preparing';
    case Ready = 'ready';
    case OutForDelivery = 'out_for_delivery';
    case Delivered = 'delivered';
    case Cancelled = 'cancelled';

    public function label(): string
    {
        return match ($this) {
            self::Received => 'Received',
            self::Preparing => 'Preparing',
            self::Ready => 'Ready',
            self::OutForDelivery => 'Out for delivery',
            self::Delivered => 'Delivered',
            self::Cancelled => 'Cancelled',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::Received => 'gray',
            self::Preparing => 'info',
            self::Ready => 'warning',
            self::OutForDelivery => 'primary',
            self::Delivered => 'success',
            self::Cancelled => 'danger',
        };
    }

    /**
     * Whether this status can transition to the given target status.
     */
    public function canTransitionTo(OrderStatus $target): bool
    {
        if ($this === $target) {
            return false;
        }

        if ($target === self::Cancelled) {
            return in_array($this, [self::Received, self::Preparing, self::Ready], true);
        }

        $allowed = match ($this) {
            self::Received => [self::Preparing, self::Cancelled],
            self::Preparing => [self::Ready, self::Cancelled],
            self::Ready => [self::OutForDelivery, self::Cancelled],
            self::OutForDelivery => [self::Delivered],
            self::Delivered => [],
            self::Cancelled => [],
        };

        return in_array($target, $allowed, true);
    }
}
