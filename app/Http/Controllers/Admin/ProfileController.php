<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Program;
use App\Models\UserProgram;
use Illuminate\Support\Facades\Hash;

use Carbon\Carbon;
class ProfileController extends Controller
{

    public function index(){
        return view('admin.profil');
    }

    public function update(Request $request){
        $user = auth()->guard('admin')->user();
        $user->update([
            'nama' => $request->nama,
            'username' => $request->username
        ]);
        return redirect()->back()->with('success', 'Profil berhasil diperbarui');
    }

    public function password(){
        return view('admin.password');
    }

    public function passwordUpdate(Request $request)
    {
        $rules = [
            'old_password' => 'required',
            'password' => 'required|same:password_confirmation',
            'password_confirmation' => 'required'
        ];

        $pesan = [
            'old_password.required' => 'Password Wajib Diisi',
            'password.required' => 'Password Wajib Diisi',
            'password.same' => 'Konfirmasi Password Tidak Sama!',
            'password_confirmation.required' => 'Konfirmasi Password Wajib Diisi'
        ];
        
        $validator = Validator::make($request->all(), $rules, $pesan);
        if ($validator->fails()){
            return back()->withInput()->withErrors($validator->errors());
        }else{
            DB::beginTransaction();
            try{
                
                $data = auth()->guard('admin')->user();
                $data->password = Hash::make($request->password);
                $data->save();

            }catch(\QueryException $e){
                DB::rollback();
                dd($e);
            }

            DB::commit();
            return redirect()->back()->with('success', 'Password berhasil diperbarui');
        }
    }
}
