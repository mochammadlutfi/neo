<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Routing\Controller;
use Carbon\Carbon;
use Storage;
use DataTables;

use App\Models\User;
use App\Models\Task;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        if ($request->ajax()) {
            $project_id = $request->project_id;
            $data = Task::with(['project'])
            ->when(isset($project_id), function($q) use($project_id){
                return $q->where('project_id', $project_id);
            })
            ->orderBy('id', 'DESC')->get();

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($row){
                    $btn = '<div class="dropdown">
                        <button type="button" class="btn btn-primary btn-sm dropdown-toggle" id="dropdown-default-outline-primary" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Aksi
                        </button>
                        <div class="dropdown-menu fs-sm" aria-labelledby="dropdown-default-outline-primary" style="">';
                        $btn .= '<a class="dropdown-item" href="javascript:void(0)" onclick="detail('. $row->id .')"><i class="si si-eye me-1"></i>Detail</a>';
                        $btn .= '<a class="dropdown-item" href="javascript:void(0)" onclick="edit('.$row->id.')"><i class="si si-note me-1"></i>Ubah</a>';
                        $btn .= '<a class="dropdown-item" href="javascript:void(0)" onclick="hapus('. $row->id.')"><i class="si si-trash me-1"></i>Hapus</a>';
                    $btn .= '</div></div>';
                    return $btn; 
                })
                ->editColumn('tgl_tempo', function ($row) {
                    $tgl =  Carbon::parse($row->tgl_tempo)->translatedFormat('d F Y');
                    return $tgl;
                })
                ->editColumn('tgl_upload', function ($row) {
                    $tgl = Carbon::parse($row->tgl_upload);

                    return $tgl->translatedFormat('d F Y H:i');
                })
                ->editColumn('status', function ($row) {
                    if($row->status == 'Draft'){
                        return '<span class="badge bg-danger">Draft</span>';
                    }else if($row->status == 'Pending'){
                        return '<span class="badge bg-warning">Pending</span>';
                    }else if($row->status == 'Selesai'){
                        return '<span class="badge bg-primary">Selesai</span>';
                    }else if($row->status == 'Disetujui'){
                        return '<span class="badge bg-success">Setuju</span>';
                    }else if($row->status == 'Ditolak'){
                        return '<span class="badge bg-secondary">Ditolak</span>';
                    }
                }) 
                ->editColumn('status_upload', function ($row) {
                    if($row->status_upload == 0){
                        return '<span class="badge bg-danger">Belum Upload</span>';
                    }else{
                        return '<span class="badge bg-success">Sudah Upload</span>';
                    }
                })
                ->rawColumns(['action', 'status', 'harga', 'status_upload']) 
                ->make(true);
        }
        $training = Training::where('status', 'buka')->orderBy('id', 'ASC')->get();
        return view('admin.pembayaran',[
            'training' => $training
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $rules = [
            'nama' => 'required',
            'link_brief' => 'required',
            'tgl_tempo' => 'required',
            'tgl_upload' => 'required',
        ];

        $pesan = [
            'nama.required' => 'Nama tugas harus diisi!',
            'link_brief.required' => 'Link brief harus diisi!',
            'tgl_tempo.required' => 'Tanggal tempo harus diisi!',
            'tgl_upload.required' => 'Tanggal upload harus diisi!',
        ];

        $validator = Validator::make($request->all(), $rules, $pesan);
        if ($validator->fails()){
            return response()->json([
                'fail' => true,
                'errors' => $validator->errors()
            ]);
        }else{
            DB::beginTransaction();
            try{
                $data = new Task();
                $data->project_id = $request->project_id;
                $data->nama = $request->nama;
                $data->link_brief = $request->link_brief;
                $data->tgl_tempo = $request->tgl_tempo;
                $data->tgl_upload = $request->tgl_upload;
                $data->status = 'Draft';
                $data->status_upload = 0;

                if($request->file){
                    $fileName = time() . '.' . $request->file->extension();
                    Storage::disk('public')->putFileAs('uploads/task', $request->file, $fileName);
                    $data->file = '/uploads/task/'.$fileName;
                }
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
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data = Task::where('id', $id)->first();

        if($data->status == 'Pending'){
            $status = '<span class="badge bg-primary">Pending</span>';
        }else if($data->status == 'Selesai'){
            $status = '<span class="badge bg-success">Diterima</span>';
        }else{
            $status = '<span class="badge bg-secondary">Ditolak</span>';
        }
        
        if($data->status_upload){
            $status_upload = '<span class="badge bg-primary">Sudah Upload</span>';
        }else {
            $status_upload = '<span class="badge bg-danger">Belum Upload</span>';
        }

        $html = '<div class="block-content p-4">
            <div class="row mb-3">
                <div class="col-md-12">
                    <div class="row mb-3">
                        <label class="col-sm-5 fw-medium">Nama Tugas</label>
                        <div class="col-sm-7">: '. $data->nama .'</div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-sm-5 fw-medium">Link Brief</label>
                        <div class="col-sm-7">: 
                            <a href="'. $data->link_brief .'" target="_blank" class="badge bg-primary px-3 text-white">Lihat Brief</a>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-sm-5 fw-medium">Status</label>
                        <div class="col-sm-7">: '. $status .'</div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-sm-5 fw-medium">Tanggal Tempo</label>
                        <div class="col-sm-7">
                            : '. Carbon::parse($data->tgl_tempo)->translatedFormat('d F Y') .'
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-sm-5 fw-medium">Tanggal Upload</label>
                        <div class="col-sm-7">
                            : '. Carbon::parse($data->tgl_upload)->translatedFormat('d F Y H:i') .' WIB
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-sm-5 fw-medium">Status Upload</label>
                        <div class="col-sm-7">: '. $status_upload .'</div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-sm-5 fw-medium">File Task</label>
                        <div class="col-sm-7">: 
                            <a href="'. $data->file .'" target="_blank" class="badge bg-primary px-3 text-white">
                                Lihat File
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>';

        echo $html;
    }

    
    public function status($id, Request $request)
    {
        DB::beginTransaction();
        try{
            $data = UserTraining::where('id', $id)->first();
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
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data = Task::where('id', $id)->first();

        return response()->json($data);
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
        // dd($request->all());
        $rules = [
            'nama' => 'required',
            'link_brief' => 'required',
            'tgl_tempo' => 'required',
            'tgl_upload' => 'required',
        ];

        $pesan = [
            'nama.required' => 'Nama tugas harus diisi!',
            'link_brief.required' => 'Link brief harus diisi!',
            'tgl_tempo.required' => 'Tanggal tempo harus diisi!',
            'tgl_upload.required' => 'Tanggal upload harus diisi!',
        ];

        $validator = Validator::make($request->all(), $rules, $pesan);
        if ($validator->fails()){
            return response()->json([
                'fail' => true,
                'errors' => $validator->errors()
            ]);
        }else{
            DB::beginTransaction();
            try{

                $data = Task::where('id', $id)->first();
                $data->project_id = $request->project_id;
                $data->nama = $request->nama;
                $data->link_brief = $request->link_brief;
                $data->tgl_tempo = $request->tgl_tempo;
                $data->tgl_upload = $request->tgl_upload;
                if($request->status == 'Ditolak'){
                    $data->status = 'Draft';
                }
                $data->status_upload = $request->status_upload;

                if($request->file){
                    $fileName = time() . '.' . $request->file->extension();
                    Storage::disk('public')->putFileAs('uploads/task', $request->file, $fileName);
                    $data->file = '/uploads/task/'.$fileName;
                }
                $data->save();

            }catch(\QueryException $e){
                DB::rollback();
                return response()->json([
                    'fail' => true,
                    'errors' => $e
                ]);
            }

            DB::commit();
            return response()->json([
                'fail' => false,
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Perbaikan  $perbaikan
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        DB::beginTransaction();
        try{

            $data = Task::where('id', $id)->first();
            $data->delete();

        }catch(\QueryException $e){
            DB::rollback();
            return response()->json([
                'fail' => true,
                'errors' => $e,
                'pesan' => 'Data Gagal Dihapus!',
            ]);
        }

        DB::commit();
        return response()->json([
            'fail' => false,
            'pesan' => 'Data Berhasil Dihapus!',
        ]);
    }

    public function anggota($id, Request $request)
    {
        if ($request->ajax()) {

            $data = DB::table("anggota_eskul as a")
            ->select('a.*', 'b.nis', 'b.nama', 'b.kelas', 'b.hp', 'b.email', 'b.jk', 'b.alamat', 'c.nama as ekskul')
            ->join("anggota as b", "b.id", "=", "a.anggota_id")
            ->join("ekskul as c", "c.id", "=", "a.ekskul_id")
            ->where('a.ekskul_id', $id)
            ->get();

            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($row){
                    $actionBtn = '<a href="'.route('anggota.show', $row->id).'" class="edit btn btn-primary btn-sm">Detail</a>';
                    return $actionBtn;
                })
                ->editColumn('created_at', function ($row) {
                    return Carbon::parse($row->created_at)->translatedFormat('d F Y');
                })
                ->editColumn('status', function ($row) {
                    if($row->status == 'draft'){
                        return '<span class="badge bg-warning">Menunggu Konfirmasi</span>';
                    }else if($row->status == 'aktif'){
                        return '<span class="badge bg-success">Aktif</span>';
                    }else if($row->status == 'tolak'){
                        return '<span class="badge bg-success">Ditolak</span>';
                    }else{
                        return '<span class="badge bg-secondary">Keluar</span>';
                    }
                })
                ->rawColumns(['action', 'status']) 
                ->make(true);
        }
    }

    
    public function json(Request $request)
    {
        $project_id = $request->project_id;
        $data = Task::select('id','nama as title', 'tgl_upload as start')
        ->when(isset($project_id), function($q) use ($project_id){
            return $q->where('project_id', $project_id);
        })
        ->get();

        return response()->json($data);
    }
}
