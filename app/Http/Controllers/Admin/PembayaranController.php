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
use App\Models\Order;
use App\Notifications\PaymentStatusNotification;

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
                    $btn = '<div class="btn-group" role="group">';
                    $btn .= '<button type="button" class="btn btn-primary btn-sm dropdown-toggle" data-boundary="window" data-bs-toggle="dropdown" aria-expanded="false">';
                    $btn .= '<i class="fa fa-cog me-1"></i>Aksi';
                    $btn .= '</button>';
                    $btn .= '<ul class="dropdown-menu">';
                    $btn .= '<li><a class="dropdown-item" href="javascript:void(0)" onclick="modalShow('. $row->id .')"><i class="fa fa-eye me-2"></i>Detail</a></li>';
                    
                    // Hanya tampilkan Edit dan Hapus jika status bukan 'terima'
                    if ($row->status !== 'terima') {
                        $btn .= '<li><a class="dropdown-item" href="javascript:void(0)" onclick="editPayment('. $row->id .')"><i class="fa fa-edit me-2"></i>Edit</a></li>';
                        $btn .= '<li><hr class="dropdown-divider"></li>';
                        $btn .= '<li><a class="dropdown-item text-danger" href="javascript:void(0)" onclick="hapus('. $row->id .')"><i class="fa fa-trash me-2"></i>Hapus</a></li>';
                    } else {
                        $btn .= '<li><hr class="dropdown-divider"></li>';
                        $btn .= '<li><span class="dropdown-item-text text-muted"><i class="fa fa-lock me-2"></i>Pembayaran sudah diterima</span></li>';
                    }
                    
                    $btn .= '</ul>';
                    $btn .= '</div>';
                    
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
                    $data->bukti = '/storage/uploads/pembayaran/'.$fileName;
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
            $data = Pembayaran::with(['order.user'])->where('id', $id)->first();
            
            if (!$data) {
                return response()->json([
                    'fail' => true,
                    'pesan' => 'Data pembayaran tidak ditemukan',
                ]);
            }

            $oldStatus = $data->status;
            $data->status = $request->status;
            $data->save();

            // Kirim email notifikasi jika status diubah menjadi 'terima'
            if ($request->status === 'terima' && $oldStatus !== 'terima') {
                $this->sendPaymentStatusNotification($data);
            }

        }catch(\QueryException $e){
            DB::rollback();
            return response()->json([
                'fail' => true,
                'pesan' => $e->getMessage(),
            ]);
        }

        DB::commit();
        return response()->json([
            'fail' => false,
        ]);
    }

    /**
     * Send payment status notification to user
     */
    private function sendPaymentStatusNotification(Pembayaran $pembayaran)
    {
        try {
            $order = $pembayaran->order;
            $user = $order->user;
            
            // Hitung total pembayaran yang sudah diterima untuk order ini
            $totalPembayaranDiterima = Pembayaran::where('order_id', $order->id)
                ->where('status', 'terima')
                ->sum('jumlah');
            
            // Hitung sisa pembayaran
            $sisaPembayaran = max(0, $order->total - $totalPembayaranDiterima);
            
            // Tentukan status pembayaran menggunakan accessor
            $statusPembayaran = $order->status_pembayaran;
            
            // Kirim notifikasi email
            $user->notify(new PaymentStatusNotification($pembayaran, $sisaPembayaran, $statusPembayaran));
            
            \Log::info('Payment status notification sent to user: ' . $user->id . ' for payment: ' . $pembayaran->id);
            
        } catch (\Exception $e) {
            \Log::error('Failed to send payment status notification: ' . $e->getMessage());
            // Jangan gagalkan transaction jika email gagal dikirim
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data = Pembayaran::with(['order'])->where('id', $id)->first();
        
        if (!$data) {
            return response()->json([
                'fail' => true,
                'message' => 'Data tidak ditemukan'
            ]);
        }
        
        // Check apakah pembayaran sudah diterima
        if ($data->status === 'terima') {
            return response()->json([
                'fail' => true,
                'message' => 'Pembayaran yang sudah diterima tidak dapat diubah'
            ]);
        }
        
        return response()->json([
            'fail' => false,
            'data' => [
                'id' => $data->id,
                'order_id' => $data->order_id,
                'order_nomor' => $data->order->nomor,
                'tgl' => $data->tgl->format('Y-m-d'),
                'jumlah' => $data->jumlah,
                'bukti' => $data->bukti,
                'status' => $data->status
            ]
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
        // Check apakah pembayaran sudah diterima
        $pembayaran = Pembayaran::find($id);
        if (!$pembayaran) {
            return response()->json([
                'fail' => true,
                'message' => 'Data tidak ditemukan'
            ]);
        }
        
        if ($pembayaran->status === 'terima') {
            return response()->json([
                'fail' => true,
                'message' => 'Pembayaran yang sudah diterima tidak dapat diubah'
            ]);
        }
        
        $rules = [
            'order_id' => 'required',
            'tgl' => 'required',
            'jumlah' => 'required|numeric|min:1',
        ];

        $pesan = [
            'order_id.required' => 'Pesanan Wajib Diisi!',
            'tgl.required' => 'Tanggal Bayar Wajib Diisi!',
            'jumlah.required' => 'Jumlah Wajib Diisi!',
            'jumlah.numeric' => 'Jumlah harus berupa angka!',
            'jumlah.min' => 'Jumlah minimal 1!',
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
                $data = $pembayaran;
                
                $data->order_id = $request->order_id;
                $data->tgl = Carbon::parse($request->tgl);
                $data->jumlah = $request->jumlah;
                
                // Handle file upload jika ada bukti baru
                if($request->hasFile('bukti')){
                    // Delete old file if exists
                    if(!empty($data->bukti)){
                        $oldFile = str_replace('/storage', '', $data->bukti);
                        if(Storage::disk('public')->exists($oldFile)){
                            Storage::disk('public')->delete($oldFile);
                        }
                    }
                    
                    $fileName = time() . '.' . $request->bukti->extension();
                    Storage::disk('public')->putFileAs('uploads/pembayaran', $request->bukti, $fileName);
                    $data->bukti = '/storage/uploads/pembayaran/'.$fileName;
                }
                
                $data->save();

            }catch(\QueryException $e){
                DB::rollback();
                return response()->json([
                    'fail' => true,
                    'message' => 'Gagal update data: ' . $e->getMessage()
                ]);
            }

            DB::commit();
            return response()->json([
                'fail' => false,
                'message' => 'Data berhasil diupdate'
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
        $data = Pembayaran::find($id);
        
        if (!$data) {
            return response()->json([
                'fail' => true,
                'pesan' => 'Data tidak ditemukan!',
            ]);
        }
        
        // Check apakah pembayaran sudah diterima
        if ($data->status === 'terima') {
            return response()->json([
                'fail' => true,
                'pesan' => 'Pembayaran yang sudah diterima tidak dapat dihapus!',
            ]);
        }
        
        DB::beginTransaction();
        try{
            // Delete file bukti jika ada
            if(!empty($data->bukti)){
                $oldFile = str_replace('/storage', '', $data->bukti);
                if(Storage::disk('public')->exists($oldFile)){
                    Storage::disk('public')->delete($oldFile);
                }
            }
            
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
