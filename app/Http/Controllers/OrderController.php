<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use DataTables;
use Carbon\Carbon;
use App\Models\Order;
use App\Models\Paket;
use App\Models\Pembayaran;
use App\Mail\OrderCreated;
use Illuminate\Support\Facades\Mail;

use App\Services\Midtrans\CreateSnapTokenService;
use App\Services\Midtrans\CallbackService;
use Storage;
use PDF;
class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $data = Training::orderBy('id', 'DESC')->whereNotIn('status', ['draft'])
        ->paginate(9);

        return view('landing.training.index',[
            'data' => $data
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
        $user = auth()->guard('web')->user();
        $data = Order::with(['project' => function($q){
            return $q->withCount('task');
        }, 'payment'])
        ->where('id', $id)->first();

        return view('landing.order.show', [
            'data' => $data,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        DB::beginTransaction();
        try{
            $user = auth()->guard('web')->user();

            $paket = Paket::where('id', $request->paket_id)->first();

            $data = new Order();
            $data->nomor = $this->getNumber($paket->kode);
            $data->user_id = $user->id;
            $data->paket_id = $request->paket_id;
            $data->durasi = $request->lama;
            $data->tgl = Carbon::today();
            $data->harga = $request->harga;
            $data->total = $request->total;
            
            // Calculate tgl_selesai (order date + duration in months)
            $data->tgl_selesai = Carbon::today()->addMonths($request->lama);
            
            // Calculate tgl_tempo (tgl_selesai - 1 week)
            $data->tgl_tempo = Carbon::today()->addMonths($request->lama)->subWeek();
            
            $data->save();

            // Load paket relationship for email
            $data->load('paket', 'user');

            // Send email notification to user
            Mail::to($user->email)->send(new OrderCreated($data));

        }catch(\QueryException $e){
            DB::rollback();
            dd($e);
        }

        DB::commit();
        return redirect()->route('user.order.payment', $data->id);
    }

    public function payment($id)
    {
        $data = Order::where('id', $id)->first();

        return view('landing.order.payment',[
            'data' => $data
        ]);
    }

    public function invoice($id)
    {
        $user = auth()->guard('web')->user();
        $order = Order::with(['paket', 'user', 'payment'])
            ->where('id', $id)
            ->where('user_id', $user->id)
            ->first();

        if (!$order) {
            abort(404, 'Order not found');
        }

        $config = [
            'format' => 'A4'
        ];
        
        $pdf = PDF::loadView('reports.invoice', [
            'order' => $order
        ], [], $config);

        return $pdf->stream('Invoice-' . $order->nomor . '.pdf');
    }

    public function update($id, Request $request)
    {
        DB::beginTransaction();
        try{
            $user = auth()->guard('web')->user();

            $data = new Pembayaran();
            $data->order_id = $id;
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

    public function paymentData($id, Request $request)
    {
        if ($request->ajax()) {
            $user = auth()->guard('web')->user();

            $data = Pembayaran::with(['order'])
            ->where('order_id', $id)
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
                    }else if($row->status == 'lunas'){
                        return '<span class="badge bg-success">Lunas</span>';
                    }else if($row->status == 'batal'){
                        return '<span class="badge bg-secondary">Batal</span>';
                    }
                })
                ->rawColumns(['action', 'status', 'jumlah']) 
                ->make(true);
        }
        return view('admin.pembayaran');
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function user(Request $request)
    {
        $user = auth()->guard('web')->user();

        $data = Order::with(['paket'])->where('user_id', $user->id)->latest()->get();
        if ($request->ajax()) {

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($row){
                    if($row->status_pembayaran == 'Belum Bayar'){
                        $btn = '<a class="btn btn-primary btn-sm" href="'. route('user.order.payment', $row->id).'"><i class="si si-eye me-1"></i>Selesaikan Pembayaran</a>';
                    }else{
                        $btn = '<a class="btn btn-primary btn-sm" href="'. route('user.order.show', $row->id).'"><i class="si si-eye me-1"></i>Detail</a>';
                    }

                    return $btn; 
                })
                ->editColumn('durasi', function ($row) {

                    return $row->durasi .' Bulan';
                })
                ->editColumn('tgl', function ($row) {
                    return Carbon::parse($row->tgl)->translatedformat('d M Y');
                })
                ->editColumn('status', function ($row) {
                    if($row->status == 'draft'){
                        return '<span class="badge bg-warning">Draft</span>';
                    }else if($row->status == 'buka'){
                        return '<span class="badge bg-primary">Buka</span>';
                    }else{
                        return '<span class="badge bg-danger">Tutup</span>';
                    }
                })
                ->rawColumns(['action', 'status']) 
                ->make(true);
        }
        return view('landing.order.index', [
            'data' => $data
        ]);
    }

    private function getNumber($kode)
    {
        $q = Order::select(DB::raw('MAX(RIGHT(nomor,5)) AS kd_max'));

        $code = 'ORD.';
        $no = 1;
        date_default_timezone_set('Asia/Jakarta');

        if($q->count() > 0){
            foreach($q->get() as $k){
                return $code .'-'. $kode. date('ym') .'-'.sprintf("%05s", abs(((int)$k->kd_max) + 1));
            }
        }else{
            return $code .'-'. $kode . date('ym') .'-'. sprintf("%05s", $no);
        }
    }
}
