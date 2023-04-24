<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Dataset extends Model
{
    use \Backpack\CRUD\app\Models\Traits\CrudTrait;
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

    protected $appends = ['url', 'isPurchased'];

    protected $keyType = 'string';
    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */

    public static function createDataset(Request $request, $engine_id){
        try{
            $dataset_id = Str::uuid()->toString();

            $file = $request->file('provider_doc') ?? null;
            if ($file){
                $filePath = $file->store('/public/datasets/'.$dataset_id);
                $fileUrl = Storage::url($filePath);
            }

            $dataset = new Dataset();
            $dataset->id = $dataset_id;

            $dataset->engine_id = $engine_id;
            $dataset->user_id = Auth::id();

            $dataset->name = $request['dataset_name'];
            $dataset->type = $request['dataset_type'];
            $dataset->price = $request['dataset_price'];
            $dataset->license = $request['dataset_license'];
            $dataset->description = $request['dataset_description'];
            $dataset->is_geolocated = $request['dataset_checkbox'];
            $dataset->provider_doc = $fileUrl ?? null;

            $dataset->save();

            return true;
        }catch(\Exception $e){
            Log::error("Error while creating dataset");
            Log::error($e->getMessage());
            return false;
        }

    }

    public function generateLastDataReadsTable(){

        $datareads = $this->datareads()->latest()->take(5)->get();

        $data = [];
        foreach ($datareads as $dataread){
            array_push($data, get_object_vars($dataread->deserialize($dataread->data)));
        }

        if ($data == []) {
            return "<p>No hay datos disponibles</p>";
        }

        // Obtener las columnas de la tabla a partir de los datos
        $columns = array_keys($data[0]);

        // Construir la tabla HTML
        $table_html = '<table><thead><tr>';
        foreach ($columns as $column) {
            $table_html .= '<th>' . $column . '</th>';
        }
        $table_html .= '</tr></thead><tbody>';
        foreach ($data as $row) {
            $table_html .= '<tr>';
            foreach ($row as $cell) {
                $table_html .= '<td>' . $cell . '</td>';
            }
            $table_html .= '</tr>';
        }
        $table_html .= '</tbody></table>';

        return $table_html;
    }


    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */

    public function engine()
    {
        return $this->belongsTo(Engine::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function datareads()
    {
        return $this->hasMany(DataRead::class);
    }

    public function purchases()
    {
        return $this->hasMany(Purchase::class);
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
    public function getUrlAttribute(): string
    {
        return url("/api/datasets/".$this->id);
    }
    public function getIsPurchasedAttribute(){
        return $this->purchases()->where('user_id', Auth::id())->where('is_active', 1)->exists();
    }
    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */
}

