<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Models\User;
use App\Models\Order;
use App\Models\Pembayaran;
use App\Models\Project;
use Illuminate\Support\Facades\DB;

use Carbon\Carbon;
class DashboardController extends Controller
{

    public function index(){
        $user = auth()->user();
        
        // Overview Statistics
        $ovr = Collect([
            'user' => User::count(),
            'order' => Order::count(),
            'pembayaran' => Pembayaran::count(),
            'project' => Project::count(),
        ]);

        // Revenue Statistics
        $totalRevenue = Pembayaran::where('status', 'terima')->sum('jumlah');
        $pendingRevenue = Pembayaran::where('status', 'pending')->sum('jumlah');
        $thisMonthRevenue = Pembayaran::where('status', 'terima')
            ->whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->sum('jumlah');

        // Order Status Statistics - Using PHP since status_pembayaran is accessor
        $allOrders = Order::with('payment')->get();
        $orderStats = (object) [
            'total' => $allOrders->count(),
            'lunas' => $allOrders->where('status_pembayaran', 'Lunas')->count(),
            'sebagian' => $allOrders->where('status_pembayaran', 'Sebagian')->count(),
            'belum_bayar' => $allOrders->where('status_pembayaran', 'Belum Bayar')->count()
        ];

        // Monthly Order Chart Data (Last 6 months)
        $monthlyOrders = Order::selectRaw('MONTH(created_at) as month, YEAR(created_at) as year, COUNT(*) as total')
            ->where('created_at', '>=', Carbon::now()->subMonths(6))
            ->groupBy('month', 'year')
            ->orderBy('year', 'asc')
            ->orderBy('month', 'asc')
            ->get();

        // Monthly Revenue Chart Data (Last 6 months)
        $monthlyRevenue = Pembayaran::selectRaw('MONTH(created_at) as month, YEAR(created_at) as year, SUM(jumlah) as total')
            ->where('status', 'terima')
            ->where('created_at', '>=', Carbon::now()->subMonths(6))
            ->groupBy('month', 'year')
            ->orderBy('year', 'asc')
            ->orderBy('month', 'asc')
            ->get();

        // Payment Status Distribution
        $paymentStats = Pembayaran::selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->get();

        // Recent Orders
        $recentOrders = Order::with(['user', 'paket'])
            ->latest()
            ->limit(5)
            ->get();

        // Recent Payments
        $recentPayments = Pembayaran::with(['order.user'])
            ->latest()
            ->limit(5)
            ->get();

        return view('admin.dashboard',[
            'ovr' => $ovr,
            'totalRevenue' => $totalRevenue,
            'pendingRevenue' => $pendingRevenue,
            'thisMonthRevenue' => $thisMonthRevenue,
            'orderStats' => $orderStats,
            'monthlyOrders' => $monthlyOrders,
            'monthlyRevenue' => $monthlyRevenue,
            'paymentStats' => $paymentStats,
            'recentOrders' => $recentOrders,
            'recentPayments' => $recentPayments,
        ]);
    }
}
