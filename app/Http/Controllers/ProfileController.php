<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

use App\Models\User;
use DataTables;
use Carbon\Carbon;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request)
    {   
        $data = User::find(auth()->guard('web')->user()->id);
        
        return view('landing.profil', [
            'data' => $data,
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(Request $request)
    {
        $rules = [
            'nama' => 'required|string',
            'username' => 'required|unique:users,username',
        ];

        $pesan = [
            'nama.required' => 'Nama Lengkap Wajib Diisi!',
            'username.required' => 'Username Wajib Diisi!',
            'username.unique' => 'Username Sudah Terdaftar!',
        ];

        $validator = Validator::make($request->all(), $rules, $pesan);
        if ($validator->fails()){
            return back()->withInput()->withErrors($validator->errors());
        }else{
            DB::beginTransaction();
            try{
                
                $data = User::where('id', auth()->user()->id)->first();
                $data->nama = $request->nama;
                $data->username = $request->username;
                $data->save();

            }catch(\QueryException $e){
                DB::rollback();
                dd($e);
            }

            DB::commit();
            return redirect()->route('home');
        }
    }

    public function password(Request $request): View
    {   

        return view('landing.password', [
            'user' => $request->user(),
        ]);
    }
    
    public function passwordUpdate(Request $request)
    {
        $rules = [
            'password' => 'required|same:password_confirmation',
        ];

        $pesan = [
            'password.required' => 'Password Wajib Diisi!',
            'password.same' => 'Konfirmasi Password Tidak Sama!',
        ];
        
        $validator = Validator::make($request->all(), $rules, $pesan);
        if ($validator->fails()){
            return back()->withInput()->withErrors($validator->errors());
        }else{
            DB::beginTransaction();
            try{
                
                $data = User::where('id', auth()->user()->id)->first();
                $data->password = Hash::make($request->password);
                $data->save();

            }catch(\QueryException $e){
                DB::rollback();
                dd($e);
            }

            DB::commit();
            return redirect()->route('beranda');
        }
    }
}
