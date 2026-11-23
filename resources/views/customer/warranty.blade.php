@extends('layouts.customer')

@section('content')
<div class="max-w-4xl mx-auto py-8 space-y-6">
    <!-- Header Halaman -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black text-[#2e1065]">My Warranties</h1>
            <p class="text-xs text-slate-500 mt-0.5">View your 2-year frame and lens warranty coverage and validity status.</p>
        </div>
        <a href="{{ route('customer.dashboard') }}" class="px-4 py-2 bg-slate-100 text-slate-600 rounded-xl text-xs font-bold hover:bg-slate-200 transition flex items-center justify-center space-x-2">
            <i class="fa-solid fa-arrow-left text-xs"></i>
            <span>Back to Dashboard</span>
        </a>
    </div>

    <!-- Senarai Waranti -->
    <div class="bg-white rounded-2xl border border-slate-100 shadow-xs p-6 space-y-4">
        <h3 class="text-xs font-black text-[#3b127e] uppercase tracking-wider">Active 2-Year Coverage</h3>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            @forelse($warranties ?? [] as $warranty)
                <div class="p-4 bg-purple-50/60 border border-purple-100 rounded-xl space-y-3">
                    <div class="flex justify-between items-center">
                        <span class="text-xs font-bold text-[#2e1065]">{{ $warranty->warranty_code }}</span>
                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold {{ $warranty->status == 'Active' ? 'bg-green-100 text-green-700' : 'bg-slate-100 text-slate-600' }}">
                            {{ $warranty->status }}
                        </span>
                    </div>

                    <div class="space-y-1 text-xs text-slate-600">
                        <p><strong>Order ID:</strong> #ORD-{{ $warranty->order_id }}</p>
                        <p><strong>Coverage:</strong> {{ $warranty->coverage_details ?? '2-Year Full Frame & Lens Coating Warranty' }}</p>
                    </div>

                    <div class="text-[10px] text-slate-400 flex justify-between border-t border-purple-100 pt-2 font-medium">
                        <span>Started: {{ \Carbon\Carbon::parse($warranty->start_date)->format('d/m/Y') }}</span>
                        <span class="text-purple-700 font-bold">Expires: {{ \Carbon\Carbon::parse($warranty->end_date)->format('d/m/Y') }}</span>
                    </div>
                </div>
            @empty
                <div class="col-span-2 p-8 text-center text-slate-400 text-xs italic">
                    No active warranty records found for your account. Warranties are automatically generated upon order completion.
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection
