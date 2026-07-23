@extends('layouts.salesperson')

@section('content')
    <div class="py-10">
        <div class="max-w-4xl mx-auto bg-white p-8 rounded-2xl shadow-xs border border-slate-100">

            <!-- Header & Maklumat Pelanggan -->
            <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6 gap-4">
                <div>
                    <a href="{{ route('salesperson.customer.index') }}" class="inline-flex items-center text-xs font-semibold text-slate-500 hover:text-[#3b127e] transition mb-2">
                        <i class="fa-solid fa-arrow-left mr-1.5"></i> Back to Customer Directory
                    </a>
                    <h1 class="text-2xl font-black text-[#2e1065]">Eye Condition Record</h1>
                </div>

                <!-- Kad Paparan Nama Pelanggan -->
                @if(isset($customer))
                    <div class="bg-purple-50/70 border border-purple-100 px-4 py-2.5 rounded-xl flex items-center space-x-3">
                        <div class="w-9 h-9 rounded-lg bg-[#6d28d9] text-white flex items-center justify-center font-bold text-xs">
                            {{ substr($customer->customerName, 0, 1) }}
                        </div>
                        <div>
                            <p class="text-[10px] text-purple-700 font-extrabold uppercase tracking-wider">Patient / Client</p>
                            <p class="text-xs font-bold text-slate-800">{{ $customer->customerName }} <span class="text-slate-500 font-normal">({{ $customer->customerPhoneNumber }})</span></p>
                        </div>
                    </div>
                @endif
            </div>

            @if($errors->any())
                <div class="bg-rose-50 border border-rose-100 text-rose-700 px-4 py-3 rounded-xl text-xs font-bold mb-6">
                    {{ $errors->first() }}
                </div>
            @endif

            <form action="{{ route('salesperson.conditions.store') }}" method="POST" class="space-y-6">
                @csrf

                <!-- Input Tersembunyi untuk ID Pelanggan / Temujanji -->
                @if(isset($customer))
                    <input type="hidden" name="customer_id" value="{{ $customer->id }}">
                @endif
                @if(isset($appointmentID))
                    <input type="hidden" name="appointmentID" value="{{ $appointmentID }}">
                @endif

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Right Eye -->
                    <div class="border border-slate-100 p-5 rounded-2xl bg-slate-50/50 space-y-4">
                        <h3 class="font-extrabold text-xs uppercase tracking-wider text-[#3b127e] flex items-center space-x-2">
                            <i class="fa-solid fa-eye"></i>
                            <span>Right Eye (OD)</span>
                        </h3>
                        <div>
                            <label class="block text-[11px] font-bold text-slate-600 uppercase mb-1">Power (SPH):</label>
                            <input type="number" step="0.25" name="rightPower" class="w-full px-3.5 py-2 rounded-xl border border-slate-200 text-xs focus:outline-none focus:border-[#3b127e] bg-white" placeholder="e.g. -1.50" required>
                        </div>
                        <div>
                            <label class="block text-[11px] font-bold text-slate-600 uppercase mb-1">Cylinder:</label>
                            <input type="number" step="0.25" name="rightCylinder" class="w-full px-3.5 py-2 rounded-xl border border-slate-200 text-xs focus:outline-none focus:border-[#3b127e] bg-white" placeholder="e.g. -0.50" required>
                        </div>
                        <div>
                            <label class="block text-[11px] font-bold text-slate-600 uppercase mb-1">Axis (°):</label>
                            <input type="number" name="rightAxis" class="w-full px-3.5 py-2 rounded-xl border border-slate-200 text-xs focus:outline-none focus:border-[#3b127e] bg-white" placeholder="e.g. 180" required>
                        </div>
                    </div>

                    <!-- Left Eye -->
                    <div class="border border-slate-100 p-5 rounded-2xl bg-slate-50/50 space-y-4">
                        <h3 class="font-extrabold text-xs uppercase tracking-wider text-[#3b127e] flex items-center space-x-2">
                            <i class="fa-solid fa-eye"></i>
                            <span>Left Eye (OS)</span>
                        </h3>
                        <div>
                            <label class="block text-[11px] font-bold text-slate-600 uppercase mb-1">Power (SPH):</label>
                            <input type="number" step="0.25" name="leftPower" class="w-full px-3.5 py-2 rounded-xl border border-slate-200 text-xs focus:outline-none focus:border-[#3b127e] bg-white" placeholder="e.g. -1.75" required>
                        </div>
                        <div>
                            <label class="block text-[11px] font-bold text-slate-600 uppercase mb-1">Cylinder:</label>
                            <input type="number" step="0.25" name="leftCylinder" class="w-full px-3.5 py-2 rounded-xl border border-slate-200 text-xs focus:outline-none focus:border-[#3b127e] bg-white" placeholder="e.g. -0.75" required>
                        </div>
                        <div>
                            <label class="block text-[11px] font-bold text-slate-600 uppercase mb-1">Axis (°):</label>
                            <input type="number" name="leftAxis" class="w-full px-3.5 py-2 rounded-xl border border-slate-200 text-xs focus:outline-none focus:border-[#3b127e] bg-white" placeholder="e.g. 90">
                        </div>
                    </div>
                </div>

                <div>
                    <label class="block text-[11px] font-bold text-slate-600 uppercase mb-1">Pupil Distance (PD in mm):</label>
                    <input type="number" step="0.5" name="pupilDistance" class="w-full px-3.5 py-2 rounded-xl border border-slate-200 text-xs focus:outline-none focus:border-[#3b127e]" placeholder="e.g. 63.5" required>
                </div>

                <div class="pt-6 border-t border-slate-100 flex flex-col sm:flex-row justify-end gap-3">
                    <button type="submit" name="action" value="save_only" class="px-5 py-2.5 border border-slate-200 text-slate-600 rounded-xl text-xs font-bold hover:bg-slate-50 transition">
                        Save Only
                    </button>
                    <button type="submit" name="action" value="save_and_order" class="px-6 py-2.5 bg-[#6d28d9] text-white rounded-xl text-xs font-bold hover:bg-purple-800 transition shadow-xs flex items-center justify-center space-x-2">
                        <i class="fa-solid fa-cart-shopping text-xs"></i>
                        <span>Save & Proceed to Order</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
