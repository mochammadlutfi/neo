<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DataTables;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Models\Training;
use Storage;
use Carbon\Carbon;
use App\Models\UserTraining;
use App\Models\User;
use PDF;
class UserTrainingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id, Request $request)
    {
        if ($request->ajax()) {
            $query = UserTraining::with('user')->where('training_id', $id)->get();

            return DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('action', function($row) use($id){
                    $btn = '<div class="dropdown">
                        <button type="button" class="btn btn-outline-primary btn-sm dropdown-toggle" id="dropdown-default-outline-primary" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Aksi
                        </button>
                        <div class="dropdown-menu fs-sm" aria-labelledby="dropdown-default-outline-primary" style="">';
                    if($row->status == 'lunas'){
                        $btn .= '<a class="dropdown-item" target="_blank" href="'. route('admin.training.peserta.certificate', ['id' => $id, 'user'=>$row->id]).'"><i class="si si-badge me-1"></i>Sertifikat</a>';
                    }
                        
                        $btn .= '<a class="dropdown-item" href="javascript:void(0)" onclick="hapus('. $row->id.')"><i class="si si-trash me-1"></i>Hapus</a>';
                    $btn .= '</div></div>';
                    // $btn = '<button type="button" class="btn btn-sm btn-danger" onclick="hapus('. $row->id.')"><i class="fa fa-trash me-1"></i>Hapus</button>';

                    return $btn; 
                })
                ->editColumn('created_at', function ($row) {
                    $tgl = Carbon::parse($row->created_at);

                    return $tgl->translatedFormat('d M Y');
                })
                ->editColumn('status', function ($row) {
                    if($row->status == 'belum bayar'){
                        return '<span class="badge bg-danger">Belum Bayar</span>';
                    }else if($row->status == 'sebagian'){
                        return '<span class="badge bg-warning">Sebagian</span>';
                    }else if($row->status == 'pending'){
                        return '<span class="badge bg-primary">Menunggu Konfirmasi</span>';
                    }else if($row->status == 'lunas'){
                        return '<span class="badge bg-success">Lunas</span>';
                    }else if($row->status == 'batal'){
                        return '<span class="badge bg-secondary">Batal</span>';
                    }
                })
                ->rawColumns(['action','status']) 
                ->make(true);
        }

        $data = Training::where('id', $id)->first();
        $user = User::orderBy('nama', 'ASC')->get();

        return view('admin.training.peserta',[
            'data' => $data,
            'user' => $user
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store($id, Request $request)
    {
        DB::beginTransaction();
        try{

            $data = new UserTraining();
            $data->nomor = $this->getNumber($id);
            $data->user_id = $request->user_id;
            $data->training_id = $id;
            $data->status = $request->status;
            $data->save();

        }catch(\QueryException $e){
            DB::rollback();
            return response()->json([
                'fail' => true,
                'pesan' => $e,
            ]);
        }

        DB::commit();
        return response()->json([
            'fail' => false,
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function certificate($id, $user)
    {
        $data = UserTraining::with(['user', 'training'])->where('id', $user)->first();

        $config = [
            'format' => 'A4-L' // Landscape
        ];
        // $stylesheet = file_get_contents(base_path('/resources/css/certificate.css'));

        $pdf = PDF::loadView('reports.certificate', [
            'data' => $data
        ], [ ], $config);

        return $pdf->stream('Sertifikat '. $data->training->nama.'.pdf');

        return view("reports.certificate", [
            'data' => $data
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $rules = [
            'nama' => 'required',
            'deskripsi' => 'required',
            'lokasi' => 'required',
            'tgl_daftar' => 'required',
            'tgl_training' => 'required',
            'slot' => 'required',
        ];

        $pesan = [
            'nama.required' => 'Nama Wajib Diisi!',
            'deskripsi.required' => 'Deskripsi Wajib Diisi!',
            'lokasi.required' => 'Lokasi Wajib Diisi!',
            'tgl_daftar.required' => 'Tanggal Pendaftaran Wajib Diisi!',
            'tgl_training.required' => 'Tanggal Training Wajib Diisi!',
            'slot.required' => 'Slot Peserta Wajib Diisi!',
        ];

        $validator = Validator::make($request->all(), $rules, $pesan);
        if ($validator->fails()){
            return back()->withInput()->withErrors($validator->errors());
        }else{
            DB::beginTransaction();
            try{
                $tgl_daftar = explode(" - ",$request->tgl_daftar);
                $tgl_training = explode(" - ",$request->tgl_training);
                
                $data = Training::where('id', $id)->first();
                $data->nama = $request->nama;
                $data->lokasi = $request->lokasi;
                $data->deskripsi = $request->deskripsi;
                $data->tgl_mulai_daftar = $tgl_daftar[0];
                $data->tgl_selesai_daftar = (count($tgl_daftar) > 1) ? $tgl_daftar[1] : null;
                $data->tgl_mulai = $tgl_training[0];
                $data->tgl_selesai = (count($tgl_training) > 1) ? $tgl_training[1] : null;
                $data->slot = $request->slot;
                $data->status = $request->status;
                $data->harga = isset($request->harga) ? $request->harga : 0;
                
                if($request->foto){
                    $fileName = time() . '.' . $request->foto->extension();
                    Storage::disk('public')->putFileAs('uploads/training', $request->foto, $fileName);
                    $data->foto = '/uploads/training/'.$fileName;
                }
                $data->save();

            }catch(\QueryException $e){
                DB::rollback();
                dd($e);
            }

            DB::commit();
            return redirect()->route('admin.training.index');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        DB::beginTransaction();
        try{

            $data = UserTraining::where('id', $id)->first();
            $data->delete();

        }catch(\QueryException $e){
            DB::rollback();
            return response()->json([
                'fail' => true,
                'errors' => $e,
                'pesan' => 'Gagal Hapus Data!',
            ]);
        }

        DB::commit();
        return response()->json([
            'fail' => false,
            'pesan' => 'Berhasil Hapus Data!',
        ]);
    }

    private function getNumber($training)
    {
        $q = UserTraining::select(DB::raw('MAX(RIGHT(nomor,5)) AS kd_max'));

        $code = $training.'/';
        $no = 1;
        date_default_timezone_set('Asia/Jakarta');

        if($q->count() > 0){
            foreach($q->get() as $k){
                return $code . date('ym') .'/'.sprintf("%05s", abs(((int)$k->kd_max) + 1));
            }
        }else{
            return $code . date('ym') .'/'. sprintf("%05s", $no);
        }
    }

}
