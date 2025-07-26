<?php


namespace App\Http\Controllers;

use App\Models\Anggota;
use App\Models\User;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

use App\Providers\RouteServiceProvider;
use Validate;
use Auth;
use Illuminate\Support\Facades\Route;
use Redirect;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class AuthController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * Show the login form.
     *
     * @return \Illuminate\Http\Response
     */
    public function showLogin()
    {
        return view('landing.auth.login');
    }

    /**
    * Create a new controller instance.
    *
    * @return void
    */
    public function login(Request $request)
    {
    $input = $request->all();
        // dd($input);
    $rules = [
        'email' => 'required|exists:users,email',
        'password' => 'required|string'
    ];

    $pesan = [
        'email.exists' => 'email Tidak Terdaftar!',
        'email.required' => 'email Wajib Diisi!',
        'password.required' => 'Password Wajib Diisi!',
    ];


    $validator = Validator::make($request->all(), $rules, $pesan);
    if ($validator->fails()){
        return back()->withInput()->withErrors($validator->errors());
    }else{
        if(auth()->guard('web')->attempt(array('email' => $input['email'], 'password' => $input['password']), true))
        {
            return redirect()->route('home');
        }else{
            $gagal['password'] = array('Password salah!');
            return back()->withInput()->withErrors($gagal);
        }
    }

    }

    public function logout()
    {
        Auth::guard('web')->logout();

        return redirect()->route('home');
    }

    
    public function showRegister()
    {
        return view('landing.auth.register');
    }

    public function register(Request $request)
    {
        $rules = [
            'nama' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'perusahaan' => 'required',
            'hp' => 'required',
            'password' => 'required|same:password_conf',
            'password_conf' => 'required'
        ];

        $pesan = [
            'nama.required' => 'Nama Lengkap harus diisi',
            'email.required' => 'Alamat Email harus diisi',
            'email.unique' => 'Alamat Email sudah terdaftar',
            'perusahaan.required' => 'Nama perusahaan harus diisi',
            'hp.required' => 'No HP harus diisi',
            'alamat.required' => 'Alamat harus diisi',
            'password.required' => 'Password harus diisi',
            'password_conf.required' => 'Konfirmasi Password harus diisi',
        ];

        $validator = Validator::make($request->all(), $rules, $pesan);
        if ($validator->fails()){
            return back()->withInput()->withErrors($validator->errors());
        }else{
            DB::beginTransaction();
            try{

                $auth = new User();
                $auth->nama = $request->nama;
                $auth->email = $request->email;
                $auth->password = Hash::make($request->password);
                $auth->hp = $request->hp;
                $auth->perusahaan = $request->perusahaan;
                $auth->save();

            }catch(\QueryException $e){
                DB::rollback();
                return back()->withInput()->withErrors($e);
            }

            DB::commit();
            auth()->guard('web')->attempt(array('email' => $request->email, 'password' => $request->password), true);
            return redirect()->route('home');
        }
    }
}
