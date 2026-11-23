@extends('layouts.salesperson')

@section('content')
    <div class="py-10 max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white border border-slate-200 rounded-3xl shadow-xl p-8">
            <h1 class="text-2xl font-extrabold text-slate-800 mb-6">Edit Eye Prescription</h1>

            <form action="{{ route('salesperson.conditions.update', $condition->conditionID) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label class="block text-xs font-bold text-slate-600 uppercase mb-2">Right Power (SPH)</label>
                        <input type="text" name="rightPower" value="{{ old('rightPower', $condition->rightPower) }}" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:border-purple-500 focus:ring-4 focus:ring-purple-500/10 outline-none text-sm" required>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-600 uppercase mb-2">Left Power (SPH)</label>
                        <input type="text" name="leftPower" value="{{ old('leftPower', $condition->leftPower) }}" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:border-purple-500 focus:ring-4 focus:ring-purple-500/10 outline-none text-sm" required>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-600 uppercase mb-2">Right Cylinder (CYL)</label>
                        <input type="text" name="rightCylinder" value="{{ old('rightCylinder', $condition->rightCylinder) }}" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:border-purple-500 focus:ring-4 focus:ring-purple-500/10 outline-none text-sm">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-600 uppercase mb-2">Left Cylinder (CYL)</label>
                        <input type="text" name="leftCylinder" value="{{ old('leftCylinder', $condition->leftCylinder) }}" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:border-purple-500 focus:ring-4 focus:ring-purple-500/10 outline-none text-sm">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-600 uppercase mb-2">Right Axis (°)</label>
                        <input type="number" name="rightAxis" value="{{ old('rightAxis', $condition->rightAxis) }}" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:border-purple-500 focus:ring-4 focus:ring-purple-500/10 outline-none text-sm">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-600 uppercase mb-2">Left Axis (°)</label>
                        <input type="number" name="leftAxis" value="{{ old('leftAxis', $condition->leftAxis) }}" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:border-purple-500 focus:ring-4 focus:ring-purple-500/10 outline-none text-sm">
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-xs font-bold text-slate-600 uppercase mb-2">Pupil Distance (PD)</label>
                        <input type="text" name="pupilDistance" value="{{ old('pupilDistance', $condition->pupilDistance) }}" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:border-purple-500 focus:ring-4 focus:ring-purple-500/10 outline-none text-sm" required>
                    </div>
                </div>

                <div class="flex justify-end gap-3">
                    <a href="{{ route('salesperson.conditions.index', ['customer_id' => $condition->customer_id]) }}" class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-600 font-semibold rounded-xl text-sm transition">
                        Cancel
                    </a>
                    <button type="submit" class="px-6 py-2.5 bg-purple-600 hover:bg-purple-700 text-white font-semibold rounded-xl text-sm transition shadow-md shadow-purple-200">
                        Update Prescription
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
