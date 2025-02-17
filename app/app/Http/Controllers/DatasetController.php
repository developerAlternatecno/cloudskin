<?php

namespace App\Http\Controllers;

use App\Models\Dataread;
use App\Models\Dataset;
use App\Models\Project;
use App\Models\Purchase;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class DatasetController extends Controller
{
    public function createDatasetFromAPI(Request $request)
    {
        try{
            $dataset_id = Str::uuid()->toString();
            $dataset = new Dataset();
            $dataset->id = $dataset_id;
            $dataset->user_id = $request->user_id;
            $dataset->engine_id = $request->engine_id;

            if ($request->query('by-bulk', false)) {
                $dataset->name = $request->dataset_name;
                $dataset->owner = $request->dataset_owner;
                $dataset->origin = $request->dataset_origin;
                $dataset->start_daterange = Carbon::createFromFormat('d-m-Y', $request->dataset_start_daterange)->format('Y-m-d');
                $dataset->end_daterange = Carbon::createFromFormat('d-m-Y', $request->dataset_end_daterange)->format('Y-m-d');
                $dataset->type = $request->dataset_type;
                $dataset->price = $request->dataset_price;
                $dataset->license = $request->dataset_license;
                $dataset->categorie = $request->dataset_categorie;
                $dataset->description = $request->dataset_description;
                $dataset->is_geolocated = $request->dataset_checkbox;
                $dataset->autovalidate_sales = $request->autovalidate_sales;
                $dataset->data_type = $request->dataset_data_type;
            }

            $project = Project::find($request->input('project_id'));
            if (!$project) {
                return response(['error' => 'project_not_found', 'message' => 'The project does not exist'], 404);
            }
            $project->datasets()->attach($dataset);

            $dataset->save();

            return response(['url' => url("/api/datasets/".$dataset_id), "id"=>$dataset_id], 200);

        }catch (\Exception $e){
            Log::error($e->getMessage());
            return response(['error' => 'internal_error', 'message' => 'Ha ocurrido un error interno.'], 500);
        }
    }

    public function addDataRead(Request $request, string $dataset_id)
    {
        try{
            # We check if the dataset exists
            $dataset = Dataset::where('id', $dataset_id)->first();

            if (!$dataset){
                return response(['error' => 'dataset_not_found', 'message' => 'The dataset does not exist'], 404);
            }

            $longitude = $request->input('longitude', null);
            $latitude = $request->input('latitude', null);

            if ($dataset->is_geolocated && (!$longitude || !$latitude)) {
                return response()->json(['error' => 'invalid_data', 'message' => 'The dataset is geolocated, so you must provide longitude and latitude values.'], 400);
            }

            $engine_template = json_decode($dataset->engine->template, true);
            $array_data = $request->input('data', []);

            if (!is_array($array_data) || count($array_data) == 0){
                return response(['error' => 'invalid_data', 'message' => 'The data must be an array.'], 400);
            }

            DB::beginTransaction();

            foreach ($array_data as $data){
                # We check if the data has the correct typing
                $correctTyping = Dataread::checkDataHeaderAgainstTemplateEngine(array_keys($data), $engine_template);

                if (is_string($correctTyping)){
                    return response(['error' => 'invalid_data', 'message' => $correctTyping], 400);
                }

                $dataread = new Dataread();
                $dataread->dataset_id = $dataset_id;
                $dataread->data = $dataread->serialize($data);
                $dataread->longitude = $longitude;
                $dataread->latitude = $latitude;

                $dataread->save();
            }

            DB::commit();

            return response("OK", 200);
        }catch (\Exception $e){
            Log::error($e->getMessage());
            return response(['error' => 'internal_error', 'message' => 'Ha ocurrido un error interno.'], 500);
        }
    }

    public function getDataReads(Request $request, string $dataset_id){
        try{

            $start_time = $request->input('start_time') ?? null;
            $end_time = $request->input('end_time') ?? null;
            $pageSize = $request->input('pageSize') ?? 1000;

            $dataset = Dataset::where('id', $dataset_id)->first();

            if (!$dataset){
                return response(['error' => 'dataset_not_found', 'message' => 'The dataset does not exist'], 404);
            }

            # si start_time o end_time son null devolver error
            if ($start_time and $end_time){
                $datareads = $dataset->datareads()->whereBetween('created_at', [$start_time, $end_time])->paginate($pageSize);
            }else{
                $datareads = $dataset->datareads()->paginate($pageSize);
            }

            foreach ($datareads as $dataread){
                $dataread->created_at = Carbon::parse($dataset->created_at)->format('d-m-Y H:i:s');
                $dataread->updated_at = Carbon::parse($dataset->updated_at)->format('d-m-Y H:i:s');
                $dataread->data = $dataread->deserialize($dataread->data);

            }

            return $datareads;

        }catch (\Exception $e){
            Log::error($e->getMessage());
            return response(['error' => 'internal_error', 'message' => 'Ha ocurrido un error interno.'], 500);
        }
    }

    public function getProviderDoc($dataset_id){
        try{
            $dataset = Dataset::where('id', $dataset_id)->first();

            if (!$dataset){
                return response(['error' => 'dataset_not_found', 'message' => 'The dataset does not exist'], 404);
            }

            $filePath = str_replace(Storage::url(''), '', $dataset->provider_doc);
            $filePath = '/public/'.$filePath;
            // Obtener el tipo MIME del archivo
            $mimeType = Storage::mimeType($filePath);

            // Verificar si el archivo existe en el disco
            if (!Storage::exists($filePath)) {
                return response()->json(['mensaje' => 'Archivo no encontrado en el disco'], 404);
            }

            // Devolver el archivo como respuesta HTTP
            return response(Storage::get($filePath), 200)
                ->header('Content-Type', $mimeType)
                ->header('Content-Disposition', 'attachment; filename=' . basename($filePath));

        }catch (\Exception $e){
            Log::error($e->getMessage());
            return response(['error' => 'internal_error', 'message' => 'Ha ocurrido un error interno.'], 500);
        }
    }

    public function bulkCreation(Request $request, $dataset_id){
        try{
            $data = $request->json()->all();
            foreach ($data as $key => $value){
                foreach ($value as $new_dataread){
                    $dataread = new Dataread();
                    $dataread->dataset_id = $key;
                    $dataread->data = $dataread->serialize($new_dataread['data']);
                    $dataread->longitude = $new_dataread['longitude'] ?? null;
                    $dataread->latitude = $new_dataread['latitude'] ?? null;
                    $dataread->save();
                }

            }

        }catch (\Exception $e){
            Log::error($e->getMessage());
            return response(['error' => 'internal_error', 'message' => 'Ha ocurrido un error interno.'], 500);
        }
    }

    public function getMapDatasets(){
        try{
            $datasets = Dataset::whereIn('id', function ($query) {
                $query->selectRaw('MIN(id)')
                    ->from('datasets')
                    ->groupBy('latitude', 'longitude');
            })
            ->get();

            Log::info($datasets);

            $data = [];

            foreach ($datasets as $dataset){
                $data[] = [
                    'id' => $dataset->id,
                    'dataset_id' => $dataset->id,
                    'latitude' => $dataset->latitude,
                    'longitude' => $dataset->longitude,
                    'created_at' => $dataset->created_at,
                    'dataset_name' => $dataset->name,
                ];
            }

            return response($data, 200);
        }catch (\Exception $e){
            Log::error($e->getMessage());
            return response(['error' => 'internal_error', 'message' => 'Ha ocurrido un error interno.'], 500);
        }
    }

    public function getAllDatasets(Request $request){
        try{
            $query = Dataset::query();

            if ($request->has('search') && !empty($request->input('search'))) {
                $search = $request->input('search');
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('type', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            }

            if ($request->has('project_id') && !empty($request->input('project_id'))) {
                $project_id = $request->input('project_id');
                $query->whereHas('projects', function ($query) use ($project_id) {
                    $query->where('project_id', $project_id);
                });
            }

            if ($request->has('sort_by') && !empty($request->input('sort_by'))) {
                $sort_by = $request->input('sort_by');
                $order = $request->input('order', 'asc');
                $query->orderBy($sort_by, $order);
            }

            $datasets = $query->get();

            return response($datasets, 200);
        } catch (\Exception $e){
            Log::error($e->getMessage());
        }
    }

    public function showUploadForm($datasetId)
    {
        // Intentar encontrar el dataset por su ID alfanumérico
        $dataset = Dataset::where('id', $datasetId)->firstOrFail();
        return view('upload_data', ['dataset' => $dataset]);
    }

    public function upload(Request $request, $datasetId)
    {
        $request->validate([
            'data' => 'required|string',
        ]);

        $data = $request->input('data');
        $jsonData = json_decode($data, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            echo 'no tiene formato de JSON';
            return redirect()->route('dataset.upload_form', ['dataset' => $datasetId])->with('error', 'The content must be a valid JSON.');
        }

        $dataset = Dataset::where('id', $datasetId)->firstOrFail();

        foreach ($jsonData as $entries) {
            foreach ($entries as $entry) {

                $dataread = new Dataread();
                $dataread->dataset_id = $dataset->id;
                $dataread->data = json_encode($entry['data'] ?? []);
                $dataread->longitude = $entry['longitude'] ?? null;
                $dataread->latitude = $entry['latitude'] ?? null;

                $dataread->save();
            }
        }

        return redirect()->route('dataset.upload_form', ['dataset' => $datasetId])->with('success', 'Datos subidos exitosamente.');
    }
}
