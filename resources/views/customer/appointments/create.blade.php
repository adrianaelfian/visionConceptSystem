@extends('layouts.customer')

@section('content')
    <div class="py-10 max-w-3xl mx-auto px-4 space-y-8">

        <!-- Borang Tempahan -->
        <div class="bg-white p-8 rounded-2xl shadow-xs border border-slate-100">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-2xl font-black text-[#2e1065]">Book an Appointment</h2>
                <a href="{{ route('customer.dashboard') }}" class="text-xs font-bold text-[#6d28d9] hover:text-purple-900 transition">
                    &larr; Back to Dashboard
                </a>
            </div>

            <!-- Mesej Berjaya -->
            @if (session('success'))
                <div class="mb-5 p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-700 text-xs font-semibold">
                    {{ session('success') }}
                    <a href="#my-bookings" class="underline font-bold ml-1">View my bookings</a>
                </div>
            @endif

            <!-- Mesej Ralat Global -->
            @if ($errors->any())
                <div class="mb-5 p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-700 text-xs font-semibold">
                    <ul class="list-disc pl-4 space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('customer.appointments.store') }}" method="POST" class="space-y-5" id="appointmentForm">
                @csrf

                <!-- Pilih Tarikh -->
                <div>
                    <label class="block text-[11px] font-bold text-slate-600 uppercase mb-1">Select Date</label>
                    <input type="date" id="appointmentDate" name="appointmentDate" min="{{ date('Y-m-d') }}" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:outline-none focus:border-[#3b127e] bg-white" required>
                </div>

                <!-- Pilih Masa (Dropdown yang dikemas kini oleh AJAX) -->
                <div>
                    <label class="block text-[11px] font-bold text-slate-600 uppercase mb-1">Preferred Time Slot</label>
                    <select id="appointmentTime" name="appointmentTime" required class="w-full px-3.5 py-2.5 rounded-xl border @error('appointmentTime') border-rose-500 @else border-slate-200 @enderror text-xs focus:outline-none focus:border-[#3b127e] bg-white">
                        <option value="" disabled selected>-- Please Choose Your Booking Date --</option>
                    </select>

                    <!-- 🔴 Mesej Ralat Spesifik untuk Masa Temujanji -->
                    @error('appointmentTime')
                        <p class="text-[10px] font-semibold text-rose-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Sebab Temujanji -->
                <div>
                    <label class="block text-[11px] font-bold text-slate-600 uppercase mb-1">Reason for Appointment</label>
                    <select name="reasons" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:outline-none focus:border-[#3b127e] bg-white">
                        <option value="" disabled selected>-- Select a reason --</option>
                        <option value="Collection of glasses / Spectacles fitting">Collection of glasses / Spectacles fitting</option>
                        <option value="Eye test checkup">Eye test checkup</option>
                        <option value="Consultation">Consultation</option>
                    </select>
                </div>

                <div class="pt-4 flex justify-end items-center gap-3">
                    <a href="{{ route('customer.dashboard') }}" class="px-5 py-2.5 rounded-xl text-xs font-bold text-slate-600 bg-slate-100 hover:bg-slate-200 transition">
                        Back to Dashboard
                    </a>
                    <button type="submit" class="bg-[#6d28d9] text-white px-6 py-2.5 rounded-xl text-xs font-bold hover:bg-purple-800 transition shadow-xs">
                        Confirm & Book Appointment
                    </button>
                </div>
            </form>
        </div>

        <!-- Senarai Temujanji Pelanggan -->
        <div id="my-bookings" class="bg-white p-8 rounded-2xl shadow-xs border border-slate-100 mt-8">
            <h3 class="text-lg font-black text-[#2e1065] mb-4">My Booked Appointments</h3>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b border-slate-100 text-slate-400 text-[10px] font-bold uppercase tracking-wider">
                            <th class="pb-3 px-2">Date</th>
                            <th class="pb-3 px-2">Time</th>
                            <th class="pb-3 px-2">Reason</th>
                            <th class="pb-3 px-2 text-center">Status</th>
                            <th class="pb-3 px-2 text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="text-xs divide-y divide-slate-50 text-slate-600">
                        @forelse ($appointments as $appointment)
                            <tr class="hover:bg-slate-50/50 transition">
                                <td class="py-3 px-2 font-semibold text-slate-700">
                                    {{ \Carbon\Carbon::parse($appointment->appointmentDate)->format('d M Y') }}
                                </td>
                                <td class="py-3 px-2">
                                    {{ \Carbon\Carbon::parse($appointment->appointmentTime)->format('h:i A') }}
                                </td>
                                <td class="py-3 px-2">{{ $appointment->reason }}</td>
                                <td class="py-3 px-2 text-center">
                                    @php
                                        $badge = match ($appointment->status) {
                                            'Completed' => 'bg-emerald-100 text-emerald-700',
                                            'Cancelled' => 'bg-rose-100 text-rose-700',
                                            default     => 'bg-purple-100 text-purple-700',
                                        };
                                    @endphp
                                    <span class="px-2.5 py-1 rounded-full text-[10px] font-bold {{ $badge }}">
                                        {{ $appointment->status }}
                                    </span>
                                </td>
                                <td class="py-3 px-2 text-center">
                                    @if($appointment->status === 'Pending')
                                        <div class="flex items-center justify-center space-x-2">
                                            <a href="{{ route('customer.appointments.edit', $appointment->appointmentID ?? $appointment->id) }}" class="px-2.5 py-1 bg-purple-50 text-[#6d28d9] hover:bg-purple-100 rounded-lg font-bold text-[10px] transition" title="Edit Appointment">
            Edit
        </a>
                                            <!-- Butang Batal (Cancel) -->
                                            <form action="{{ route('customer.appointments.cancel', $appointment->appointmentID ?? $appointment->id) }}" method="POST" onsubmit="return confirm('Do you confirm to cancel order?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="px-2.5 py-1 bg-rose-50 text-rose-600 hover:bg-rose-100 rounded-lg font-bold text-[10px] transition" title="Cancel Appointment">
                                                    Cancel
                                                </button>
                                            </form>
                                        </div>
                                    @else
                                        <span class="text-slate-400 text-[10px] italic">No action</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-6 text-center text-slate-400">You have no appointments yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Skrip AJAX & Validasi Masa -->
    <script>
        document.getElementById('appointmentDate').addEventListener('change', function() {
            let selectedDate = this.value;
            let timeSelect = document.getElementById('appointmentTime');

            timeSelect.innerHTML = '<option value="" disabled selected>Loading time slots...</option>';

            fetch(`{{ route('customer.appointments.slots') }}?date=${selectedDate}`)
                .then(response => response.json())
                .then(data => {
                    timeSelect.innerHTML = '<option value="" disabled selected>-- Choose Time Slot --</option>';

                    for (let [time, status] of Object.entries(data)) {
                        let formattedTime = time.substring(0, 5);
                        let option = document.createElement('option');
                        option.value = time;

                        if (status === 'booked') {
                            option.disabled = true;
                            option.textContent = `${formattedTime} (Fully Booked / Expired)`;
                            option.className = "bg-slate-100 text-slate-400";
                        } else {
                            option.textContent = `${formattedTime} (Available)`;
                        }
                        timeSelect.appendChild(option);
                    }
                })
                .catch(error => {
                    console.error('Ralat:', error);
                    timeSelect.innerHTML = '<option value="" disabled selected>Failed to load time slots.</option>';
                });
        });

        // Tambahan Validasi Frontend Semasa Submit Borang
        document.getElementById('appointmentForm').addEventListener('submit', function(e) {
            let dateInput = document.getElementById('appointmentDate').value;
            let timeSelect = document.getElementById('appointmentTime').value;

            if (dateInput && timeSelect) {
                let selectedDateTime = new Date(`${dateInput}T${timeSelect}`);
                let now = new Date();

                let todayStr = now.toISOString().split('T')[0];
                if (dateInput === todayStr && selectedDateTime < now) {
                    e.preventDefault();
                    alert('Ralat: Anda tidak boleh menempah slot masa yang telah lepas untuk hari ini.');
                }
            }
        });
    </script>
@endsection
