<?php
namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class ProjectController extends Controller
{
    public function createProject(Request $request){
        try{
            $request_data = $request->all();

            $project = new Project();

            $project->name = $request['name'];
            $project->type = $request['type'];
            $project->description = $request['description'];
            $project->entity = $request['entity'];
            $project->url = $request['url'];
            $project->access = $request['access'];
            $project->data_source = $request['data_source'];
            //$project->dataset = $request['dataset'];

            $project->save();

            return response(['message' => 'Project created'], 200);


        }catch(\Exception $e){
            Log::error($e);
            return false;
        }
    }

    public function getProjects(Request $request, string $project_id){
        $start_time = $request->input('start_time') ?? null;
        $end_time = $request->input('end_time') ?? null;
        $pageSize = $request->input('pageSize') ?? 1000;
        
        $project = Project::where('id', $project_id)->first();

        if (!$project){
            return response(['error' => 'project_not_found','message' => 'Project not found'], 404);
        }

        # si start_time o end_time son null devolver error
        if ($start_time and $end_time){
            //$datareads = $dictionary->datareads()->whereBetween('created_at', [$start_time, $end_time])->paginate($pageSize);
            Log::info($project);
        }else{
            Log::info($project);
            //$datareads = $dictionary->datareads()->paginate($pageSize);
        }
    }
}
