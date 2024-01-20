<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

use App\Http\Controllers\ExcelController;
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
        'free' => 'Free',
        'sale' => 'Sale',
        'rental' => 'Rental'
    ];

    const DATASET_LICENSES = [
        'Unrestricted public use license'=>'Unrestricted public use license',
        'Public use license - include use restrictions'=>"Public use license - include use restrictions",
        'Public use license with geographic restrictions'=>"Public use license with geographic restrictions",
        'Proprietary license - include usage restrictions'=>"Proprietary license - include usage restrictions",
        'Proprietary license - include geographic restrictions'=>"Proprietary license - include geographic restrictions"
        
    ];

    const DATASET_TYPES_DATA = [
        'Static Data' => 'Static Data',
        'Real Time Data' => 'Real Time Data' 
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
                $filePath = $file->store('public/datasets/'.$dataset_id);
                $fileUrl = Storage::url($filePath);
            }

            $fileData = $request->file('static_data_upload') ?? null;
            if ($fileData){
                $fileDataPath = $fileData->store('public/datasets/'.$dataset_id."/dataFile");
                $fileDataUrl = Storage::url($fileDataPath);
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
            $dataset->latitude = $request['latitude'];
            $dataset->longitude = $request['longitude'];
            $dataset->autovalidate_sales = $request['autovalidate_sales'];
            $dataset->provider_doc = $fileUrl ?? null;
            $dataset->data_type = $request['dataset_data_type'];
            $dataset->data_url = isset($fileDataUrl) ? $fileDataUrl : $request['realtime_data_upload'];

            $dataset->save();


            if($fileDataUrl !== null){
                ExcelController::processExcel($fileDataPath,$dataset_id,$dataset->latitude,$dataset->longitude);
            }

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
        return $this->hasMany(Dataread::class);
    }

    public function purchases()
    {
        return $this->hasMany(Purchase::class);
    }

    public function sales()
    {
        return $this->hasMany(Sale::class);
    }
    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
    */
    public function scopeAvailable($query)
    {
        return $query->whereDoesntHve('purchases');
    }
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

