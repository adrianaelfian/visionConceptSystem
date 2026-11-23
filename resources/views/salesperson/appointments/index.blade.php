@extends('layouts.salesperson')

@section('content')
    <div class="py-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

        <!-- Tajuk Header Halaman -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 bg-white p-6 rounded-3xl border border-purple-100 shadow-sm">
            <div>
                <span class="text-[10px] font-extrabold uppercase tracking-widest text-[#6d28d9] bg-purple-50 px-3 py-1 rounded-full border border-purple-100">
                    Salesperson Portal
                </span>
                <h1 class="text-2xl font-black text-slate-900 mt-2">Appointment Management</h1>
                <p class="text-xs text-slate-500">View and manage all customer eye examination schedules.</p>
            </div>

            <!-- Borang Tapisan (Filter) Mengikut Tarikh atau Status -->
            <form method="GET" action="{{ route('salesperson.appointments.index') }}" class="flex flex-wrap items-center gap-2 w-full sm:w-auto">
                <input type="date" name="date" value="{{ request('date') }}"
                    class="text-xs rounded-xl border-slate-200 bg-slate-50 py-2 px-3 focus:outline-none focus:border-[#3b127e]">

                <select name="status" class="text-xs rounded-xl border-slate-200 bg-slate-50 py-2 px-3 focus:outline-none focus:border-[#3b127e]">
                    <option value="">All Statuses</option>
                    <option value="Arrived" {{ request('status') == 'Arrived' ? 'selected' : '' }}>Arrived</option>
                    <option value="Completed" {{ request('status') == 'Completed' ? 'selected' : '' }}>Completed</option>
                    <option value="Cancelled" {{ request('status') == 'Cancelled' ? 'selected' : '' }}>Cancelled</option>
                    <option value="No Show" {{ request('status') == 'No Show' ? 'selected' : '' }}>No Show</option>
                </select>

                <button type="submit" class="px-4 py-2 bg-[#3b127e] text-white rounded-xl text-xs font-bold hover:bg-purple-900 transition">
                    Filter
                </button>
                @if(request('date') || request('status'))
                    <a href="{{ route('salesperson.appointments.index') }}" class="px-3 py-2 bg-slate-100 text-slate-600 rounded-xl text-xs font-bold hover:bg-slate-200 transition">
                        Reset
                    </a>
                @endif
            </form>
        </div>

        <!-- Mesej Kejayaan -->
        @if(session('success'))
            <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs rounded-2xl font-medium flex items-center gap-2">
                <i class="fa-solid fa-circle-check text-emerald-600"></i> {{ session('success') }}
            </div>
        @endif

        <!-- Jadual Senarai Temujanji -->
        <div class="bg-white rounded-3xl shadow-sm border border-purple-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-purple-50/50 text-[11px] font-extrabold text-slate-600 uppercase tracking-wider border-b border-purple-100">
                            <th class="py-4 px-6">Customer ID</th>
                            <th class="py-4 px-6">Customer Name</th>
                            <th class="py-4 px-6">Date & Time</th>
                            <th class="py-4 px-6">Current Status</th>
                            <th class="py-4 px-6 text-right">Update Status Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-xs font-medium text-slate-700">
                        @forelse($appointments as $appointment)
                            <tr class="hover:bg-slate-50/50 transition">
                                <td class="py-4 px-6">
                                    <div class="flex items-center gap-2">
        <span class="text-[10px] font-bold text-purple-600 bg-purple-50 px-2 py-0.5 rounded-md border border-purple-100">
            ID: {{ $appointment->customerID ?? ($appointment->customer->id ?? 'N/A') }}
        </span>
    </div>
                                </td>
                                <td class="py-4 px-6">
    <div class="font-bold text-slate-900 mt-1">{{ $appointment->customer->customerName ?? 'N/A' }}</div>
    <div class="text-[11px] text-slate-400">{{ $appointment->customer->customerEmail ?? '' }}</div>
</td>
                                <td class="py-4 px-6">
                                    <div class="font-semibold text-slate-800">{{ $appointment->appointmentDate }}</div>
                                    <div class="text-[11px] text-slate-400">{{ $appointment->appointmentTime }}</div>
                                </td>
                                <td class="py-4 px-6">
                                    <span class="px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider
                                        {{ $appointment->status == 'Completed' ? 'bg-emerald-100 text-emerald-700' :
                                          ($appointment->status == 'Cancelled' ? 'bg-rose-100 text-rose-700' :
                                          ($appointment->status == 'Arrived' ? 'bg-sky-100 text-sky-700' : 'bg-amber-100 text-amber-700')) }}">
                                        {{ $appointment->status }}
                                    </span>
                                </td>
                                <td class="py-4 px-6 text-right">
                                    <!-- Borang Kemas Kini Status Terus dari Jadual -->
                                    <form action="{{ route('salesperson.appointments.updateStatus', ['id' => $appointment->appointmentID]) }}" method="POST" class="inline-flex items-center justify-end gap-2">
                                        @csrf
                                        @method('PATCH')
                                        <select name="status" onchange="this.form.submit()" class="text-xs rounded-xl border-slate-200 py-1.5 px-3 focus:ring-[#3b127e] focus:border-[#3b127e] bg-slate-50 font-semibold text-slate-700 cursor-pointer">
                                            <option value="Arrived" {{ $appointment->status == 'Arrived' ? 'selected' : '' }}>Arrived</option>
                                            <option value="Completed" {{ $appointment->status == 'Completed' ? 'selected' : '' }}>Completed</option>
                                            <option value="Cancelled" {{ $appointment->status == 'Cancelled' ? 'selected' : '' }}>Cancelled</option>
                                            <option value="No Show" {{ $appointment->status == 'No Show' ? 'selected' : '' }}>No Show</option>
                                        </select>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="py-12 text-center text-slate-400 text-xs">
                                    <div class="flex flex-col items-center justify-center space-y-2">
                                        <i class="fa-regular fa-calendar-xmark text-2xl text-slate-300"></i>
                                        <p>No appointments found.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pautan Halaman (Pagination) -->
            @if($appointments->hasPages())
                <div class="p-4 border-t border-slate-100 bg-slate-50/50">
                    {{ $appointments->withQueryString()->links() }}
                </div>
            @endif
        </div>

    </div>
@endsection
