<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Engine extends Model
{
    use \Backpack\CRUD\app\Models\Traits\CrudTrait;
    use HasFactory;
    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    # Aquí vamos a definir los tipos que va a recibir nuestra aplicación, el tamaño se va a mostrar en bytes, excepto
    # en el caso de string, que va a ser variable, el usuario mandará longitud de la cadena y se hará el cálculo en el momento.
    const ENGINE_TYPING = [
        "tinyint" => [
            "type" => "integer",
            "length" => 1, # -128 a 127 o de 0 a 255
        ],
        "smallint" => [
            "type" => "integer",
            "length" => 2, # -32,768 a 32,767 o de 0 a 65,535
        ],
        "mediumint" => [
            "type" => "integer",
            "length" => 3, # -8,388,608 a 8,388,607 o de 0 a 16,777,215
        ],
        "int" => [
            "type" => "integer",
            "length" => 4, # -2,147,483,648 a 2,147,483,647 o de 0 a 4,294,967,295
        ],
        "bigint" => [
            "type" => "integer",
            "length" => 8, # -9,223,372,036,854,775,808 a 9,223,372,036,854,775,807 o de 0 a 18,446,744,073,709,551,615
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
    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */
    public function sinks()
    {
        return $this->hasMany(Sink::class);
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
