@extends('layouts.print')

@section('title', 'Order ' . $order->order_code)

@push('styles')
<style>
    .receipt { max-width: 400px; margin: 0 auto; }
    .receipt h1 { font-size: 1.5rem; margin: 0 0 0.25rem; }
    .receipt .code { font-size: 1.75rem; font-weight: 700; letter-spacing: 0.05em; }
    .receipt .status { display: inline-block; padding: 0.25rem 0.5rem; border-radius: 4px; font-weight: 600; margin-bottom: 1rem; background: #f0f0f0; color: #333; }
    .receipt section { margin-bottom: 1rem; }
    .receipt .label { font-size: 0.75rem; text-transform: uppercase; color: #666; margin-bottom: 0.25rem; }
    .receipt table { width: 100%; border-collapse: collapse; margin: 0.5rem 0; }
    .receipt th, .receipt td { text-align: left; padding: 0.35rem 0.5rem; border-bottom: 1px solid #eee; }
    .receipt th { font-size: 0.75rem; color: #666; }
    .receipt .totals { margin-top: 1rem; text-align: right; }
    .receipt .totals .row { display: flex; justify-content: flex-end; gap: 1rem; }
    .receipt .totals .total { font-size: 1.25rem; font-weight: 700; }
    .receipt .meta { font-size: 0.85rem; color: #666; margin-top: 1.5rem; }
    .no-print { margin-bottom: 1rem; }
</style>
@endpush

@section('content')
<div class="receipt">
    <p class="no-print"><a href="#" onclick="window.print(); return false;">Print</a> | <a href="{{ url('/admin') }}">Back to admin</a></p>

    <h1>Order</h1>
    <div class="code">{{ $order->order_code }}</div>
    <span class="status">{{ $order->status->label() }}</span>

    <section>
        <div class="label">Customer</div>
        <div>{{ $order->customer_name }}</div>
        @if($order->customer_phone)<div>{{ $order->customer_phone }}</div>@endif
        @if($order->customer_address)<div>{{ $order->customer_address }}</div>@endif
    </section>

    <section>
        <div class="label">Items</div>
        <table>
            <thead>
                <tr>
                    <th>Item</th>
                    <th>Qty</th>
                    <th>Notes</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->items as $item)
                <tr>
                    <td>{{ $item->name_snapshot }}</td>
                    <td>{{ $item->qty }}</td>
                    <td>{{ $item->item_notes ?? '—' }}</td>
                    <td>{{ number_format($item->line_total, 2) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </section>

    <section class="totals">
        <div class="row"><span>Subtotal</span><span>{{ number_format($order->subtotal, 2) }}</span></div>
        @if((float) $order->discount > 0)
        <div class="row"><span>Discount</span><span>-{{ number_format($order->discount, 2) }}</span></div>
        @endif
        <div class="row total"><span>Total</span><span>{{ number_format($order->total, 2) }}</span></div>
    </section>

    @if($order->notes)
    <section>
        <div class="label">Notes</div>
        <div>{{ $order->notes }}</div>
    </section>
    @endif

    <div class="meta">Created: {{ $order->created_at->format('M j, Y g:i A') }}</div>
</div>
@endsection
