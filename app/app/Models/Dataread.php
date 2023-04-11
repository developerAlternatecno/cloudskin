<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class Dataread extends Model
{
    use HasFactory;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */
    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */

    public function serialize($data)
    {
        $serialized = json_encode($data);
        return iconv('UTF-8', 'ASCII//TRANSLIT', $serialized);
    }

    public function deserialize($data)
    {
        $deserialized = iconv('ASCII', 'UTF-8//IGNORE', $data);
        return json_decode($deserialized);
    }

    public static function checkDataTyping($data, $engine_template)
    {
        $engine_template = json_decode($engine_template, true);
        foreach ($data as $key => $value) {
            $type = Engine::ENGINE_TYPING[$engine_template[$key]['type']];

            if (gettype($value) != $type['type']) {
                return false;
            }

            #TODO: AÑADIR TEMA DE LA LONGITUD de bytes/caracteres
        }
        return true;
    }

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */
    public function sink()
    {
        return $this->belongsTo(Sink::class);
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
