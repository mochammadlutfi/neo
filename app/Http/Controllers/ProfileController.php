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
            'perusahaan' => 'nullable|string',
            'email' => 'required|email|unique:users,email,' . auth()->user()->id,
            'hp' => 'nullable|string',
        ];

        $pesan = [
            'nama.required' => 'Nama Lengkap Wajib Diisi!',
            'email.required' => 'Email Wajib Diisi!',
            'email.email' => 'Format Email Tidak Valid!',
            'email.unique' => 'Email Sudah Terdaftar!',
        ];

        $validator = Validator::make($request->all(), $rules, $pesan);
        if ($validator->fails()){
            return back()->withInput()->withErrors($validator->errors());
        }else{
            DB::beginTransaction();
            try{
                
                $data = User::where('id', auth()->user()->id)->first();
                $data->nama = $request->nama;
                $data->perusahaan = $request->perusahaan;
                $data->email = $request->email;
                $data->hp = $request->hp;
                $data->save();

            }catch(\QueryException $e){
                DB::rollback();
                return back()->with('error', 'Terjadi kesalahan saat menyimpan data.');
            }

            DB::commit();
            return back()->with('success', 'Profil berhasil diupdate!');
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
            'old_password' => 'required',
            'password' => 'required|min:8|same:password_conf',
            'password_conf' => 'required',
        ];

        $pesan = [
            'old_password.required' => 'Password Lama Wajib Diisi!',
            'password.required' => 'Password Baru Wajib Diisi!',
            'password.min' => 'Password Minimal 8 Karakter!',
            'password.same' => 'Konfirmasi Password Tidak Sama!',
            'password_conf.required' => 'Konfirmasi Password Wajib Diisi!',
        ];
        
        $validator = Validator::make($request->all(), $rules, $pesan);
        if ($validator->fails()){
            return back()->withInput()->withErrors($validator->errors());
        }

        // Verify old password
        $user = User::find(auth()->user()->id);
        if (!Hash::check($request->old_password, $user->password)) {
            return back()->with('error', 'Password lama tidak sesuai!');
        }

        DB::beginTransaction();
        try{
            $user->password = Hash::make($request->password);
            $user->save();

        }catch(\QueryException $e){
            DB::rollback();
            return back()->with('error', 'Terjadi kesalahan saat menyimpan password.');
        }

        DB::commit();
        return back()->with('success', 'Password berhasil diubah!');
    }
}
