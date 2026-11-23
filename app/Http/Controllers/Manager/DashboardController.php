<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Appointment;
use App\Models\Item; // <-- Pastikan model Item diimport di sini

class DashboardController extends Controller
{
    public function index()
    {
        // 1. Kira jumlah order hari ini
        $todayOrders = Order::whereDate('created_at', today())->count();

        // 2. Kira jumlah appointment yang telah 'Completed' hari ini
        $completedAppointments = Appointment::whereDate('appointmentDate', today())
                                           ->where('status', 'Completed')
                                           ->count();

        // 3. Kira jumlah jualan hari ini
        $totalRevenue = Order::whereDate('created_at', today())->sum('total_price');

        // 4. Ambil senarai 5 pesanan terkini beserta maklumat pelanggan
        $recentOrders = Order::with('customer')->latest()->take(5)->get();

        // 5. Ambil item inventori yang stoknya rendah (<= 5) atau habis (0)
        $lowStockItems = Item::where('stock', '<=', 10)->get();

        // 6. Data untuk carta jualan (Chart.js)
        $chartData = [
            'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
            'totals' => [1200, 1900, 3000, 5000, 2400, 3800]
        ];

        // Hantar kesemua pemboleh ubah termasuk lowStockItems ke fail paparan
        return view('manager.dashboard', compact(
            'todayOrders',
            'completedAppointments',
            'totalRevenue',
            'recentOrders',
            'lowStockItems', // <-- Ditambah di dalam compact supaya boleh dibaca oleh Blade
            'chartData'
        ));
    }
}
