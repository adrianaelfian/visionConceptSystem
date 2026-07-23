<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Appointment;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. Kira jumlah order hari ini
        $todayOrders = Order::whereDate('created_at', now())->count();

        // 2. Kira jumlah appointment yang telah 'Completed' hari ini
        $completedAppointments = Appointment::whereDate('updated_at', now())
                                           ->where('status', 'Completed')
                                           ->count();

        // 3. Kira jumlah jualan hari ini
        $totalRevenue = Order::whereDate('created_at', now())->sum('total_price');

        // 4. Ambil senarai 5 pesanan terkini
        $recentOrders = Order::latest()->take(5)->get();

        // 5. Data untuk carta jualan (Chart.js)
        $chartData = [
            'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
            'totals' => [1200, 1900, 3000, 5000, 2400, 3800]
        ];

        // Hantar kesemua pemboleh ubah ke fail paparan manager.dashboard sekali gus
        return view('manager.dashboard', compact(
            'todayOrders',
            'completedAppointments',
            'totalRevenue',
            'recentOrders',
            'chartData'
        ));
    }
}
