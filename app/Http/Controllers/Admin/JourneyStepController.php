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


class JourneyStepController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $data = JourneyItem::when($request->journey_id, function($e, $id){
            return $e->where('journey_id', $id);
        })
        ->latest()->get();

        return response()->json($data);
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
            'stage' => 'required',
            'experience' => 'required',
            'opportunities' => 'required',
            'expectation' => 'required',
            'feeling' => 'required',
            'touch_point' => 'required',
        ];

        $pesan = [
            'stage.required' => 'Stage harus diisi!',
            'experience.required' => 'Experience harus diisi!',
            'opportunities.required' => 'Opportunities harus diisi!',
            'expectation.required' => 'Expectation harus diisi!',
            'feeling.required' => 'Feeling harus diisi!',
            'touch_point.required' => 'Touch Point harus diisi!',
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
                $data = new JourneyItem();
                $data->journey_id = $request->journey_id;
                $data->stage = $request->stage;
                $data->experience = $request->experience;
                $data->opportunities = $request->opportunities;
                $data->expectation = $request->expectation;
                $data->feeling = $request->feeling;
                $data->touch_point = $request->touch_point;
                $data->save();

            }catch(\QueryException $e){
                DB::rollback();
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

        $data = JourneyItem::where('id', $id)->first();

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
        $rules = [
            'stage' => 'required',
            'experience' => 'required',
            'opportunities' => 'required',
            'expectation' => 'required',
            'feeling' => 'required',
            'touch_point' => 'required',
        ];

        $pesan = [
            'stage.required' => 'Stage harus diisi!',
            'experience.required' => 'Experience harus diisi!',
            'opportunities.required' => 'Opportunities harus diisi!',
            'expectation.required' => 'Expectation harus diisi!',
            'feeling.required' => 'Feeling harus diisi!',
            'touch_point.required' => 'Touch Point harus diisi!',
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
                
                $data = JourneyItem::where('id', $id)->first();
                $data->journey_id = $request->journey_id;
                $data->stage = $request->stage;
                $data->experience = $request->experience;
                $data->opportunities = $request->opportunities;
                $data->expectation = $request->expectation;
                $data->feeling = $request->feeling;
                $data->touch_point = $request->touch_point;
                $data->save();


            }catch(\QueryException $e){
                DB::rollback();
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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        DB::beginTransaction();
        try{

            $data = JourneyItem::where('id', $id)->first();
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

    
    public function select(Request $request)
    {
        if(!isset($request->searchTerm)){
            $fetchData = Paket::orderBy('created_at', 'DESC')->get();
          }else{
            $cari = $request->searchTerm;
            $fetchData = Paket::where('nama','LIKE',  '%' . $cari .'%')->orderBy('created_at', 'DESC')->get();
          }

          $data = array();
          foreach($fetchData as $row) {
            $data[] = array("id" =>$row->id, "text"=>$row->nama);
          }

          return response()->json($data);
    }

    
    public function json(Request $request)
    {
        if(!isset($request->id)){
            $data = Paket::where('id', $request->id)->get();
        }else{
            $data = Paket::where('id', $request->id)->first();
        }
        // $query = Paket::

          return response()->json($data);
    }
}
