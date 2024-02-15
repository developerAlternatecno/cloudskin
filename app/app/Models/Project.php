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

    const PROJECT_ACCESS = [
        "private" => "Private",
        "public" => "Public"
    ];

    protected $appends = [];
    protected $fillable = [
        'name', 'type', 'description', 'entity', 'url', 'access', 'user_id', 'dataset_id'
    ];
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

            $project->name = $request['project_name'];
            $project->type = $request['project_type'];
            $project->description = $request['project_description'];
            $project->entity = $request['project_entity'];
            $project->url = $request['project_url'];
            $project->access = $request['project_access'];
            $project->data_source = $request['project_data_source'];
            $project->dataset_id = $request['dataset_id'];
            $project->user_id = Auth::id();

            $project->save();

            return true;
        }catch(\Exception $e){
            Log::error($e);
            return false;
        }
    }

    protected static function boot()
    {
        parent::boot();

        // Manejar la eliminación del proyecto antes de eliminar los datasets
        static::deleting(function ($project) {
            $project->datasets()->delete();
        });
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
        return $this->belongsToMany(Dataset::class, 'project_dataset');
    }

    public function dataset()
    {
        return $this->belongsTo(Dataset::class);
    }


}