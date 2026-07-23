@extends('layouts.salesperson')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="mb-6">
        <h1 class="text-2xl font-black text-[#2e1065]">Salesperson Dashboard</h1>
        <p class="text-xs text-slate-500 mt-0.5">Welcome, {{ auth()->user()->name }}. Manage your sales activities here.</p>
    </div>

    <!-- Quick Actions -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <!-- Register Customer -->
        <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm">
            <h2 class="text-sm font-bold text-slate-700 mb-4">Customer Actions</h2>
            <a href="{{ route('salesperson.customer.create') }}" class="block w-full text-center px-4 py-3 bg-sky-600 text-white rounded-xl text-sm font-bold hover:bg-sky-700 transition">
                <i class="fa-solid fa-user-plus mr-2"></i> Register New Customer
            </a>
        </div>

        <!-- Create Order -->
        <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm">
            <h2 class="text-sm font-bold text-slate-700 mb-4">Order Actions</h2>
            <a href="{{ route('salesperson.orders.create') }}" class="block w-full text-center px-4 py-3 bg-emerald-600 text-white rounded-xl text-sm font-bold hover:bg-emerald-700 transition">
                <i class="fa-solid fa-cart-plus mr-2"></i> Create New Order
            </a>
        </div>
    </div>

    <!-- Today's Appointment Queue -->
    <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm mt-6">
        <h2 class="text-xs font-bold text-[#3b127e] mb-4">Today's Appointment Queue</h2>

        <table class="w-full text-left text-xs">
            <thead class="text-slate-400 border-b border-slate-100">
                <tr>
                    <th class="pb-3">Customer Name</th>
                    <th class="pb-3">Time</th>
                    <th class="pb-3 text-right">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($todayAppointments as $appointment)
                <tr class="border-b border-slate-50">
                    <td class="py-4 font-semibold text-slate-700">{{ $appointment->customer->name ?? 'N/A' }}</td>
                    <td class="py-4">{{ $appointment->appointmentTime }}</td>
                    <td class="py-4 text-right">
                        <a href="{{ route('salesperson.conditions.create', ['appointmentID' => $appointment->id]) }}"
                            class="text-purple-600 hover:underline font-bold">
                            Add Condition
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="3" class="py-4 text-center text-slate-400">No confirmed appointments for today.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Sales Statistics -->
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm">
            <span class="text-[10px] font-bold uppercase text-slate-400">Orders Handled (Today)</span>
            <div class="text-2xl font-black mt-2 text-slate-800">{{ $todayOrdersCount ?? 0 }}</div>
        </div>
        <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm">
            <span class="text-[10px] font-bold uppercase text-slate-400">Total Sales (Today)</span>
            <div class="text-2xl font-black mt-2 text-slate-800">RM {{ number_format($todaySales ?? 0, 2) }}</div>
        </div>
    </div>
</div>
@endsection
