<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\MenuItem;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function dashboard()
    {
        $stats = [
            'totalSales' => Order::where('status', 'completed')->sum('total'),
            'todayOrders' => Order::whereDate('created_at', today())->count(),
            'activeOrders' => Order::whereIn('status', ['pending', 'preparing'])->count(),
            'menuItems' => MenuItem::count(),
        ];

        $recentOrders = Order::with('user')->latest()->take(10)->get();
        $users = User::all();
        $menuItems = MenuItem::all();

        return view('admin.dashboard', compact('stats', 'recentOrders', 'users', 'menuItems'));
    }

    public function transactionMonitor()
    {
        $recentOrders = Order::with(['user', 'orderItems.menuItem'])
                            ->where('created_at', '>=', now()->subDay())
                            ->latest()
                            ->get();

        $activeOrders = Order::whereIn('status', ['pending', 'preparing'])->count();
        $completedToday = Order::whereDate('created_at', today())
                              ->where('status', 'completed')
                              ->count();
        $todaySales = Order::whereDate('created_at', today())
                          ->where('status', 'completed')
                          ->sum('total');

        return view('admin.transaction-monitor', compact(
            'recentOrders', 'activeOrders', 'completedToday', 'todaySales'
        ));
    }

    public function salesReport(Request $request)
    {
        $filter = $request->get('filter', 'daily');
        
        $salesData = $this->getSalesData($filter);
        $orders = Order::with(['user', 'orderItems.menuItem'])->latest()->get();

        return view('admin.sales-report', compact('salesData', 'orders', 'filter'));
    }

    private function getSalesData($filter)
    {
        $query = Order::select(
            DB::raw('DATE(created_at) as date'),
            DB::raw('COUNT(*) as transactions'),
            DB::raw('SUM(total) as total_sales')
        )->where('status', 'completed');

        switch ($filter) {
            case 'weekly':
                $query->groupBy(DB::raw('YEAR(created_at), WEEK(created_at)'));
                break;
            case 'monthly':
                $query->groupBy(DB::raw('YEAR(created_at), MONTH(created_at)'));
                break;
            case 'yearly':
                $query->groupBy(DB::raw('YEAR(created_at)'));
                break;
            default: // daily
                $query->groupBy(DB::raw('DATE(created_at)'));
        }

        return $query->get();
    }
}