<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DataTables;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Storage;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Journey;
use App\Models\JourneyItem;
use PDF;


class JourneyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        $data = Journey::with(['user', 'order'])->latest()->get();

        return view('admin.journey.index',[
            'data' => $data
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $customer = User::select('id as value', 'nama as label')->latest()->get();

        return view('admin.journey.form',[
            'konsumen' => $customer
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
            'customer_id' => 'required',
            'order_id' => 'required',
            'goal' => 'required',
        ];

        $pesan = [
            'customer_id.required' => 'Konsumen harus diisi!',
            'order_id.required' => 'No Pesanan harus diisi!',
            'goal.required' => 'Goal harus diisi!',
        ];

        $validator = Validator::make($request->all(), $rules, $pesan);
        if ($validator->fails()){
            return back()->withInput()->withErrors($validator->errors());
        }else{
            DB::beginTransaction();
            try{
                $data = new Journey();
                $data->user_id = $request->customer_id;
                $data->order_id = $request->order_id;
                $data->goal = $request->goal;
                $data->status = 'aktif';
                $data->save();

            }catch(\QueryException $e){
                DB::rollback();
            }

            DB::commit();
            return redirect()->route('admin.journey.show', $data->id);
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

        $data = Journey::with('user')->where('id', $id)->first();

        return view('admin.journey.show',[
            'data' => $data
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
        $customer = User::select('id as value', 'nama as label')->latest()->get();
        $data = Journey::where('id', $id)->first();
        // dd();
        return view('admin.journey.form',[
            'konsumen' => $customer,
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
            'customer_id' => 'required',
            'order_id' => 'required',
            'goal' => 'required',
        ];

        $pesan = [
            'customer_id.required' => 'Konsumen harus diisi!',
            'order_id.required' => 'No Pesanan harus diisi!',
            'goal.required' => 'Goal harus diisi!',
        ];
        
        $validator = Validator::make($request->all(), $rules, $pesan);
        if ($validator->fails()){
            return back()->withInput()->withErrors($validator->errors());
        }else{
            DB::beginTransaction();
            try{
                
                $data = Journey::where('id', $id)->first();
                $data->user_id = $request->customer_id;
                $data->order_id = $request->order_id;
                $data->goal = $request->goal;
                $data->save();

            }catch(\QueryException $e){
                DB::rollback();
                dd($e);
            }

            DB::commit();
            return redirect()->route('admin.journey.show', $id);
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

            $data = Journey::where('id', $id)->first();
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

    public function pdf($id)
    {

        $data = Journey::with(['user', 'order'])
        ->where('id', $id)
        ->first();

        $stage = JourneyItem::where('journey_id', $id)
        ->orderBy('id', 'ASC')->get();

        $config = [
            'format' => 'A4-L' // Landscape
        ];

        $pdf = PDF::loadView('reports.journey', [
            'data' => $data,
            'stage' => $stage
        ], [ ], $config);

        return $pdf->stream('Customer Journey.pdf');
    }
}
