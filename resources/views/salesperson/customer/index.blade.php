@extends('layouts.salesperson')

@section('content')
    <div class="py-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <!-- Header & Button Register -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-8 gap-4">
            <div>
                <h1 class="text-3xl font-extrabold text-slate-800 tracking-tight">Customer Directory</h1>
                <p class="text-slate-500 mt-1">Manage and view all registered clients in the system.</p>
            </div>
            <a href="{{ route('salesperson.customer.create') }}" class="inline-flex items-center justify-center space-x-2 px-5 py-3 rounded-2xl bg-gradient-to-r from-purple-600 to-indigo-600 text-white font-bold shadow-lg shadow-indigo-200 hover:from-purple-700 hover:to-indigo-700 transition">
                <i class="fa-solid fa-user-plus text-xs"></i>
                <span>Register New Customer</span>
            </a>
        </div>

        <!-- Search Bar & Filter Card -->
        <div class="bg-white/80 backdrop-blur-md border border-slate-200 p-4 rounded-2xl shadow-sm mb-6">
            <form action="{{ route('salesperson.customer.index') }}" method="GET" class="flex items-center gap-4">
                <div class="relative flex-1">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400">
                        <i class="fa-solid fa-search text-xs"></i>
                    </span>
                    <input type="text" name="search" value="{{ $search ?? '' }}" placeholder="Search by customer name or phone number..." class="w-full pl-11 pr-4 py-2.5 rounded-xl border border-slate-200 focus:border-purple-500 focus:ring-4 focus:ring-purple-500/10 transition outline-none text-sm">
                </div>
                <button type="submit" class="px-5 py-2.5 bg-slate-800 hover:bg-slate-900 text-white font-semibold rounded-xl text-sm transition shadow-sm">
                    Search
                </button>
                @if(request('search'))
                    <a href="{{ route('salesperson.customer.index') }}" class="px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-600 font-semibold rounded-xl text-sm transition">
                        Reset
                    </a>
                @endif
            </form>
        </div>

        <!-- Table Card -->
        <div class="bg-white border border-slate-200 rounded-3xl shadow-xl shadow-purple-100/50 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50/70 border-b border-slate-100 text-[11px] font-bold text-slate-400 uppercase tracking-wider">
                            <th class="py-4 px-6">Customer Name</th>
                            <th class="py-4 px-6">Email Address</th>
                            <th class="py-4 px-6">Phone Number</th>
                            <th class="py-4 px-6">Registered Date</th>
                            <th class="py-4 px-6 text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-sm">
                        @forelse($customers as $customer)
                            <tr class="hover:bg-purple-50/30 transition">
                                <td class="py-4 px-6 font-bold text-slate-800">
                                    {{ $customer->customerName }}
                                </td>
                                <td class="py-4 px-6 text-slate-600">
                                    {{ $customer->customerEmail }}
                                </td>
                                <td class="py-4 px-6 text-slate-600 font-medium">
                                    {{ $customer->customerPhoneNumber }}
                                </td>
                                <td class="py-4 px-6 text-slate-500 text-xs">
                                    {{ $customer->created_at->format('d M Y, h:i A') }}
                                </td>
                                <td class="py-4 px-6 text-center space-x-2">
    <!-- Butang Pantas ke Eye Condition dengan menghantar ID pelanggan -->
    <a href="{{ route('salesperson.conditions.create', ['customer_id' => $customer->id]) }}" class="inline-flex items-center px-3 py-1.5 bg-indigo-50 hover:bg-indigo-100 text-indigo-700 rounded-lg font-semibold text-xs transition" title="Add Eye Condition for this customer">
        <i class="fa-solid fa-eye mr-1.5"></i> Eye Condition
    </a>

    <!-- Butang Pantas ke Create Order dengan menghantar ID pelanggan -->
    <a href="{{ route('salesperson.orders.create', ['customer_id' => $customer->id]) }}" class="inline-flex items-center px-3 py-1.5 bg-purple-50 hover:bg-purple-100 text-purple-700 rounded-lg font-semibold text-xs transition" title="Create Order for this customer">
        <i class="fa-solid fa-cart-plus mr-1.5"></i> Order
    </a>
</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-12 text-center text-slate-400">
                                    <div class="flex flex-col items-center justify-center space-y-2">
                                        <i class="fa-solid fa-folder-open text-3xl text-slate-300"></i>
                                        <p class="text-sm font-medium">No customers found.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination Links -->
            @if($customers->hasPages())
                <div class="p-4 border-t border-slate-100 bg-slate-50/50">
                    {{ $customers->withQueryString()->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
