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

    # Aquí vamos a definir los tipos que va a recibir nuestra aplicación, el tamaño se va a mostrar en bytes, excepto
    # en el caso de string, que va a ser variable, el usuario mandará longitud de la cadena y se hará el cálculo en el momento.
    const ENGINE_TYPING = [
        "int" => [
            "type" => "integer",
            "length" => 4, # -2,147,483,648 a 2,147,483,647 o de 0 a 4,294,967,295
        ],
        "float" => [
            "type" => "double",
            "length" => 4, # 7 dígitos
        ],
        "double" => [
            "type" => "double",
            "length" => 8, # 15 dígitos
        ],
        "string" => [
            "type" => "string",
            "length" => "variable",
        ],
    ];

    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */
    public static function createEngineFromCrudController($engine_template){
        try{
            $template = [];
            foreach(json_decode($engine_template ) as $template_value){
                $template[$template_value->field_name] = [
                    'type' => array_keys(Engine::ENGINE_TYPING)[$template_value->type],
                    'length' => (int) $template_value->length == 0 ? null : (int) $template_value->length,
                    'unit' => $template_value->field_unit,
                ];
            }

            $engine_id = Str::uuid()->toString();
            $engine = new Engine();
            $engine->id = $engine_id;
            $engine->template = json_encode($template);

            $engine->save();
            return $engine_id;

        }catch (\Exception $e){
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
