@extends('layouts.Customer')

@section('content')
    @php
        $lineItems = $order->orderItems ?? ($order->items ?? collect());
    @endphp

    <div class="max-w-4xl mx-auto space-y-6">
        <!-- Header & Back Button -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-white p-6 rounded-2xl border border-slate-100 shadow-xs">
            <div>
                <span class="text-[11px] font-bold uppercase tracking-wider text-purple-600">Order Details</span>
                <h1 class="text-2xl font-black text-[#2e1065] mt-0.5">#ORD-{{ $order->id }}</h1>
                <p class="text-xs text-slate-500 mt-1">
                    Placed on {{ $order->created_at ? $order->created_at->format('d M Y, h:i A') : '-' }}
                </p>
            </div>

            <div class="flex items-center gap-3">
                <span class="px-3 py-1.5 rounded-full text-xs font-extrabold bg-purple-50 text-purple-700 border border-purple-100">
                    {{ $order->order_status ?? 'Pending' }}
                </span>
                <a href="{{ route('customer.orders.index') }}"
                   class="px-4 py-2 rounded-xl text-xs font-bold bg-slate-100 text-slate-700 hover:bg-slate-200 transition">
                    ← Back to My Orders
                </a>
            </div>
        </div>

        <!-- Ringkasan Pembayaran -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-xs">
                <span class="text-[10px] font-bold uppercase text-slate-400">Total Price</span>
                <div class="text-lg font-black text-purple-950 mt-1">
                    RM {{ number_format($order->total_price ?? 0, 2) }}
                </div>
            </div>

            <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-xs">
                <span class="text-[10px] font-bold uppercase text-slate-400">Amount Paid</span>
                <div class="text-lg font-black text-emerald-700 mt-1">
                    RM {{ number_format($order->amount_paid ?? 0, 2) }}
                </div>
                @if(!empty($order->payment_status))
                    <span class="inline-block mt-1 text-[10px] font-bold text-slate-500">{{ $order->payment_status }}</span>
                @endif
            </div>

            <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-xs">
                <span class="text-[10px] font-bold uppercase text-slate-400">Remaining Balance</span>
                <div class="text-lg font-black text-amber-700 mt-1">
                    RM {{ number_format($order->remaining_balance ?? max(0, ($order->total_price ?? 0) - ($order->amount_paid ?? 0)), 2) }}
                </div>
            </div>
        </div>

        <!-- Senarai Item Pesanan -->
        <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-xs">
            <h2 class="text-xs font-black uppercase tracking-wider text-[#3b127e] mb-4">Ordered Items</h2>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs border-collapse">
                    <thead class="text-slate-400 border-b border-slate-100 uppercase text-[10px]">
                        <tr>
                            <th class="pb-3">Product</th>
                            <th class="pb-3">Unit Price</th>
                            <th class="pb-3">Qty</th>
                            <th class="pb-3 text-right">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-slate-700 font-medium">
                        @forelse($lineItems as $row)
                            @php
                                $qty = (int) ($row->quantity ?? $row->pivot->quantity ?? 1);
                                $unitPrice = (float) ($row->price ?? $row->pivot->price ?? ($row->item->price ?? 0));
                                $subtotal = $qty * $unitPrice;
                                $itemName = $row->item->name ?? ($row->name ?? 'Item #' . ($row->item_id ?? $row->id));
                            @endphp
                            <tr>
                                <td class="py-3.5 font-bold text-slate-900">{{ $itemName }}</td>
                                <td class="py-3.5">RM {{ number_format($unitPrice, 2) }}</td>
                                <td class="py-3.5">{{ $qty }}</td>
                                <td class="py-3.5 text-right font-bold text-purple-950">RM {{ number_format($subtotal, 2) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="py-8 text-center text-slate-400">
                                    No item breakdown found for this order.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
