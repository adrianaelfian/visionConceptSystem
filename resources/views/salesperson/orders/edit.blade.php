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
            <form action="{{ route('salesperson.orders.update', $order->id) }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')

                <!-- Maklumat Pelanggan & Ringkasan Kos -->
                <div class="border-b border-slate-100 pb-4">
                    <h3 class="text-xs font-black text-[#3b127e] uppercase tracking-wider mb-3">Order Summary</h3>
                    <div class="grid grid-cols-1 sm:grid-cols-4 gap-4 bg-slate-50 p-4 rounded-xl border border-slate-200 text-xs">
                        <div>
                            <span class="block text-[10px] font-bold text-slate-400 uppercase">Customer Name</span>
                            <span class="font-bold text-slate-700">{{ $order->customer->name ?? $order->customer->customerName ?? 'Walk-in Customer' }}</span>
                            @if(isset($order->customer->customerId))
                                <span class="block text-[10px] text-slate-400">ID: {{ $order->customer->customerId }}</span>
                            @endif
                        </div>
                        <div>
                            <span class="block text-[10px] font-bold text-slate-400 uppercase">Total Amount</span>
                            <span class="font-bold text-[#3b127e]">RM {{ number_format($order->total_price ?? $order->total_amount ?? 0.00, 2) }}</span>
                        </div>
                        <div>
                            <span class="block text-[10px] font-bold text-slate-400 uppercase">Balance Due (Baki)</span>
                            <span class="font-bold text-rose-600">RM {{ number_format($order->remaining_balance ?? (($order->total_price ?? 0) - ($order->amount_paid ?? 0)), 2) }}</span>
                        </div>
                        <div>
                            <span class="block text-[10px] font-bold text-slate-400 uppercase">Current Payment Status</span>
                            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold {{ ($order->payment_status ?? '') == 'Fully Paid' ? 'bg-green-100 text-green-700' : 'bg-blue-100 text-blue-700' }}">
                                {{ $order->payment_status ?? 'Depo' }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Kad Paparan Latest Eye Condition Prescription -->
                @php
                    $latestCondition = $order->customer ? $order->customer->eyeConditions()->latest()->first() : null;
                @endphp

                <div class="bg-purple-50/60 border border-purple-100 p-4 rounded-xl">
                    <h3 class="text-xs font-extrabold text-purple-700 uppercase tracking-wider mb-3 flex items-center gap-2">
                        <i class="fa-solid fa-glasses"></i> Latest Eye Condition Prescription
                    </h3>
                    @if($latestCondition)
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-3 text-xs">
                            <div class="bg-white p-3 rounded-lg border border-purple-100 shadow-xs">
                                <span class="text-slate-400 block font-semibold mb-0.5 text-[10px]">Right (SPH/CYL/AX)</span>
                                <strong class="text-slate-800 text-xs">{{ $latestCondition->rightPower ?? '-' }} / {{ $latestCondition->rightCylinder ?? '-' }} / {{ $latestCondition->rightAxis ?? '-' }}°</strong>
                            </div>
                            <div class="bg-white p-3 rounded-lg border border-purple-100 shadow-xs">
                                <span class="text-slate-400 block font-semibold mb-0.5 text-[10px]">Left (SPH/CYL/AX)</span>
                                <strong class="text-slate-800 text-xs">{{ $latestCondition->leftPower ?? '-' }} / {{ $latestCondition->leftCylinder ?? '-' }} / {{ $latestCondition->leftAxis ?? '-' }}°</strong>
                            </div>
                            <div class="bg-white p-3 rounded-lg border border-purple-100 shadow-xs">
                                <span class="text-slate-400 block font-semibold mb-0.5 text-[10px]">Pupil Distance (PD)</span>
                                <strong class="text-slate-800 text-xs">{{ $latestCondition->pupilDistance ?? '-' }} mm</strong>
                            </div>
                            <div class="bg-white p-3 rounded-lg border border-purple-100 shadow-xs">
                                <span class="text-slate-400 block font-semibold mb-0.5 text-[10px]">Date Recorded</span>
                                <strong class="text-slate-800 text-xs">{{ $latestCondition->created_at ? $latestCondition->created_at->format('d/m/Y') : '-' }}</strong>
                            </div>
                        </div>
                    @else
                        <p class="text-slate-400 italic text-xs">No prescription record found for this customer.</p>
                    @endif
                </div>

                <!-- Bahagian Kemaskini Status Bayaran & Progress Status -->
                <div class="space-y-4">
                    <h3 class="text-xs font-black text-[#3b127e] uppercase tracking-wider">Update Payment & Progress Status</h3>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <!-- Payment Status Dropdown -->
                        <div>
                            <label class="block text-[11px] font-bold text-slate-600 uppercase mb-1">Payment Status</label>
                            <select name="payment_status" required class="w-full px-3.5 py-2 rounded-xl border border-slate-200 text-xs focus:outline-none focus:border-[#3b127e] bg-white">
                                <option value="Depo" {{ old('payment_status', $order->payment_status) == 'Depo' ? 'selected' : '' }}>
                                    Deposit
                                </option>
                                <option value="Fully Paid" {{ old('payment_status', $order->payment_status) == 'Fully Paid' ? 'selected' : '' }}>
                                    Fully Paid
                                </option>
                            </select>
                            @error('payment_status')
                                <p class="text-red-500 text-[10px] mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Order Status Dropdown -->
                        <div>
                            <label class="block text-[11px] font-bold text-slate-600 uppercase mb-1">Order Progress Status</label>
                            <select name="order_status" required class="w-full px-3.5 py-2 rounded-xl border border-slate-200 text-xs focus:outline-none focus:border-[#3b127e] bg-white">
                                <option value="pending" {{ old('order_status', $order->order_status) == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="Processing" {{ old('order_status', $order->order_status) == 'Processing' ? 'selected' : '' }}>Processing</option>
                                <option value="Ready for collection" {{ old('order_status', $order->order_status) == 'Ready for collection' ? 'selected' : '' }}>Ready for collection</option>
                                <option value="Not Picked Up" {{ old('order_status', $order->order_status) == 'Not Picked Up' ? 'selected' : '' }}>Not Picked Up</option>
                                <option value="Completed" {{ old('order_status', $order->order_status) == 'Completed' ? 'selected' : '' }}>Completed (Collected)</option>
                            </select>
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
