<?php

namespace App\Console\Commands;

use App\Jobs\ProcessExcelJob;
use App\Models\Dataread;
use App\Models\Dataset;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Reader\Exception as ReaderException;

class ComandTest extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ComandTest';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        try {
            Log::info("Comando start: ". Carbon::now()->toDateTimeString());


            // $files = scandir(getcwd());
            // dd($files);
            $dataset = Dataset::find("0181acd6-621a-40e1-89a1-53ea170e5c22");
            // $dataset_id = Str::uuid()->toString();
            // $newDataset = new Dataset();
            // $newDataset->id = $dataset_id;

            // $newDataset->engine_id = $dataset->engine_id;
            // $newDataset->user_id = $dataset->user_id;

            // $newDataset->name = $dataset->name;
            // $newDataset->owner = $dataset->owner;
            // $newDataset->origin = $dataset->origin;
            // $newDataset->start_daterange = $dataset->start_daterange;
            // $newDataset->end_daterange = $dataset->end_daterange;
            // $newDataset->type = $dataset->type;
            // $newDataset->price = $dataset->price;
            // $newDataset->license = $dataset->license;
            // $newDataset->categorie = $dataset->categorie;
            // $newDataset->description = $dataset->description;
            // $newDataset->is_geolocated = $dataset->is_geolocated;
            // $newDataset->latitude = 35.681382;
            // $newDataset->longitude = -3.607834;
            // $newDataset->autovalidate_sales = $dataset->autovalidate_sales;
            // $newDataset->provider_doc = null;
            // $newDataset->data_type = $dataset->data_type;
            // $newDataset->data_url = $dataset->data_url;

            // // if ($projectId) {
            // //     $newDataset->project_id = $projectId;
            // // }

            // $newDataset->save();

            // $newDataset1 = Dataset::find($dataset_id);
            // $newDataset1->update([
            //     'updated_at' => Carbon::now()->addSeconds(5)->toDateTimeString(),
            // ]);

            // dd($newDataset1);

            // // $datareads = $dataset->datareads()->latest()->take(5)->get();

            // // $data = [];
            // // foreach ($datareads as $dataread) {
            // //     array_push($data, get_object_vars($dataread->deserialize($dataread->data)));
            // // }

            // // if ($data == []) {
            // //     dd( "<p>No hay datos disponibles</p>");
            // // }

            // // // Obtener las columnas de la tabla a partir de los datos
            // // $columns = array_keys($data[0]);

            // // // Construir la tabla HTML
            // // $table_html = '<table><thead><tr>';
            // // foreach ($columns as $column) {
            // //     $table_html .= '<th>' . $column . '</th>';
            // // }
            // // $table_html .= '</tr></thead><tbody>';
            // // foreach ($data as $row) {
            // //     $table_html .= '<tr>';
            // //     foreach ($row as $cell) {
            // //         $table_html .= '<td>' . $cell . '</td>';
            // //     }
            // //     $table_html .= '</tr>';
            // // }
            // // $table_html .= '</tbody></table>';

            // // dd($table_html);
        
            // // $datasetId = $dataset->id;

            Dataread::where('dataset_id', $dataset->id)->delete();

            ProcessExcelJob::dispatch($dataset);

            Log::info("Comando finish: ". Carbon::now()->toDateTimeString());
        } catch (\Exception $e) {
            Log::error("Excepción: " . $e->getMessage());
            return "Excepción: " . $e->getMessage();
        }
    }

    private function parseDate($value)
    {
        $formats = [
            'm/d/Y', 'm/d/Y H:i', 'm/d/Y H:i:s',
            'm-d-Y', 'm-d-Y H:i', 'm-d-Y H:i:s',
            'Y-m-d\TH:i', 'Y-m-d\TH:i:s', 'Y/m/d H:i',
            'Y/m/d H:i:s', 'Y-m-d H:i', 'Y-m-d H:i:s',
            'd/m/Y H:i', 'd/m/Y H:i:s', 'd-m-Y H:i',
            'd-m-Y H:i:s', 'Y-m-d', 'Y/m/d', 'd/m/Y',
            'd-m-Y',
        ];

        foreach ($formats as $format) {
            try {
                $date = Carbon::createFromFormat($format, $value);
                if ($date) {
                    $newDate = Carbon::parse($value);
                    return $newDate->format(strpos($format, 'H') === false ? 'Y-m-d' : 'Y-m-d H:i:s');
                }
            } catch (\Exception $e) {
                // No se pudo parsear el valor como fecha
            }
        }
        return null;
    }
}