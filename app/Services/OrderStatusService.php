<?php

namespace App\Services;

use App\Enums\OrderStatus;
use App\Models\Order;
use App\Models\User;
use InvalidArgumentException;

class OrderStatusService
{
    /**
     * Update order status if the transition is allowed.
     *
     * @throws InvalidArgumentException when transition is not allowed
     */
    public function updateStatus(Order $order, OrderStatus $target, ?User $by = null): void
    {
        $current = $order->status;

        if (! $current->canTransitionTo($target)) {
            throw new InvalidArgumentException(
                "Invalid status transition: cannot change from {$current->label()} to {$target->label()}."
            );
        }

        $order->update([
            'status' => $target,
            'status_updated_at' => now(),
            'status_updated_by' => $by?->getKey(),
        ]);
    }
}
