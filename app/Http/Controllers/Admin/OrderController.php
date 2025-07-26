<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DataTables;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Storage;
use Carbon\Carbon;
use App\Models\Order;
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
        if ($request->ajax()) {
            $user_id = $request->user_id;
            $data = Order::with(['user', 'paket'])
            ->when(!empty($user_id), function($q) use ($user_id) {
                return $q->where('user_id', $user_id);
            })
            ->latest()->get();

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($row){
                    $btn = '<a class="btn btn-sm btn-primary" href="'. route('admin.order.show', $row->id).'"><i class="si si-eye me-1"></i>Detail</a>';
                    return $btn; 
                })
                ->editColumn('tgl', function ($row) {
                    return Carbon::parse($row->tgl)->translatedformat('d M Y');
                })
                ->editColumn('durasi', function ($row) {
                    return $row->durasi .' Bulan';
                })
                ->addColumn('tgl_selesai', function ($row) {
                    return Carbon::parse($row->tgl)->addMonth($row->durasi)->translatedformat('d M Y');
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
                ->rawColumns(['action', 'status', 'tgl_selesai']) 
                ->make(true);
        }
        return view('admin.order.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.order.create',[
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
            'paket_id' => 'required',
            'tgl' => 'required',
            'durasi' => 'required',
        ];

        $pesan = [
            'user_id.required' => 'Konsumen harus diisi!',
            'paket_id.required' => 'Paket harus diisi!',
            'tgl.required' => 'Tanggal harus diisi!',
            'durasi.required' => 'Durasi harus diisi!',
        ];

        $validator = Validator::make($request->all(), $rules, $pesan);
        if ($validator->fails()){
            return back()->withInput()->withErrors($validator->errors())->withInput();
        }else{
            DB::beginTransaction();
            try{
                $data = new Order();
                $data->nomor = $this->getNumber();
                $data->user_id = $request->user_id;
                $data->paket_id = $request->paket_id;
                $data->durasi = $request->durasi;
                $data->tgl = $request->tgl;
                $data->harga = $request->harga;
                $data->total = $request->total;
                $data->save();

            }catch(\QueryException $e){
                DB::rollback();
                dd($e);
            }

            DB::commit();
            return redirect()->route('admin.order.index');
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
        $data = Order::where('id', $id)->first();
        // dd();
        return view('admin.order.show',[
            'data' => $data,
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
        $data = Order::where('id', $id)->first();
        // dd();
        return view('admin.order.edit',[
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
            'user_id' => 'required',
            'paket_id' => 'required',
            'tgl' => 'required',
            'durasi' => 'required',
        ];

        $pesan = [
            'user_id.required' => 'Konsumen harus diisi!',
            'paket_id.required' => 'Paket harus diisi!',
            'tgl.required' => 'Tanggal harus diisi!',
            'durasi.required' => 'Durasi harus diisi!',
        ];
        
        $validator = Validator::make($request->all(), $rules, $pesan);
        if ($validator->fails()){
            return back()->withInput()->withErrors($validator->errors());
        }else{
            DB::beginTransaction();
            try{
                
                $data = Order::where('id', $id)->first();
                $data->user_id = $request->user_id;
                $data->paket_id = $request->paket_id;
                $data->durasi = $request->durasi;
                $data->tgl = $request->tgl;
                $data->harga = $request->harga;
                $data->total = $request->total;
                $data->save();

            }catch(\QueryException $e){
                DB::rollback();
                dd($e);
            }

            DB::commit();
            return redirect()->route('admin.order.index');
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

            $data = Order::where('id', $id)->first();
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

    
    private function getNumber()
    {
        $q = Order::select(DB::raw('MAX(RIGHT(nomor,5)) AS kd_max'));

        $code = 'ORD.';
        $no = 1;
        date_default_timezone_set('Asia/Jakarta');

        if($q->count() > 0){
            foreach($q->get() as $k){
                return $code . date('ym') .'-'.sprintf("%05s", abs(((int)$k->kd_max) + 1));
            }
        }else{
            return $code . date('ym') .'-'. sprintf("%05s", $no);
        }
    }

    
    public function select(Request $request)
    {
            $cari = $request->searchTerm;
            $user_id = $request->user_id;
            $fetchData = Order::
            when(isset($cari), function($q) use($cari){
                return $q->where('nomor','LIKE',  '%' . $cari .'%');
            })->when(isset($user_id), function($q) use($user_id){
                return $q->where('user_id', $user_id);
            })
            ->orderBy('created_at', 'DESC')->get();
        //   }

          $data = array();
          foreach($fetchData as $row) {
            $data[] = array("id" =>$row->id, "text"=> $row->nomor);
          }

          return response()->json($data);
    }

    
    public function json(Request $request)
    {
        $elq = Order::when($request->id, function($q, $id){
            return $q->where('id', $id);
        })->when($request->user_id, function($q, $id){
            return $q->where('user_id', $id);
        });
        if($request->id){
            $data = $elq->first();
            // $data = Order::where('id', $request->id)->get();
        }else{
            // $data = Order::where('id', $request->id)->first();
            $data = $elq->get();
        }

          return response()->json($data);
    }

    public function report(Request $request)
    {
        $tgl = explode(" - ",$request->tgl);
        $data = Order::with(['user', 'paket'])
        ->withCount('project')
        ->whereBetween('tgl', $tgl)
        ->latest()->get();
        $config = [
            'format' => 'A4-L' // Landscape
        ];

        $pdf = PDF::loadView('reports.order', [
            'data' => $data,
            'tgl' =>$tgl
        ], [ ], $config);

        return $pdf->stream('Laporan Pesanan.pdf');

    }
}
