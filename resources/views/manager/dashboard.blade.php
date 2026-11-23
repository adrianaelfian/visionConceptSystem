@extends('layouts.manager')

@section('content')
<div class="p-6 space-y-6 max-w-7xl mx-auto">
    <!-- Header Tajuk Dashboard -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold text-slate-800 tracking-tight">Manager Dashboard</h2>
            <p class="text-sm text-slate-500 mt-1">Welcome back! Here is your business performance overview today.</p>
        </div>
    </div>

    <!-- 🛑 BAHAGIAN NOTIFIKASI LOW STOCK (Letak di sini) -->
    @if(isset($lowStockItems) && $lowStockItems->count() > 10)
        <div class="bg-amber-50 border border-amber-200 rounded-2xl p-4 md:p-5 shadow-xs space-y-3">
            <div class="flex items-center space-x-2 text-amber-800">
                <i class="fa-solid fa-triangle-exclamation text-base"></i>
                <h3 class="text-xs font-black uppercase tracking-wider">Inventory Stock Alert (Low / Out of Stock)</h3>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-2.5">
                @foreach($lowStockItems as $item)
                    <div class="bg-white p-3 rounded-xl border border-amber-100 flex items-center justify-between text-xs shadow-xs">
                        <span class="font-bold text-slate-700 truncate max-w-[150px]">{{ $item->name }}</span>
                        @if($item->stock == 0)
                            <span class="px-2 py-0.5 bg-rose-100 text-rose-700 font-black rounded-md text-[10px] uppercase">
                                Out of Stock (0)
                            </span>
                        @else
                            <span class="px-2 py-0.5 bg-amber-100 text-amber-800 font-bold rounded-md text-[10px]">
                                Low: {{ $item->stock }} left
                            </span>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <!-- Stat Kad (3 Grid) -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200/80 hover:shadow-md transition">
            <p class="text-sm font-semibold text-slate-400 uppercase tracking-wider">Revenue Today</p>
            <h3 class="text-3xl font-black text-blue-600 mt-2">RM {{ number_format($totalRevenue, 2) }}</h3>
        </div>
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200/80 hover:shadow-md transition">
            <p class="text-sm font-semibold text-slate-400 uppercase tracking-wider">Orders Today</p>
            <h3 class="text-3xl font-black text-emerald-600 mt-2">{{ $todayOrders }}</h3>
        </div>
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200/80 hover:shadow-md transition">
            <p class="text-sm font-semibold text-slate-400 uppercase tracking-wider">Appointments Completed</p>
            <h3 class="text-3xl font-black text-purple-600 mt-2">{{ $completedAppointments }}</h3>
        </div>
    </div>

    <!-- Bahagian Tengah: Carta Prestasi Jualan (Sales Chart) -->
    <div class="bg-white p-6 md:p-8 rounded-2xl shadow-sm border border-slate-200/80 space-y-6">
        <div class="flex items-center justify-between">
            <h3 class="text-lg font-bold text-slate-800">Sales Performance Overview</h3>
            <span class="text-xs font-semibold px-3 py-1 bg-purple-50 text-purple-700 rounded-lg">Analytics</span>
        </div>

        <!-- Ruang Paparan Carta -->
        <div class="relative h-72 w-full">
            <canvas id="salesChart"></canvas>
        </div>
    </div>


</div>

@php
    $labels = $chartData['labels'] ?? ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'];
    $totals = $chartData['totals'] ?? [0, 0, 0, 0, 0, 0];
@endphp

<!-- 1) Muatkan Chart.js (tag ini mesti berasingan, jangan letak kod di dalamnya) -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>

<!-- 2) Kod carta anda -->
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const ctx = document.getElementById('salesChart').getContext('2d');

        const chartLabels = @json($labels);
        const chartTotals = @json($totals);

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: chartLabels,
                datasets: [{
                    label: 'Total Sales (RM)',
                    data: chartTotals,
                    backgroundColor: 'rgba(147, 51, 234, 0.05)',
                    borderColor: 'rgba(147, 51, 234, 1)',
                    borderWidth: 2.5,
                    tension: 0.35,
                    fill: true,
                    pointBackgroundColor: 'rgba(147, 51, 234, 1)',
                    pointRadius: 4,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { color: 'rgba(241, 245, 249, 1)' }
                    },
                    x: {
                        grid: { display: false }
                    }
                },
                plugins: {
                    legend: {
                        display: false
                    }
                }
            }
        });
    });
</script>
@endsection
