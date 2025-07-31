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
        $user = auth()->guard('web')->user();

        $data = Project::with(['order'])
        ->where('user_id', $user->id)
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

    public function pdfReport($id)
    {
        $user = auth()->guard('web')->user();
        $data = Project::with(['order.user', 'order.paket'])->where('id', $id)->first();
        
        if (!$data) {
            abort(404, 'Project not found');
        }


        $tasks = Task::where('project_id', $id)->orderBy('tgl_tempo', 'asc')->get();

        // Calculate statistics
        $totalTasks = $tasks->count();
        $completedTasks = $tasks->where('status', 'Disetujui')->count();
        $pendingTasks = $tasks->where('status', 'Draft')->count();
        $rejectedTasks = $tasks->where('status', 'Ditolak')->count();
        $uploadedTasks = $tasks->where('status_upload', 1)->count();

        // Calculate engagement metrics
        $totalViews = $tasks->sum('total_view');
        $totalLikes = $tasks->sum('total_likes');
        $totalComments = $tasks->sum('total_comments');
        $totalShares = $tasks->sum('total_share');
        $totalEngagement = $totalLikes + $totalComments + $totalShares;
        $engagementRate = $totalViews > 0 ? ($totalEngagement / $totalViews) * 100 : 0;

        $config = [
            'format' => 'A4',
            'orientation' => 'P'
        ];
        
        $pdf = PDF::loadView('reports.project-insights', [
            'project' => $data,
            'tasks' => $tasks,
            'statistics' => [
                'total_tasks' => $totalTasks,
                'completed_tasks' => $completedTasks,
                'pending_tasks' => $pendingTasks,
                'rejected_tasks' => $rejectedTasks,
                'uploaded_tasks' => $uploadedTasks,
                'total_views' => $totalViews,
                'total_likes' => $totalLikes,
                'total_comments' => $totalComments,
                'total_shares' => $totalShares,
                'total_engagement' => $totalEngagement,
                'engagement_rate' => $engagementRate
            ]
        ], [], $config);

        return $pdf->stream('Laporan-Insights-' . $data->nama . '.pdf');
    }
}
