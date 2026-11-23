@extends('layouts.salesperson')

@section('content')
    <div class="py-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            <!-- Header Page -->
            <div>
                <h1 class="text-2xl font-black text-[#2e1065]">Salesperson Dashboard</h1>
                <p class="text-xs text-slate-500 font-medium">Welcome back! Manage your daily orders and Check customer order history.</p>
            </div>

            <!-- Action Buttons (Order Focused) -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Create New Order -->
                <div class="bg-white p-6 rounded-2xl shadow-xs border border-slate-100 flex items-center justify-between">
                    <div>
                        <h3 class="text-sm font-bold text-slate-700 mb-1">New Order</h3>
                        <p class="text-xs text-slate-400">Process and generate a new order for a customer</p>
                    </div>
                    <a href="{{ route('salesperson.orders.create') }}" class="px-4 py-2.5 bg-[#10b981] hover:bg-emerald-700 text-white font-bold rounded-xl text-xs transition shadow-xs flex items-center gap-1.5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        Create New Order
                    </a>
                </div>

                <!-- Manage Orders -->
                <div class="bg-white p-6 rounded-2xl shadow-xs border border-slate-100 flex items-center justify-between">
                    <div>
                        <h3 class="text-sm font-bold text-slate-700 mb-1">Order History</h3>
                        <p class="text-xs text-slate-400">View, track, and manage all existing orders</p>
                    </div>
                    <a href="{{ route('salesperson.orders.index') }}" class="px-4 py-2.5 bg-[#0284c7] hover:bg-sky-700 text-white font-bold rounded-xl text-xs transition shadow-xs flex items-center gap-1.5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                        Manage Orders
                    </a>
                </div>
            </div>

            <!-- Appointment Queue -->
            <div class="space-y-4">
                <h3 class="text-xs font-black text-[#3b127e] uppercase tracking-wider">Appointment Queue</h3>

                <div class="bg-white rounded-2xl border border-slate-100 shadow-xs overflow-hidden">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50 border-b border-slate-100 text-[11px] font-black text-slate-500 uppercase tracking-wider">
                                <th class="p-4">Date & Time</th>
                                <th class="p-4">Customer ID</th>
                                <th class="p-4">Reason</th>
                                <th class="p-4">Status</th>
                                <th class="p-4 text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-xs text-slate-600">
                            @forelse($appointments ?? [] as $apt)
                                <tr class="hover:bg-slate-50/50 transition">
                                    <td class="p-4">
                                        <span class="block font-bold text-[#2e1065]">{{ $apt->date }}</span>
                                        <span class="text-[10px] text-slate-400 font-medium">{{ $apt->time }}</span>
                                    </td>
                                    <td class="p-4 font-medium">{{ $apt->customer->customerID ?? $apt->customer->id ?? 'N/A' }}</td>
                                    <td class="p-4 text-slate-500">{{ $apt->reason }}</td>
                                    <td class="p-4">
                                        <span class="px-2.5 py-1 rounded-full text-[10px] font-bold
                                            {{ $apt->status == 'Scheduled' ? 'bg-blue-100 text-blue-700' : 'bg-amber-100 text-amber-700' }}">
                                            {{ $apt->status }}
                                        </span>
                                    </td>
                                    <td class="p-4 text-center space-x-1 flex justify-center items-center">
                                        <!-- Butang Arrived -->
                                        <form action="{{ route('salesperson.appointments.updateStatus', $apt->id ?? $apt->appointmentID) }}" method="POST" class="inline">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="status" value="Completed">
                                            <button type="submit" class="px-3 py-1 bg-green-50 text-green-700 rounded-lg font-bold hover:bg-green-600 hover:text-white transition text-[11px]">
                                                Arrived
                                            </button>
                                        </form>

                                        <!-- Butang No Show -->
                                        <form action="{{ route('salesperson.appointments.updateStatus', $apt->id ?? $apt->appointmentID) }}" method="POST" class="inline">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="status" value="No Show">
                                            <button type="submit" class="px-3 py-1 bg-rose-50 text-rose-700 rounded-lg font-bold hover:bg-rose-600 hover:text-white transition text-[11px]">
                                                No Show
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="p-6 text-center text-slate-400">No appointments in the queue.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Summary Cards (Orders & Sales) -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="bg-white p-6 rounded-2xl shadow-xs border border-slate-100">
                    <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-1">Orders Handled (Today)</p>
                    <h4 class="text-3xl font-black text-[#2e1065]">{{ $todayOrdersCount }}</h4>
                </div>

                <div class="bg-white p-6 rounded-2xl shadow-xs border border-slate-100">
                    <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-1">Total Sales (Today)</p>
                    <h4 class="text-3xl font-black text-[#2e1065]">RM {{ number_format($todaySales, 2) }}</h4>
                </div>
            </div>

        </div>
    </div>
@endsection
