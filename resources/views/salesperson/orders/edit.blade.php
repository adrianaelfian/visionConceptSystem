@extends('layouts.salesperson')

@section('content')
    <div class="space-y-6 max-w-4xl mx-auto">
        <!-- Header Halaman -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-black text-[#2e1065]">Manage Order #ORD-{{ $order->id ?? 'N/A' }}</h1>
                <p class="text-xs text-slate-500 mt-0.5">Update payment information, track progress, and manage order status.</p>
            </div>
            <a href="{{ route('salesperson.orders.index') }}" class="px-4 py-2 bg-slate-100 text-slate-600 rounded-xl text-xs font-bold hover:bg-slate-200 transition flex items-center justify-center space-x-2">
                <i class="fa-solid fa-arrow-left text-xs"></i>
                <span>Back to Orders</span>
            </a>
        </div>

        <!-- Paparan Ringkasan & Borang Kemaskini -->
        <div class="bg-white rounded-2xl border border-slate-100 shadow-xs p-6">
            <form action="{{ route('salesperson.orders.update', $order->id ?? 1) }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')

                <!-- Maklumat Pelanggan & Ringkasan Kos -->
                <div class="border-b border-slate-100 pb-4">
                    <h3 class="text-xs font-black text-[#3b127e] uppercase tracking-wider mb-3">Order Summary</h3>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 bg-slate-50 p-4 rounded-xl border border-slate-200 text-xs">
                        <div>
                            <span class="block text-[10px] font-bold text-slate-400 uppercase">Customer Name</span>
                            <span class="font-bold text-slate-700">{{ $order->customer->name ?? 'Walk-in Customer' }}</span>
                        </div>
                        <div>
                            <span class="block text-[10px] font-bold text-slate-400 uppercase">Total Amount</span>
                            <span class="font-bold text-[#3b127e]">RM {{ number_format($order->total_amount ?? 0.00, 2) }}</span>
                        </div>
                        <div>
                            <span class="block text-[10px] font-bold text-slate-400 uppercase">Current Payment Status</span>
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold {{ ($order->payment_status ?? '') == 'Fully Paid' ? 'bg-green-100 text-green-700' : 'bg-amber-100 text-amber-700' }}">
                                {{ $order->payment_status ?? 'Pending' }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Bahagian Kemaskini Bayaran & Status (UCD700 Flow A1) -->
                <div class="space-y-4">
                    <h3 class="text-xs font-black text-[#3b127e] uppercase tracking-wider">Update Payment & Progress Status</h3>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <!-- Amount Paid -->
                        <div>
                            <label class="block text-[11px] font-bold text-slate-600 uppercase mb-1">Amount Paid (RM)</label>
                            <input type="number" step="0.01" name="amount_paid" value="{{ old('amount_paid', $order->amount_paid ?? 0.00) }}" required class="w-full px-3.5 py-2 rounded-xl border border-slate-200 text-xs focus:outline-none focus:border-[#3b127e] bg-white">
                            @error('amount_paid')
                                <p class="text-red-500 text-[10px] mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Order Status -->
                        <div>
                            <label class="block text-[11px] font-bold text-slate-600 uppercase mb-1">Order Status</label>
                            <select name="order_status" required class="w-full px-3.5 py-2 rounded-xl border border-slate-200 text-xs focus:outline-none focus:border-[#3b127e] bg-white">
                                <option value="Processing" {{ (isset($order) && $order->order_status == 'Processing') ? 'selected' : '' }}>Processing</option>
                                <option value="Ready for collection" {{ (isset($order) && $order->order_status == 'Ready for collection') ? 'selected' : '' }}>Ready for collection</option>
                                <option value="Completed" {{ (isset($order) && $order->order_status == 'Completed') ? 'selected' : '' }}>Completed (Collected)</option>
                            </select>
                            <p class="text-[10px] text-slate-400 mt-1">Status can only be set to Completed once the customer picks up the order.</p>
                        </div>
                    </div>
                </div>

                <!-- Butang Tindakan -->
                <div class="pt-4 border-t border-slate-100 flex justify-end space-x-3">
                    <a href="{{ route('salesperson.orders.index') }}" class="px-4 py-2.5 border border-slate-200 text-slate-600 rounded-xl text-xs font-bold hover:bg-slate-50 transition">Cancel</a>
                    <button type="submit" class="px-5 py-2.5 bg-[#6d28d9] text-white rounded-xl text-xs font-bold hover:bg-purple-800 transition shadow-xs">
                        Update Order Details
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
