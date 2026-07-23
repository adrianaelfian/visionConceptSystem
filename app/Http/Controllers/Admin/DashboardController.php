<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\{Order, Customer, Item};
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Statistik Ringkas
        $totalSales = Order::whereMonth('created_at', now()->month)->sum('total_price');
        $customerCount = Customer::count();
        $lowStockItems = Item::where('stock', '<', 5)->get();

        // Data untuk Graf (Jualan 6 bulan terakhir) - Ditukar kepada created_at
        $salesData = Order::select(DB::raw('SUM(total_price) as total'), DB::raw('MONTH(created_at) as month'))
            ->groupBy('month')
            ->orderBy('month', 'DESC')
            ->limit(6)
            ->get();

        return view('admin.dashboard', compact('totalSales', 'customerCount', 'lowStockItems', 'salesData'));
    }
}
