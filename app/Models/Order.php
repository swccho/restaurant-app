<?php

namespace App\Models;

use App\Enums\OrderStatus;
use App\Services\OrderCodeService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    protected static function booted(): void
    {
        static::creating(function (Order $order): void {
            if (blank($order->order_code)) {
                $order->order_code = app(OrderCodeService::class)->generate();
            }
        });
    }

    protected $fillable = [
        'order_code',
        'customer_name',
        'customer_phone',
        'customer_address',
        'order_type',
        'status',
        'status_updated_at',
        'status_updated_by',
        'subtotal',
        'discount',
        'total',
        'notes',
    ];

    protected $casts = [
        'status' => OrderStatus::class,
        'status_updated_at' => 'datetime',
        'subtotal' => 'decimal:2',
        'discount' => 'decimal:2',
        'total' => 'decimal:2',
    ];

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function statusUpdatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'status_updated_by');
    }
}
