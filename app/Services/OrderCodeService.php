<?php

namespace App\Services;

use App\Models\Order;
use Illuminate\Support\Facades\DB;

class OrderCodeService
{
    /**
     * Generate next unique order code: CD-YYYY-000001
     */
    public function generate(): string
    {
        $year = now()->format('Y');
        $prefix = "CD-{$year}-";

        return DB::transaction(function () use ($prefix) {
            $last = Order::query()
                ->where('order_code', 'like', $prefix.'%')
                ->orderByDesc('id')
                ->lockForUpdate()
                ->value('order_code');

            $next = 1;
            if ($last) {
                $num = (int) substr($last, strlen($prefix));
                $next = $num + 1;
            }

            return $prefix.str_pad((string) $next, 6, '0', STR_PAD_LEFT);
        });
    }
}
