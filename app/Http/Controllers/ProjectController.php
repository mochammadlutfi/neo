<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use DataTables;
use Carbon\Carbon;
use App\Models\Order;
use App\Models\Project;
use App\Models\Task;

use App\Services\Midtrans\CreateSnapTokenService;
use App\Services\Midtrans\CallbackService;
use Storage;
use PDF;
class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $data = Project::with(['order'])
        ->withCount('task')
        ->latest()->get();
        
        return view('landing.project.index',[
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
        $data = Project::where('id', $id)->first();
        $task = Task::with(['project'])
        ->where('project_id', $id)
        ->orderBy('id', 'DESC')->get();

        return view('landing.project.show', [
            'data' => $data,
            'task' =>  $task
        ]);
    }
    
    public function calendar($id)
    {
        $user = auth()->guard('web')->user();
        $data = Project::where('id', $id)->first();

        return view('landing.project.kalender', [
            'data' => $data,
        ]);
    }

    public function status($id, Request $request)
    {
        
        DB::beginTransaction();
        try{

            $data = Task::where('id', $id)->first();
            $data->catatan = $request->catatan;
            $data->status = $request->status;
            $data->save();

        }catch(\QueryException $e){
            DB::rollback();
            back()->withInput()->withErrors($validator->errors());
        }

        DB::commit();

        return redirect()->back();
    }

    
}
