<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Models\User;
use App\Models\Order;
use App\Models\Pembayaran;

use Carbon\Carbon;
class DashboardController extends Controller
{

    public function index(){
        $user = auth()->user();
        $ovr = Collect([
            'order' => Order::get()->count(),
            'user' => User::get()->count(),
            'pembayaran' => Pembayaran::get()->count(),
        ]);

        return view('admin.dashboard',[
            'ovr' => $ovr,
            // 'berlangsung' => $berlangsung 
        ]);
    }
}
