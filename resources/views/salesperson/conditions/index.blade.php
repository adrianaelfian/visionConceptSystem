@extends('layouts.salesperson')

@section('content')
    <div class="py-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <!-- Header & Back Button -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-8 gap-4">
            <div>
                <h1 class="text-3xl font-extrabold text-slate-800 tracking-tight">Eye Prescription Records</h1>
                <p class="text-slate-500 mt-1 flex items-center gap-2">
    <span>Viewing prescription history for: <span class="font-semibold text-purple-600">{{ $customer->customerName }}</span></span>
</p>
            </div>
            <div class="flex items-center gap-3">

                <a href="{{ route('salesperson.customer.index') }}" class="inline-flex items-center justify-center px-4 py-3 rounded-2xl bg-slate-100 hover:bg-slate-200 text-slate-600 font-semibold transition text-sm">
                    <i class="fa-solid fa-arrow-left mr-2"></i> Back
                </a>
            </div>
        </div>

        <!-- Table Card -->
        <div class="bg-white border border-slate-200 rounded-3xl shadow-xl shadow-purple-100/50 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50/70 border-b border-slate-100 text-[11px] font-bold text-slate-400 uppercase tracking-wider">
                            <th class="py-4 px-6">Date Recorded</th>
                            <th class="py-4 px-6">Right Power (SPH/CYL/AX)</th>
                            <th class="py-4 px-6">Left Power (SPH/CYL/AX)</th>
                            <th class="py-4 px-6">Pupil Distance (PD)</th>
                            <th class="py-4 px-6">Appointment ID</th>
                            <th class="py-4 px-6 text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-sm">
                        @forelse($conditions as $condition)
                            <tr class="hover:bg-purple-50/30 transition">
                                <td class="py-4 px-6 font-medium text-slate-800">
                                    {{ $condition->created_at->format('d M Y, h:i A') }}
                                </td>
                                <td class="py-4 px-6 text-slate-600">
                                    <span class="font-semibold">{{ $condition->rightPower }}</span> /
                                    <span>{{ $condition->rightCylinder }}</span> /
                                    <span>{{ $condition->rightAxis }}°</span>
                                </td>
                                <td class="py-4 px-6 text-slate-600">
                                    <span class="font-semibold">{{ $condition->leftPower }}</span> /
                                    <span>{{ $condition->leftCylinder }}</span> /
                                    <span>{{ $condition->leftAxis }}°</span>
                                </td>
                                <td class="py-4 px-6 text-slate-600 font-medium">
                                    {{ $condition->pupilDistance }} mm
                                </td>
                                <td class="py-4 px-6 text-slate-500 text-xs">
                                    {{ $condition->appointmentID ?? 'Walk-in' }}
                                </td>
                                <td class="py-4 px-6 text-center">
                                    <a href="{{ route('salesperson.conditions.edit', $condition->conditionID) }}" class="inline-flex items-center justify-center px-3 py-1.5 rounded-xl bg-amber-50 hover:bg-amber-100 text-amber-600 font-semibold transition text-xs">
                                        <i class="fa-solid fa-pen-to-square mr-1"></i> Edit
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-12 text-center text-slate-400">
                                    <div class="flex flex-col items-center justify-center space-y-2">
                                        <i class="fa-solid fa-glasses text-3xl text-slate-300"></i>
                                        <p class="text-sm font-medium">No eye prescription records found for this customer.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Skrip JavaScript untuk Kawal Modal -->
    <script>
        function openEditModal() {
            document.getElementById('editCustomerModal').classList.remove('hidden');
        }
        function closeEditModal() {
            document.getElementById('editCustomerModal').classList.add('hidden');
        }
    </script>
@endsection
