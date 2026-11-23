@extends('layouts.salesperson')

@section('content')
<div class="py-10">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

        <!-- Header & Search Bar -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-black text-[#2e1065]">Manage Orders</h1>
                <p class="text-xs text-slate-500 font-medium">View and update customer orders, payment status, and tracking progress.</p>
            </div>

            <div class="flex items-center gap-3">
                <!-- 🔍 Ruangan Carian Order ID / Nama -->
                <form action="{{ route('salesperson.orders.index') }}" method="GET" class="flex items-center gap-2">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search Order ID (e.g. ORD-21)..."
                        class="px-4 py-2 bg-white rounded-xl border border-slate-200 text-xs focus:outline-none focus:border-[#6d28d9] w-60 shadow-xs">
                    <button type="submit" class="px-4 py-2 bg-[#6d28d9] hover:bg-purple-800 text-white font-bold rounded-xl text-xs transition shadow-xs">
                        Search
                    </button>
                    @if(request('search'))
                        <a href="{{ route('salesperson.orders.index') }}" class="px-3 py-2 bg-slate-200 hover:bg-slate-300 text-slate-700 rounded-xl text-xs font-bold transition">
                            Reset
                        </a>
                    @endif
                </form>

                <a href="{{ route('salesperson.orders.create') }}" class="px-4 py-2 bg-[#6d28d9] hover:bg-purple-800 text-white font-bold rounded-xl text-xs transition shadow-xs flex items-center gap-1.5">
                    + Create New Order
                </a>
            </div>
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
                            <td class="p-4 font-medium">
                                {{ $order->customer->formatted_id ?? '' }} {{ $order->customer->customerName ?? 'Walk-in Customer' }}
                                @if($order->customer && isset($order->customer->customerId))
                                    <span class="block text-[10px] text-slate-400 font-semibold">ID: {{ $order->customer->customerId }}</span>
                                @endif
                            </td>
                            <td class="p-4 font-bold text-slate-700">RM {{ number_format($order->total_price ?? 0.00, 2) }}</td>
                            <td class="p-4">
                                {{-- Menyesuaikan warna mengikut status baru 'Fully Paid' atau 'Depo' --}}
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold {{ $order->payment_status == 'Fully Paid' ? 'bg-green-100 text-green-700' : 'bg-blue-100 text-blue-700' }}">
                                    {{ $order->payment_status ?? 'Depo' }}
                                </span>
                            </td>
                            <td class="p-4">
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-purple-50 text-purple-700 border border-purple-100 uppercase">
                                    {{ $order->order_status ?? 'pending' }}
                                </span>
                            </td>
                            <td class="p-4 text-center space-x-2">
                                <a href="{{ route('salesperson.orders.edit', $order->id) }}" class="inline-block px-3 py-1.5 bg-purple-50 text-[#6d28d9] rounded-lg font-bold hover:bg-[#6d28d9] hover:text-white transition">
                                    Manage / Update
                                </a>
                                <a href="{{ route('salesperson.orders.invoice', $order->id) }}" target="_blank" class="inline-block px-3 py-1.5 bg-slate-100 text-slate-700 rounded-lg font-bold hover:bg-slate-200 transition">
                                    <i class="fa-solid fa-print"></i>
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
