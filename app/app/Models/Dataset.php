<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class Dataset extends Model
{
    use HasFactory;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */
    const DATASET_TYPES = [
        'buyout' => 'Compra',
        'rental' => 'Alquiler'
    ];

    const DATASET_LICENSES = [
        'national' => 'Nacional',
        'european' => 'Europea',
        'unlimited' => 'Ilimitada'
    ];
    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */

    public static function createDataset($request){
        try{
            $dataset = new Dataset();
            $dataset->name = $request['dataset_name'];
            $dataset->type = $request['dataset_type'];
            $dataset->price = $request['dataset_price'];
            $dataset->license = $request['dataset_license'];
            $dataset->description = $request['dataset_description'];

            $dataset->save();

            return true;
        }catch(\Exception $e){
            Log::error("Error while creating dataset");
            Log::error($e->getMessage());
            return false;
        }

    }

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */
    public function datareads()
    {
        return $this->hasMany(Dataread::class);
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

