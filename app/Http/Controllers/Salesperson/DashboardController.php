<?php

namespace App\Http\Controllers\Salesperson;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Appointment;
use App\Models\Order;

class DashboardController extends Controller
{
    public function dashboard()
    {
        $todayAppointments = Appointment::whereDate('appointmentDate', now())
                                    ->where('status', 'Confirmed')
                                    ->get();

        $todayOrdersCount = Order::whereDate('created_at', today())->count();

        // Tukar 'total_amount' kepada nama kolum sebenar anda (cth: total_price atau price)
        $todaySales = Order::whereDate('created_at', today())->sum('total_price');

        return view('salesperson.dashboard', compact('todayAppointments', 'todayOrdersCount', 'todaySales'));
    }
}
