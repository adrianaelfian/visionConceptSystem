@extends('layouts.salesperson')

@section('content')
<div class="space-y-6 max-w-6xl mx-auto">
    <!-- Header Halaman -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black text-[#2e1065]">Manage Orders</h1>
            <p class="text-xs text-slate-500 mt-0.5">View and update customer orders, payment status, and tracking progress.</p>
        </div>
        <a href="{{ route('salesperson.orders.create') }}" class="px-4 py-2 bg-[#6d28d9] text-white rounded-xl text-xs font-bold hover:bg-purple-800 transition shadow-xs flex items-center justify-center space-x-2">
            <i class="fa-solid fa-plus text-xs"></i>
            <span>Create New Order</span>
        </a>
    </div>

    <!-- Senarai Pesanan (Jadual) -->
    <div class="bg-white rounded-2xl border border-slate-100 shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-100 text-[11px] font-black text-slate-500 uppercase tracking-wider">
                        <th class="p-4">Order ID</th>
                        <th class="p-4">Customer</th>
                        <th class="p-4">Total Amount</th>
                        <th class="p-4">Payment Status</th>
                        <th class="p-4">Order Status</th>
                        <th class="p-4 text-center">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-xs text-slate-600">
                    @forelse($orders ?? [] as $order)
                        <tr class="hover:bg-slate-50/50 transition">
                            <td class="p-4 font-bold text-[#2e1065]">#ORD-{{ $order->id }}</td>
                            <td class="p-4 font-medium">{{ $order->customer->name ?? $order->customer->customerName ?? 'Walk-in Customer' }}</td>
                            <td class="p-4 font-bold text-slate-700">RM {{ number_format($order->total_amount, 2) }}</td>
                            <td class="p-4">
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold {{ $order->payment_status == 'Fully Paid' ? 'bg-green-100 text-green-700' : 'bg-amber-100 text-amber-700' }}">
                                    {{ $order->payment_status ?? 'Pending' }}
                                </span>
                            </td>
                            <td class="p-4">
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-purple-50 text-purple-700 border border-purple-100">
                                    {{ $order->order_status ?? 'Processing' }}
                                </span>
                            </td>
                            <td class="p-4 text-center space-x-2">
                                <a href="{{ route('salesperson.orders.edit', $order->id) }}" class="inline-px px-3 py-1.5 bg-purple-50 text-[#6d28d9] rounded-lg font-bold hover:bg-[#6d28d9] hover:text-white transition">
                                    Manage / Update
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="p-8 text-center text-slate-400 text-xs">
                                No orders found. Click "Create New Order" to add one.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
