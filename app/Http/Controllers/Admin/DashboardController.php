<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $totalOrders = Order::count();

        $totalProducts = Product::count();

        $totalRevenue = Order::where('payment_status', 'paid')
            ->sum('total_amount');

        $activeStudents = User::where('role', 'siswa')->count();

        return view('admin.dashboard', compact(
            'totalOrders',
            'totalProducts',
            'totalRevenue',
            'activeStudents'
        ));
    }
}
