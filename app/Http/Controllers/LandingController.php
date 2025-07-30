<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\Paket;
class LandingController extends Controller
{

    public function index()
    {

        $paket = Paket::orderBy('id', 'ASC')->get();
        // dd(explode(',', $paket->fitur));
        return view('landing.home',[
            'paket' => $paket
        ]);
    }

    
    public function kontak()
    {
        return view('landing.kontak');
    }

}
