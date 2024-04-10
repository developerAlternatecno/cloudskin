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

    public static function checkDataHeaderAgainstTemplateEngine(array $data_keys, array $engine_template)
    {
        $engine_template_keys = array_keys($engine_template);
        $missing_keys = array_merge(array_diff($data_keys, $engine_template_keys), array_diff($engine_template_keys, $data_keys));

        if (!empty($missing_keys)) {
            return 'Invalid data values, JSON key values do not match those assigned when creating the dataset: ' . implode(', ', $missing_keys);
        }

        return true;
    }

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */
    public function dataset()
    {
        return $this->belongsTo(Dataset::class);
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
