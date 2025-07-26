<?php


namespace App\Http\Controllers\Admin;

use App\User;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

use App\Providers\RouteServiceProvider;
// use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Validate;
use Illuminate\Support\Facades\Validator;
use Auth;
use Illuminate\Support\Facades\Route;
use Redirect;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    // use AuthenticatesUsers;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest:admin')->except('logout');
    }

    /**
     * Show the login form.
     *
     * @return \Illuminate\Http\Response
     */
    public function showLoginForm()
    {
        return view('admin.login');
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
            'username' => 'required|exists:admins,username',
            'password' => 'required|string'
        ];

        $pesan = [
            'username.required' => 'Username harus diisi',
            'username.exists' => 'Username tidak terdaftar',
            'password.required' => 'Password harus diisi',
        ];

        $validator = Validator::make($request->all(), $rules, $pesan);
        if ($validator->fails()){
            return back()->withErrors($validator->errors())->withInput();
        }else{
            if(auth()->guard('admin')->attempt(array('username' => $input['username'], 'password' => $input['password']), true))
            {
                return redirect()->route('admin.beranda');
            }else{
                $gagal['password'] = array('Password salah!');
                return back()->withErrors($gagal)->withInput();
            }
        }

     }

    public function logout()
    {
        Auth::guard('admin')->logout();
        return redirect()->route('admin.login');
    }

}
