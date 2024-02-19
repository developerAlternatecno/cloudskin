<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;


class ProjectController extends Controller
{
    public static function createProject(Request $request){
        try{
            $request = $request->all();
    
            $project = new Project();
    
            $project->name = $request['project_name'];
            $project->type = $request['project_type'];
            $project->description = $request['project_description'];
            $project->entity = $request['project_entity'];
            $project->url = $request['project_url'];
            $project->access = $request['project_access'];
    
            // Crear el proyecto
            $project->save();
    
            // Adjuntar dataset al proyecto
            $datasets = $request['dataset_id'];
            if ($datasets) {
                $project->datasets()->sync($datasets);
            }

            //return true;
            //return Redirect::route('dataset.create', ['projectId' => $project->id]);
        }catch(\Exception $e){
            Log::error($e);
            return false;
        }
    }

    public function getProject(Request $request, string $project_id)
    {
        $start_time = $request->input('start_time') ?? null;
        $end_time = $request->input('end_time') ?? null;
        $pageSize = $request->input('pageSize') ?? 1000;

        $project = Project::where('id', $project_id)->first();

        if (!$project) {
            return response(['error' => 'project_not_found', 'message' => 'Project not found'], 404);
        }

        # si start_time o end_time son null devolver error
        if ($start_time and $end_time) {
            //$datareads = $dictionary->datareads()->whereBetween('created_at', [$start_time, $end_time])->paginate($pageSize);
            Log::info($project);
        } else {
            Log::info($project);
            //$datareads = $dictionary->datareads()->paginate($pageSize);
        }
    }
}
