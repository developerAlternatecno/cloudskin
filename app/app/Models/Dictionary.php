<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class Dictionary extends Model
{
    use \Backpack\CRUD\app\Models\Traits\CrudTrait;
    use HasFactory;

    protected $table = 'dictionary';


    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $appends = [];

    protected $keyType = 'string';

    public static function createDictionary(Request $request, $engine_id)
    {
        try {
            $dictionary = new Dictionary();

            $dictionary->data_type = $request['dictionary_data_type'];
            $dictionary->default_unit = $request['dictionary_default_unit'];
            $dictionary->description = $request['dictionary_description'];
            
            $dictionary->save();
            return true;
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return false;
        }
    }
}
