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

    <!-- Bahagian Bawah: Jadual Order Terbaru -->
    <div class="bg-white p-6 md:p-8 rounded-2xl shadow-sm border border-slate-200/80">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-bold text-slate-800">Recent Orders</h3>
            <span class="text-xs font-medium text-slate-400">Latest transactions</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-slate-100 text-slate-400 text-xs font-semibold uppercase tracking-wider">
                        <th class="pb-3 px-2">Order ID</th>
                        <th class="pb-3 px-2">Date</th>
                        <th class="pb-3 px-2 text-right">Amount</th>
                    </tr>
                </thead>
                <tbody class="text-sm divide-y divide-slate-50 text-slate-600">
                    @forelse($recentOrders as $order)
                    <tr class="hover:bg-slate-50/50 transition">
                        <td class="py-3.5 px-2 font-semibold text-slate-700">#{{ $order->id }}</td>
                        <td class="py-3.5 px-2 text-slate-500">{{ $order->created_at->format('d M Y') }}</td>
                        <td class="py-3.5 px-2 text-right font-bold text-slate-800">RM {{ number_format($order->total_price, 2) }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" class="py-6 text-center text-slate-400 text-sm">No recent orders found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Skrip Chart.js -->
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const ctx = document.getElementById('salesChart').getContext('2d');

        @php
            $labels = $chartData['labels'] ?? ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'];
            $totals = $chartData['totals'] ?? [0, 0, 0, 0, 0, 0];
        @endphp

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
