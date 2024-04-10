<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class Engine extends Model
{
    use \Backpack\CRUD\app\Models\Traits\CrudTrait;
    use HasFactory;
    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */
    protected $keyType = 'string';
    public $incrementing = false;

    /*
    |--------------------------------------------------------------------------
    |--------------------------------------------------------------------------
    */
    public static function createEngineFromCrudController($engine_template)
    {
        try {
            $template = [];
            foreach (json_decode($engine_template) as $template_value) {
                $template[$template_value->field_name] = [
                    'description' => $template_value->description ? $template_value->description : null,
                    'data_type'    =>  $template_value->data_type ? $template_value->data_type : null,
                    'unit'    => $template_value->unit ? $template_value->unit : null,
                ];
            }

            $engine_id = Str::uuid()->toString();
            $engine = new Engine();
            $engine->id = $engine_id;
            $engine->template = json_encode($template);

            $engine->save();
            return $engine_id;
        } catch (\Exception $e) {
            Log::error("Error while creating engine");
            Log::error($e->getMessage());
            return null;
        }
    }
    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */
    public function datasets()
    {
        return $this->hasMany(Dataset::class);
    }
    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | ACCESSORS
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */
}
