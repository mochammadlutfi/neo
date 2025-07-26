<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Routing\Controller;
use Carbon\Carbon;
use Storage;
use DataTables;
use PDF;
use App\Models\User;
use App\Models\Pembayaran;

class PembayaranController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $order_id = $request->order_id;
            $data = Pembayaran::with(['order' => function($q){
                return $q->with('user');
            }])
            ->when(!empty($order_id), function($q) use ($order_id) {
                return $q->where('order_id', $order_id);
            })
            ->orderBy('id', 'DESC')->get();

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($row){
                    $btn = '<button type="button" class="btn btn-primary btn-sm" onclick="modalShow('. $row->id .')">Detail</a>';
                    return $btn; 
                })
                ->editColumn('tgl', function ($row) {
                    $tgl =  Carbon::parse($row->tgl)->translatedFormat('d F Y');
                    return $tgl;
                })
                ->editColumn('created_at', function ($row) {
                    $tgl = Carbon::parse($row->created_at);

                    return $tgl->translatedFormat('d M Y');
                })
                ->editColumn('jumlah', function ($row) {

                    return 'Rp '.number_format($row->jumlah,0,',','.');
                })
                ->editColumn('status', function ($row) {
                    if($row->status == 'belum bayar'){
                        return '<span class="badge bg-danger">Belum Bayar</span>';
                    }else if($row->status == 'sebagian'){
                        return '<span class="badge bg-warning">Sebagian</span>';
                    }else if($row->status == 'pending'){
                        return '<span class="badge bg-primary">Pending</span>';
                    }else if($row->status == 'terima'){
                        return '<span class="badge bg-success">Diterima</span>';
                    }else if($row->status == 'tolak'){
                        return '<span class="badge bg-secondary">Ditolak</span>';
                    }
                })
                ->rawColumns(['action', 'status', 'jumlah']) 
                ->make(true);
        }
        return view('admin.pembayaran');
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
            'tgl' => 'required',
            'jumlah' => 'required',
            'bukti' => 'required',
        ];

        $pesan = [
            'tgl.required' => 'Tanggal Bayar Wajib Diisi!',
            'jumlah.required' => 'Jumlah Wajib Diisi!',
            // 'jumlah.max' => 'Jumlah Pembayaran Maksimal Rp '.number_format($max,0,',','.'),
            'bukti.required' => 'Bukti Pembayaran Wajib Diisi!',
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
                $data = new Pembayaran();
                $data->order_id = $request->order_id;
                $data->tgl = Carbon::parse($request->tgl);
                $data->jumlah = $request->jumlah;
                $data->status = 'pending';

                if($request->bukti){
                    $fileName = time() . '.' . $request->bukti->extension();
                    Storage::disk('public')->putFileAs('uploads/pembayaran', $request->bukti, $fileName);
                    $data->bukti = '/uploads/pembayaran/'.$fileName;
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
        $data = Pembayaran::with(['order'])->where('id', $id)->first();
        $html = '<div class="block-content p-4">
            <div class="row">
                <div class="col-md-8">
                    <div class="row mb-3">
                        <label class="col-sm-4 fw-medium">No Pemesanan</label>
                        <div class="col-sm-6">
                            : '. $data->order->nomor .'
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-sm-4 fw-medium">Konsumen</label>
                        <div class="col-sm-6">
                            : '. $data->order->user->nama .'
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-sm-4 fw-medium">Tanggal Bayar</label>
                        <div class="col-sm-6">
                            : '. Carbon::parse($data->tgl)->translatedFormat('d F Y') .'
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-sm-4 fw-medium">Jumlah Bayar</label>
                        <div class="col-sm-6">
                            : Rp '. number_format($data->jumlah,0,',','.') .'
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <label>Foto</label>
                    <img src="'. $data->bukti .'" class="img-fluid"/>
                </div>
            </div>
        </div>';
        
        $action = '';

        
        $action .= '<div class="block-content p-4 border-top border-2">
            <div class="row justify-space-between">
                <div class="col-md-6"> 
                    <button type="button" class="btn px-4 rounded-pill btn-alt-danger" data-bs-dismiss="modal" onclick="hapus('.$data->id .')">
                        <i class="si si-trash me-1"></i>
                        Hapus
                    </button>
                </div>
                <div class="col-md-6 text-end">';

        
        if($data->status == 'pending'){
        $action .= '<button type="button" class="btn px-4 rounded-pill btn-alt-danger" data-bs-dismiss="modal" onclick="updateStatus('.$data->id .', `tolak`)">
                    <i class="fa fa-times me-1"></i>
                    Tolak
                </button>
                <button type="submit" class="btn px-4 rounded-pill btn-alt-primary" onclick="updateStatus('.$data->id .', `terima`)">
                    <i class="fa fa-check me-1"></i>
                    Konfirmasi
                </button>';

        }
                '</div>
            </div>
        </div>
        ';

        $html .= $action;

        echo $html;
    }

    
    public function status($id, Request $request)
    {
        DB::beginTransaction();
        try{
            $data = Pembayaran::where('id', $id)->first();
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
        $data = Ekskul::where('id', $id)->first();
        $pembina = User::where('level', 'pembina')->orderBy('nama', 'ASC')->get();
        return view('ekskul.edit',[
            'pembina' => $pembina,
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
        // dd($request->all());
        $rules = [
            'nama' => 'required',
            'pembina_id' => 'required',
            'deskripsi' => 'required',
            'tempat' => 'required',
            'jadwal' => 'required',
            'mulai' => 'required',
            'selesai' => 'required',
        ];

        $pesan = [
            'nama.required' => 'Nama Wajib Diisi!',
            'pembina_id.required' => 'Pembina Wajib Diisi!',
            'deskripsi.required' => 'Deskripsi Wajib Diisi!',
            'tempat.required' => 'Tempat Wajib Diisi!',
            'mulai.required' => 'Jam Mulai Wajib Diisi!',
            'selesai.required' => 'Jam Selesai Wajib Diisi!',
        ];


        $validator = Validator::make($request->all(), $rules, $pesan);
        if ($validator->fails()){
            return back()->withInput()->withErrors($validator->errors());
        }else{
            DB::beginTransaction();
            try{

                $data = Ekskul::where('id', $id)->first();
                $data->nama = $request->nama;
                $data->pembina_id = $request->pembina_id;
                $data->deskripsi = $request->deskripsi;
                $data->tempat = $request->tempat;
                $data->jadwal = json_encode($request->jadwal);
                $data->mulai = $request->mulai;
                $data->selesai = $request->selesai;
                $data->status = $request->status;
                if($request->foto){
                    if(!empty($data->foto)){
                        $cek = Storage::disk('public')->exists($data->foto);
                        if($cek)
                        {
                            Storage::disk('public')->delete($data->foto);
                        }
                    }
                    $fileName = time() . '.' . $request->foto->extension();
                    Storage::disk('public')->putFileAs('uploads/ekskul', $request->foto, $fileName);
                    $data->foto = '/uploads/ekskul/'.$fileName;
                }
                $data->save();

            }catch(\QueryException $e){
                DB::rollback();
                back()->withInput()->withErrors($validator->errors());
            }

            DB::commit();
            return redirect()->route('ekskul.index');
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

            $data = Pembayaran::where('id', $id)->first();
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

    
    public function cek(Request $request)
    {
        dd($request->all());
    }

    
    private function getNumber()
    {
        $q = Booking::select(DB::raw('MAX(RIGHT(nomor,5)) AS kd_max'));

        $code = 'BKN/';
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
    
    public function report(Request $request)
    {
        $tgl = explode(" - ",$request->tgl);
        $status = $request->status;

        $data = Pembayaran::with(['order' => function($q){
            return $q->with('user');
        }])
        ->when(!empty($status), function($q) use ($status) {
            return $q->where('status', $status);
        })
        ->whereBetween('tgl', $tgl)
        ->orderBy('id', 'DESC')->get();

        
        $config = [
            'format' => 'A4-L' // Landscape
        ];

        $pdf = PDF::loadView('reports.pembayaran', [
            'data' => $data,
            'tgl' =>$tgl
        ], [ ], $config);

        return $pdf->stream('Laporan Pembayaran'. $request->tgl .'.pdf');

    }
}
