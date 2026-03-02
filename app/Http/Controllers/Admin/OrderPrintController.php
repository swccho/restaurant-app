<?php

namespace App\Http\Controllers\Admin;

use App\Models\Order;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OrderPrintController
{
    use AuthorizesRequests;

    public function __invoke(Request $request, Order $order): View
    {
        $this->authorize('view', $order);

        $order->load('items');

        return view('admin.orders.print', [
            'order' => $order,
        ]);
    }
}
