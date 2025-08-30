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
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\RedirectResponse;

class AuthController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except(['logout', 'showVerifyEmail', 'verifyEmail', 'resendVerification']);
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
            $user = auth()->user();
            if (!$user->hasVerifiedEmail()) {
                return redirect()->route('verification.notice');
            }
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
            
            // Auto login user after registration
            auth()->guard('web')->login($auth, true);
            
            // Trigger email verification
            event(new Registered($auth));
            
            return redirect()->route('verification.notice');
        }
    }

    /**
     * Display the email verification prompt.
     */
    public function showVerifyEmail(Request $request)
    {
        return $request->user()->hasVerifiedEmail()
                    ? redirect()->intended(route('home'))
                    : view('landing.auth.verify-email');
    }

    /**
     * Mark the authenticated user's email address as verified.
     */
    public function verifyEmail(Request $request): RedirectResponse
    {
        $user = User::find($request->route('id'));

        if (!$user) {
            return redirect()->route('login')->withErrors(['email' => 'User tidak ditemukan.']);
        }

        if ($user->hasVerifiedEmail()) {
            return redirect()->intended(route('home'));
        }

        if ($user->markEmailAsVerified()) {
            event(new Verified($user));
        }

        return redirect()->route('login')->with('status', 'Email berhasil diverifikasi! Silakan login.');
    }

    /**
     * Send a new email verification notification.
     */
    public function resendVerification(Request $request): RedirectResponse
    {
        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->intended(route('home'));
        }

        $request->user()->sendEmailVerificationNotification();

        return back()->with('status', 'Email verifikasi telah dikirim ulang!');
    }
}
