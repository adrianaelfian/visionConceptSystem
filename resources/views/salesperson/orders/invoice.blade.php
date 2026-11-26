@extends('layouts.salesperson')

@section('content')
    <div class="max-w-3xl mx-auto bg-white p-10 shadow-sm border border-slate-100 rounded-2xl print:shadow-none print:border-none print:p-0">
        <div class="flex justify-between border-b border-slate-100 pb-6 mb-6">
            <div>
                <h1 class="text-2xl font-black text-[#2e1065]">INVOICE</h1>
                <p class="text-xs text-slate-400 mt-0.5">Order ID: #ORD-{{ $order->id }}</p>
            </div>
            <div class="text-right">
                <p class="font-bold text-sm text-[#3b127e]">Vision Concept Optical</p>
                <p class="text-xs text-slate-500">Klang, Selangor</p>
            </div>
        </div>

        <div class="mb-6 text-xs bg-purple-50/50 p-4 rounded-xl border border-purple-100/50">
            <span class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Billed To:</span>
            <p class="font-bold text-slate-800 text-sm">{{ $order->customer->name ?? $order->customer->customerName ?? 'N/A' }}</p>
        </div>

        <table class="w-full mb-8 text-xs">
            <thead class="border-b border-slate-100">
                <tr class="text-left text-slate-400 uppercase text-[10px] tracking-wider">
                    <th class="py-3 px-2 font-bold">Item</th>
                    <th class="py-3 px-2 font-bold text-center">Qty</th>
                    <th class="py-3 px-2 font-bold text-right">Price</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50 font-medium text-slate-700">
                @foreach($order->orderItems as $item)
                <tr>
                    <td class="py-3 px-2 font-bold text-slate-900">{{ $item->item->name ?? $item->item->itemName ?? 'Optical Product' }}</td>
                    <td class="py-3 px-2 text-center font-semibold text-slate-600">{{ $item->quantity ?? 1 }}</td>
                    <td class="py-3 px-2 text-right font-bold text-[#3b127e]">RM {{ number_format($item->price, 2) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="border-t border-slate-100 pt-4 text-right space-y-1.5 text-xs">
            <p class="text-slate-600">Total Amount: <span class="font-bold text-slate-900">RM {{ number_format($order->total_price, 2) }}</span></p>
            <p class="text-emerald-600 font-semibold">Paid: RM {{ number_format($order->amount_paid, 2) }}</p>
            <p class="font-bold text-base mt-2 {{ ($order->remaining_balance ?? $order->total_price) <= 0 ? 'text-emerald-600' : 'text-rose-600' }}">
                Balance: RM {{ number_format($order->remaining_balance ?? $order->total_price, 2) }}
            </p>
        </div>

        <div class="mt-8 pt-4 border-t border-slate-100 print:hidden flex justify-between items-center">
            <a href="{{ route('salesperson.orders.index') }}" class="text-slate-500 hover:text-slate-800 font-bold text-xs flex items-center space-x-1">
                <i class="fa-solid fa-arrow-left"></i>
                <span>Back to List of Order</span>
            </a>
            <button onclick="window.print()" class="bg-[#6d28d9] text-white px-5 py-2.5 rounded-xl text-xs font-bold shadow-xs hover:bg-purple-800 transition">
                Print Invoice
            </button>
        </div>
    </div>
@endsection
