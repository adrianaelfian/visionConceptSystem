@extends('layouts.customer')

@section('content')
<div class="py-10 max-w-3xl mx-auto px-4">
    <div class="bg-white p-8 rounded-2xl shadow-xs border border-slate-100">
        <!-- Header Halaman -->
        <div class="mb-6 border-b border-slate-100 pb-4 flex justify-between items-center">
            <div>
                <h2 class="text-2xl font-black text-[#2e1065]">Edit Appointment #APT-{{ $appointment->id ?? $appointment->appointmentID }}</h2>
                <p class="text-xs text-slate-500 mt-1">Update your scheduled date, time, or service type.</p>
            </div>
            <a href="{{ route('customer.dashboard') }}" class="px-3.5 py-2 bg-slate-100 text-slate-600 rounded-xl text-xs font-bold hover:bg-slate-200 transition">
                Back to Dashboard
            </a>
        </div>

        <!-- Paparan Ralat Validasi -->
        @if($errors->any())
            <div class="bg-rose-50 border border-rose-100 text-rose-700 px-4 py-3 rounded-xl text-xs font-bold mb-6">
                {{ $errors->first() }}
            </div>
        @endif

        <!-- Borang Kemaskini Temujanji -->
        <form action="{{ route('customer.appointments.update', $appointment->id ?? $appointment->appointmentID) }}" method="POST" class="space-y-5">
            @csrf
            @method('PUT')

            <!-- Tarikh Temujanji -->
            <div>
                <label class="block text-[11px] font-bold text-slate-600 uppercase mb-1">Appointment Date</label>
                <input type="date" name="appointmentDate" min="{{ date('Y-m-d') }}" value="{{ old('appointmentDate', isset($appointment->appointmentDate) ? \Carbon\Carbon::parse($appointment->appointmentDate)->format('Y-m-d') : ($appointment->appointmentDate ?? '')) }}" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:outline-none focus:border-[#3b127e] bg-white" required>
            </div>

            <!-- Masa Temujanji -->
            <div>
                <label class="block text-[11px] font-bold text-slate-600 uppercase mb-1">Appointment Time</label>
                <input type="time" name="appointmentTime" value="{{ old('appointmentTime', isset($appointment->appointmentTime) ? \Carbon\Carbon::parse($appointment->appointmentTime)->format('H:i') : '') }}" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:outline-none focus:border-[#3b127e] bg-white" required>
            </div>

            <!-- Sebab Temujanji (Service Type / Reason) -->
            <div>
                <label class="block text-[11px] font-bold text-slate-600 uppercase mb-1">Reason for Appointment</label>
                <select name="reasons" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:outline-none focus:border-[#3b127e] bg-white">
                    <option value="" disabled>-- Select a reason --</option>
                    <option value="Collection of glasses / Spectacles fitting" {{ old('reasons', $appointment->reasons ?? $appointment->reason ?? '') == 'Collection of glasses / Spectacles fitting' ? 'selected' : '' }}>Collection of glasses / Spectacles fitting</option>
                    <option value="Eye test checkup" {{ old('reasons', $appointment->reasons ?? $appointment->reason ?? '') == 'Eye test checkup' ? 'selected' : '' }}>Eye test checkup</option>
                    <option value="Consultation" {{ old('reasons', $appointment->reasons ?? $appointment->reason ?? '') == 'Consultation' ? 'selected' : '' }}>Consultation</option>
                </select>
            </div>

            <!-- Butang Tindakan -->
            <div class="pt-4 flex justify-end space-x-3 border-t border-slate-100">
                <a href="{{ route('customer.dashboard') }}" class="px-5 py-2.5 border border-slate-200 text-slate-600 rounded-xl text-xs font-bold hover:bg-slate-50 transition">Cancel</a>
                <button type="submit" class="bg-[#6d28d9] text-white px-6 py-2.5 rounded-xl text-xs font-bold hover:bg-purple-800 transition shadow-xs">
                    Update Appointment
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
