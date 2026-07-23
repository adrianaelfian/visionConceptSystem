@extends('layouts.admin')

@section('content')
    <div class="py-10">
        <div class="max-w-4xl mx-auto bg-white p-8 rounded-2xl shadow-xs border border-slate-100">
            <h2 class="text-2xl font-black text-[#2e1065] mb-6">Create New Appointment</h2>

            @if($errors->any())
                <div class="bg-rose-50 border border-rose-100 text-rose-700 px-4 py-3 rounded-xl text-xs font-bold mb-6">
                    {{ $errors->first() }}
                </div>
            @endif

            <form action="{{ route('admin.appointments.store') }}" method="POST" class="space-y-4">
                @csrf

                <div>
                    <label class="block text-[11px] font-bold text-slate-600 uppercase mb-1">Select Customer</label>
                    <select name="customerID" class="w-full px-3.5 py-2 rounded-xl border border-slate-200 text-xs focus:outline-none focus:border-[#3b127e] bg-white" required>
                        <option value="">-- Choose a customer --</option>
                        @foreach($customers as $customer)
                            <option value="{{ $customer->customerID }}">
                                {{ $customer->customerName }} ({{ $customer->customerEmail ?? 'No Email' }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-[11px] font-bold text-slate-600 uppercase mb-1">Appointment Date</label>
                    <input type="date" name="appointmentDate" class="w-full px-3.5 py-2 rounded-xl border border-slate-200 text-xs focus:outline-none focus:border-[#3b127e]" required>
                </div>

                <div>
                    <label class="block text-[11px] font-bold text-slate-600 uppercase mb-1">Appointment Time</label>
                    <input type="time" name="appointmentTime" class="w-full px-3.5 py-2 rounded-xl border border-slate-200 text-xs focus:outline-none focus:border-[#3b127e]" required>
                </div>

                <div class="pt-4 flex justify-end space-x-3">
                    <a href="{{ route('admin.appointments.index') }}" class="px-4 py-2.5 border border-slate-200 text-slate-600 rounded-xl text-xs font-bold hover:bg-slate-50 transition">Cancel</a>
                    <button type="submit" class="bg-[#6d28d9] text-white px-6 py-2.5 rounded-xl text-xs font-bold hover:bg-purple-800 transition shadow-xs">
                        Save Appointment
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
