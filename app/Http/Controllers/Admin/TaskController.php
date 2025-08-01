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
                    }else if($row->status == 'Direvisi'){
                        return '<span class="badge bg-info">Direvisi</span>';
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
        
        // Return admin task detail component for AJAX requests
        return view('components.admin-task-detail-content', ['data' => $data])->render();
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
                
                // Store original status to check if it was rejected
                $originalStatus = $data->status;
                
                $data->project_id = $request->project_id;
                $data->nama = $request->nama;
                $data->link_brief = $request->link_brief;
                $data->tgl_tempo = $request->tgl_tempo;
                $data->tgl_upload = $request->tgl_upload;
                
                // If task was previously rejected and now being updated, set status to 'Direvisi'
                if($originalStatus == 'Ditolak'){
                    $data->status = 'Direvisi';
                }

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

    /**
     * Mark task as uploaded
     */
    public function markAsUploaded($id)
    {
        DB::beginTransaction();
        try {
            $task = Task::where('id', $id)->first();
            
            if (!$task) {
                return response()->json([
                    'fail' => true,
                    'pesan' => 'Task tidak ditemukan!'
                ]);
            }

            $task->status_upload = 1;
            $task->save();

        } catch(\QueryException $e) {
            DB::rollback();
            return response()->json([
                'fail' => true,
                'pesan' => 'Gagal mengubah status upload!'
            ]);
        }

        DB::commit();
        return response()->json([
            'fail' => false,
            'pesan' => 'Status upload berhasil diubah!'
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
                'bukti' => $task->bukti
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
            'bukti' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120', // 5MB max
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
            'bukti.file' => 'Bukti harus berupa file!',
            'bukti.mimes' => 'Format bukti harus: JPG, JPEG, PNG, atau PDF!',
            'bukti.max' => 'Ukuran bukti maksimal 5MB!',
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

            // Update engagement data
            $task->total_view = $request->total_view;
            $task->total_likes = $request->total_likes;
            $task->total_comments = $request->total_comments;
            $task->total_share = $request->total_share;

            // Handle file upload if provided
            if ($request->hasFile('bukti')) {
                // Delete old file if exists
                if (!empty($task->bukti)) {
                    $oldFile = str_replace('/storage', '', $task->bukti);
                    if (Storage::disk('public')->exists($oldFile)) {
                        Storage::disk('public')->delete($oldFile);
                    }
                }

                $fileName = time() . '_' . $taskId . '.' . $request->bukti->extension();
                Storage::disk('public')->putFileAs('uploads/engagement', $request->bukti, $fileName);
                $task->bukti = '/uploads/engagement/' . $fileName;
            }

            $task->save();

        } catch(\QueryException $e) {
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
}
