<?php

namespace App\Models;

// use App\Jobs\ProcessExcelJob;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Models\Project;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Reader\Exception as ReaderException;
use Illuminate\Support\Carbon;
use Prologue\Alerts\Facades\Alert;

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
        'Unrestricted public use license' => 'Unrestricted public use license',
        'Public use license - include use restrictions' => "Public use license - include use restrictions",
        'Public use license with geographic restrictions' => "Public use license with geographic restrictions",
        'Proprietary license - include usage restrictions' => "Proprietary license - include usage restrictions",
        'Proprietary license - include geographic restrictions' => "Proprietary license - include geographic restrictions"

    ];

    const DATASET_CATEGORIES = [
        'Agricultural' => 'Agricultural',
        'Environmental' => "Environmental",
        'Presence' => "Presence",

    ];

    const DATASET_TYPES_DATA = [
        'Static Data' => 'Static Data',
        'Real Time Data' => 'Real Time Data'
    ];

    protected $appends = ['url', 'isPurchased', 'imagen_url', 'template_data', 'last_data_reads_table'];

    protected $fillable = [
        'updated_at',
    ];

    protected $keyType = 'string';
    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */

    public static function createDataset(Request $request, $engine_id)
    {
        try {
            $dataset_id = Str::uuid()->toString();

            //$projectId = isset($request->input('projectId')) ? $request->input('projectId') : null;

            $file = $request->file('provider_doc') ?? null;
            if ($file) {
                $filePath = $file->store('public/datasets/' . $dataset_id);
                $fileUrl = Storage::url($filePath);
            }

            $fileImagen = $request->file('dataset_image') ?? null;
            if ($fileImagen) {
                $fileImgPath = $fileImagen->store('public/datasets/' . $dataset_id);
                $fileImgUrl = Storage::url($fileImgPath);
            }

            $fileData = $request->file('static_data_upload') ?? null;
            if ($fileData) {
                $header = self::getHeaderExcelFile($fileData);
                $header_values = array_values($header);

                // Obtiene el engine_template
                $engine = Engine::find($engine_id);
                $engine_template = json_decode($engine->template, true);

                // Compara las claves del engine_template con las columnas del archivo Excel
                $validationResult = Dataread::checkDataHeaderAgainstTemplateEngine($header_values, $engine_template);
                if (is_string($validationResult)) {
                    Log::error($validationResult);
                    Alert::error($validationResult)->flash();
                    return false;
                }

                $fileDataPath = $fileData->store('public/datasets/' . $dataset_id . "/dataFile");
                $fileDataUrl = Storage::url($fileDataPath);
            }

            $dataset = new Dataset();
            $dataset->id = $dataset_id;

            $dataset->engine_id = $engine_id;
            $dataset->user_id = Auth::id();

            $dataset->name = $request['dataset_name'];
            $dataset->owner = $request['dataset_owner'];
            $dataset->origin = $request['dataset_origin'];
            $dataset->start_daterange = $request['dataset_start_daterange'];
            $dataset->end_daterange = $request['dataset_end_daterange'];
            $dataset->type = $request['dataset_type'];
            $dataset->price = $request['dataset_price'];
            $dataset->license = $request['dataset_license'];
            $dataset->categorie = $request['dataset_categorie'];
            $dataset->description = $request['dataset_description'];
            $dataset->imagen = $fileImgUrl ?? null;
            $dataset->is_geolocated = $request['dataset_checkbox'];
            $dataset->latitude = $request['latitude'];
            $dataset->longitude = $request['longitude'];
            $dataset->autovalidate_sales = $request['autovalidate_sales'];
            $dataset->provider_doc = $fileUrl ?? null;
            $dataset->data_type = $request['dataset_data_type'];
            $dataset->data_url = isset($fileDataUrl) ? $fileDataUrl : $request['realtime_data_upload'];

            // if ($projectId) {
            //     $dataset->project_id = $projectId;
            // }

            $dataset->save();

            $newDataset1 = Dataset::find($dataset_id);
            $newDataset1->update([
                'updated_at' => Carbon::now()->addSeconds(5)->toDateTimeString(),
            ]);

            return true;
        } catch (\Exception $e) {
            Log::error("Error while creating dataset");
            Log::error($e->getMessage());
            Alert::error($e->getMessage())->flash();
            return false;
        }
    }

    public function generateLastDataReadsTable()
    {

        $datareads = $this->datareads()->latest()->take(5)->get();

        $data = [];
        foreach ($datareads as $dataread) {
            array_push($data, get_object_vars($dataread->deserialize($dataread->data)));
        }

        if ($data == []) {
            return "<p>No data available</p>";
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

    public static function getHeaderExcelFile($fileData)
    {
        try {
            $filePath = $fileData->getRealPath();
            $reader = IOFactory::createReaderForFile($filePath);
            $reader->setReadDataOnly(true);
            $spreadsheet = $reader->load($filePath);
            $sheet = $spreadsheet->getActiveSheet();
            $highestRow = 1;
            $highestColumn = $sheet->getHighestDataColumn();
            $rows = $sheet->rangeToArray('A1:' . $highestColumn . $highestRow, null, true, true, true, true);

            return $rows[1];
        } catch (ReaderException $e) {
            Log::error("Error reading Excel file: " . $e->getMessage());
            return false;
        }
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

    public function projects()
    {
        return $this->belongsToMany(Project::class, 'project_dataset');
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
        return url("/api/datasets/" . $this->id);
    }
    public function getIsPurchasedAttribute()
    {
        return $this->purchases()->where('user_id', Auth::id())->where('is_active', 1)->exists();
    }

    public function getImagenUrlAttribute(): ?string
    {
        url("storage/datasets/" . $this->id);
        return $this->imagen ? url($this->imagen) : null;
    }

    public function getTemplateDataAttribute()
    {
        return $this->engine->template ? json_decode($this->engine->template, true) : null;
    }

    public function getLastDataReadsTableAttribute()
    {
        return $this->generateLastDataReadsTable();
    }
    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */
}
