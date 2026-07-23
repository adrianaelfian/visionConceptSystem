@extends('layouts.admin')

@section('content')
    <div class="space-y-6">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-black text-[#2e1065]">Manage Appointments</h1>
                <p class="text-xs text-slate-500 mt-0.5">View and manage all customer appointment schedules.</p>
            </div>
            <a href="{{ route('admin.appointments.create') }}" class="bg-[#6d28d9] hover:bg-purple-800 text-white px-5 py-2.5 rounded-xl text-xs font-bold transition shadow-xs flex items-center space-x-2">
                <i class="fa-solid fa-calendar-plus"></i>
                <span>+ New Appointment</span>
            </a>
        </div>

        @if(session('success'))
            <div class="bg-emerald-50 border border-emerald-100 text-emerald-700 px-4 py-3 rounded-xl text-xs font-bold">
                {{ session('success') }}
            </div>
        @endif

        <div class="bg-white p-6 rounded-2xl shadow-xs border border-slate-100">
            <table class="w-full text-left text-xs border-collapse">
                <thead>
                    <tr class="text-slate-400 border-b border-slate-100 uppercase text-[10px] tracking-wider">
                        <th class="py-4 px-4 font-bold">Appt ID</th>
                        <th class="py-4 px-4 font-bold">Customer Name</th>
                        <th class="py-4 px-4 font-bold">Date</th>
                        <th class="py-4 px-4 font-bold">Time</th>
                        <th class="py-4 px-4 font-bold">Status</th>
                        <th class="py-4 px-4 font-bold text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="text-slate-700 divide-y divide-slate-50 font-medium">
                    @forelse($appointments as $appt)
                    <tr class="hover:bg-slate-50/50 transition">
                        <td class="py-4 px-4 font-bold text-slate-900">#APT-{{ $appt->appointmentID ?? $appt->id }}</td>
                        <td class="py-4 px-4 text-slate-800 font-bold">{{ $appt->customer->name ?? $appt->customer->customerName ?? 'N/A' }}</td>
                        <td class="py-4 px-4 text-slate-600">{{ $appt->appointmentDate }}</td>
                        <td class="py-4 px-4 text-slate-600">{{ $appt->appointmentTime }}</td>
                        <td class="py-4 px-4">
                            <span class="px-2.5 py-1 rounded-full text-[10px] font-extrabold
                                {{ $appt->status == 'Completed' ? 'bg-emerald-50 text-emerald-600' : 'bg-purple-50 text-[#6d28d9]' }}">
                                {{ $appt->status }}
                            </span>
                        </td>
                        <td class="py-4 px-4 text-right flex justify-end items-center space-x-3">
                            <a href="{{ route('admin.appointments.edit', $appt->appointmentID ?? $appt->id) }}" class="text-purple-600 font-bold hover:text-purple-800">
                                EDIT
                            </a>
                            <form action="{{ route('admin.appointments.destroy', $appt->appointmentID ?? $appt->id) }}" method="POST"
                                  onsubmit="return confirm('Are you sure you want to cancel this appointment?');" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-rose-600 font-bold hover:text-rose-800">
                                    CANCEL
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="p-12 text-center text-slate-400 font-medium">
                            <i class="fa-solid fa-calendar-xmark block text-2xl mb-2 text-slate-300"></i>
                            No appointment records found.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
