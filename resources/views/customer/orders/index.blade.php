@extends('layouts.customer')

@section('content')
<div class="space-y-6 max-w-5xl mx-auto py-8">
    <!-- Header Halaman -->
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-black text-[#2e1065]">My Eyewear Orders</h1>
            <p class="text-xs text-slate-500 mt-0.5">Track your past purchases, status, and receipt details.</p>
        </div>
        <a href="{{ route('customer.dashboard') }}" class="px-3.5 py-2 bg-slate-100 text-slate-600 rounded-xl text-xs font-bold hover:bg-slate-200 transition">
            Back to Dashboard
        </a>
    </div>

    <!-- Jadual Senarai Pesanan -->
    <div class="bg-white p-6 rounded-2xl shadow-xs border border-slate-100">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs border-collapse">
                <thead>
                    <tr class="text-slate-400 border-b border-slate-100 uppercase text-[10px] tracking-wider">
                        <th class="py-4 px-4 font-bold">Order ID</th>
                        <th class="py-4 px-4 font-bold">Date</th>
                        <th class="py-4 px-4 font-bold">Total Price</th>
                        <th class="py-4 px-4 font-bold">Status</th>
                        <th class="py-4 px-4 font-bold text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="text-slate-700 divide-y divide-slate-50 font-medium">
                    @forelse($orders as $order)
                    <tr class="hover:bg-slate-50/50 transition">
                        <td class="py-4 px-4 font-bold text-slate-900">#ORD-{{ $order->id }}</td>
                        <td class="py-4 px-4 text-slate-500">{{ $order->created_at->format('d M Y, h:i A') }}</td>
                        <td class="py-4 px-4 font-semibold text-purple-950">RM {{ number_format($order->total_price, 2) }}</td>
                        <td class="py-4 px-4">
                            <span class="px-2.5 py-1 rounded-full text-[10px] font-extrabold bg-purple-50 text-purple-700">
                                {{ $order->order_status ?? 'Processing' }}
                            </span>
                        </td>
                        <td class="py-4 px-4 text-right">
                            @if(\Illuminate\Support\Facades\Route::has('customer.orders.show'))
                                <a href="{{ route('customer.orders.show', $order->id) }}" class="text-purple-600 font-bold hover:text-purple-800 bg-purple-50 px-3 py-1.5 rounded-lg">
                                    View Details
                                </a>
                            @else
                                <span class="text-slate-400 italic">No action</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="p-12 text-center text-slate-400 font-medium">
                            <i class="fa-solid fa-bag-shopping block text-2xl mb-2 text-slate-300"></i>
                            You have no order records found.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
