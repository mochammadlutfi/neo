<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DataTables;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Storage;
use Carbon\Carbon;
use App\Models\Project;
use App\Models\Task;


class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax() || $request->has('ajax')) {
            $order_id = $request->order_id;
            
            $projects = Project::with(['user', 'order' => function($q){
                return $q->with('paket');
            }, 'task' => function($q) {
                $q->select('project_id', 'status', DB::raw('COUNT(*) as count'))
                  ->groupBy('project_id', 'status');
            }])
            ->withCount(['task', 'task as completed_tasks' => function($q) {
                $q->where('status', 'Disetujui');
            }, 'task as pending_tasks' => function($q) {
                $q->where('status', 'Draft');
            }])
            ->when($order_id, function($q) use ($order_id) {
                return $q->where('order_id', $order_id);
            })->latest()->get();

            return response()->json([
                'data' => $projects,
                'success' => true
            ]);
        }
        return view('admin.project.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.project.create',[
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
            'user_id' => 'required',
            'order_id' => 'required',
            'nama' => 'required',
        ];

        $pesan = [
            'user_id.required' => 'Konsumen harus diisi!',
            'order_id.required' => 'No Pesanan harus diisi!',
            'nama.required' => 'Nama harus diisi!',
        ];

        $validator = Validator::make($request->all(), $rules, $pesan);
        if ($validator->fails()){
            return back()->withInput()->withErrors($validator->errors())->withInput();
        }else{
            DB::beginTransaction();
            try{
                $data = new Project();
                $data->order_id = $request->order_id;
                $data->user_id = $request->user_id;
                $data->nama = $request->nama;
                $data->save();

            }catch(\QueryException $e){
                DB::rollback();
                dd($e);
            }

            DB::commit();
            return redirect()->route('admin.project.index');
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
        $data = Project::with(['user', 'order' => function($q){
            return $q->with('paket');
        }])->where('id', $id)->first();
        
        $task = $data->task()->orderBy('created_at', 'DESC')->get();
        
        return view('admin.project.show',[
            'data' => $data,
            'task' => $task
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function calendar($id)
    {
        $data = Project::where('id', $id)->first();
        // dd();
        return view('admin.project.kalender',[
            'data' => $data,
        ]);
    }

    public function edit($id)
    {
        $data = Project::where('id', $id)->first();
        // dd();
        return view('admin.project.edit',[
            'data' => $data,
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
            'harga' => 'required',
            'fitur' => 'required',
        ];

        $pesan = [
            'nama.required' => 'Nama harus diisi!',
            'deskripsi.required' => 'Deskripsi harus diisi!',
            'harga.required' => 'Harga / Bulan harus diisi!',
            'fitur.required' => 'Fitur harus diisi!',
        ];
        
        $validator = Validator::make($request->all(), $rules, $pesan);
        if ($validator->fails()){
            return back()->withInput()->withErrors($validator->errors());
        }else{
            DB::beginTransaction();
            try{
                
                $data = Paket::where('id', $id)->first();
                $data->nama = $request->nama;
                $data->harga = $request->harga;
                $data->deskripsi = $request->deskripsi;
                $data->fitur = $request->fitur;
                $data->save();

            }catch(\QueryException $e){
                DB::rollback();
                dd($e);
            }

            DB::commit();
            return redirect()->route('admin.project.index');
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

            $data = Training::where('id', $id)->first();
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

    /**
     * Get engagement data for a task
     */
    public function getEngagement($taskId)
    {
        $task = Task::where('id', $taskId)->first();
        if (!$task) {
            return response()->json([
                'fail' => true,
                'pesan' => 'Task tidak ditemukan!'
            ]);
        }

        return response()->json([
            'fail' => false,
            'data' => [
                'total_view' => $task->total_view,
                'total_likes' => $task->total_likes,
                'total_comments' => $task->total_comments,
                'total_share' => $task->total_share,
            ]
        ]);
    }

    /**
     * Update engagement data for a task
     */
    public function updateEngagement(Request $request, $taskId)
    {
        $rules = [
            'total_view' => 'required|integer|min:0',
            'total_likes' => 'required|integer|min:0',
            'total_comments' => 'required|integer|min:0',
            'total_share' => 'required|integer|min:0',
        ];

        $pesan = [
            'total_view.required' => 'Total View harus diisi!',
            'total_view.integer' => 'Total View harus berupa angka!',
            'total_likes.required' => 'Total Likes harus diisi!',
            'total_likes.integer' => 'Total Likes harus berupa angka!',
            'total_comments.required' => 'Total Comments harus diisi!',
            'total_comments.integer' => 'Total Comments harus berupa angka!',
            'total_share.required' => 'Total Share harus diisi!',
            'total_share.integer' => 'Total Share harus berupa angka!',
        ];

        $validator = Validator::make($request->all(), $rules, $pesan);
        if ($validator->fails()){
            return response()->json([
                'fail' => true,
                'errors' => $validator->errors()
            ]);
        }

        DB::beginTransaction();
        try{
            $task = Task::where('id', $taskId)->first();
            if (!$task) {
                return response()->json([
                    'fail' => true,
                    'pesan' => 'Task tidak ditemukan!'
                ]);
            }

            $task->total_view = $request->total_view;
            $task->total_likes = $request->total_likes;
            $task->total_comments = $request->total_comments;
            $task->total_share = $request->total_share;
            $task->save();

        }catch(\QueryException $e){
            DB::rollback();
            return response()->json([
                'fail' => true,
                'pesan' => 'Gagal update engagement data!'
            ]);
        }

        DB::commit();
        return response()->json([
            'fail' => false,
            'pesan' => 'Engagement data berhasil diupdate!'
        ]);
    }

    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function status(Request $request)
    {
        $rules = [
            'status' => 'required',
        ];

        $pesan = [
            'status.required' => 'Status Wajib Diisi!',
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
                $data = Training::where('id', $request->id)->first();
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
    }

    
    public function peserta($id, Request $request)
    {
        if ($request->ajax()) {
            $query = UserTraining::with('user')->where('training_id', $id)->get();

            return DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('action', function($row){
                    $btn = '<div class="dropdown">
                        <button type="button" class="btn btn-outline-primary btn-sm dropdown-toggle" id="dropdown-default-outline-primary" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Aksi
                        </button>
                        <div class="dropdown-menu fs-sm" aria-labelledby="dropdown-default-outline-primary" style="">';
                        $btn .= '<a class="dropdown-item" href="'. route('admin.project.edit', $row->id).'"><i class="si si-note me-1"></i>Ubah</a>';
                        $btn .= '<a class="dropdown-item" href="javascript:void(0)" onclick="hapus('. $row->id.')"><i class="si si-trash me-1"></i>Hapus</a>';
                    $btn .= '</div></div>';
                    return $btn; 
                })
                ->editColumn('tgl_training', function ($row) {
                    $tgl_mulai = Carbon::parse($row->tgl_mulai);
                    $tgl_selesai = Carbon::parse($row->tgl_selesai);
                    if($tgl_mulai->eq($tgl_selesai) || $row->tgl_selesai == null){
                        return $tgl_mulai->translatedformat('d M Y');
                    }else{
                        return $tgl_mulai->translatedformat('d') . ' - '. $tgl_selesai->translatedformat('d M Y');
                    }
                })
                ->editColumn('tgl_daftar', function ($row) {
                    $tgl_mulai = Carbon::parse($row->tgl_mulai_daftar);
                    $tgl_selesai = Carbon::parse($row->tgl_selesai_daftar);
                    if($tgl_mulai->eq($tgl_selesai) || $row->tgl_selesai_daftar == null){
                        return $tgl_mulai->translatedformat('d M Y');
                    }else{
                        return $tgl_mulai->translatedformat('d M') . ' - '. $tgl_selesai->translatedformat('d M Y');
                    }
                })
                ->rawColumns(['action',]) 
                ->make(true);
        }

        $data = Training::where('id', $id)->first();
        $user = User::orderBy('nama', 'ASC')->get();

        return view('admin.project.peserta',[
            'data' => $data,
            'user' => $user
        ]);
    }
}
