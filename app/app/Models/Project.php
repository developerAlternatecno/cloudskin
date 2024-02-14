<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class Project extends Model
{
    use \Backpack\CRUD\app\Models\Traits\CrudTrait;
    use HasFactory;

        /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    const PROJECT_TYPES = [
        "public research project" => "Public Research Project",
        "private researchproject" => "Private Research Project",
        "commercial project" => "Commercial Project"
    ];

    protected $appends = [];
    protected $keyType = 'string';

    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */

    public static function createProject(Request $request){
        try{
            $project_id = Str::uuid()->toString();

            $project = new Project();
            $project->id = $project_id;
            $project->name = $request['name'];
            $project->type = $request['type'];
            $project->description = $request['description'];
            $project->entity = $request['entity'];
            $project->url = $request['url'];
            $project->access = $request['access'];
            $project->data_source = $request['data_source'];
            $project->dataset = $request['dataset'];
            $project->user_id = Auth::id();

            $project->save();

            return true;
        }catch(\Exception $e){
            Log::error($e);
            return false;
        }
    }

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function datasets()
    {
        return $this->hasOne(Dataset::class);
    }


}