<?php

namespace App\Http\Controllers;

use App\Models\Dataread;
use App\Models\Dataset;
use App\Models\Purchase;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
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

            $dataset->save();

            return response(['url' => url("/api/datasets/".$dataset_id)], 200);

        }catch (\Exception $e){
            Log::error($e->getMessage());
            return response(['error' => 'internal_error', 'message' => 'Ha ocurrido un error interno.'], 500);
        }
    }

    public function addDataRead(Request $request, string $dataset_id)
    {
        try {
            Log::info("Entramos en el CreateDataRead");
            # We check if the dataset exists
            $dataset = Dataset::where('id', $dataset_id)->first();
    
            if (!$dataset) {
                return response(['error' => 'dataset_not_found', 'message' => 'The dataset does not exist'], 404);
            }
    
            if ($dataset->is_geolocated) {
                if (!isset($request['longitude']) or !isset($request['latitude'])) {
                    return response(['error' => 'invalid_data', 'message' => 'The dataset is geolocated, so you must provide longitude and latitude values.'], 400);
                }
            }
    
            # We check if the entry json has the same keys that the json stored in the Engine
            $engine_template = $dataset->engine->template;
    
            $set1 = array_keys(json_decode($engine_template, true));
            asort($set1);
    
            $data = $request['data']; // Convertimos a un array normal
            $set2 = array_keys($data, true);
            asort($set2);
    
            # Buscamos la posición actual de 'ºC' y 'Timestamp'
            $positionOfCelsius = array_search('ºC', $set2);
    
            # Si no encontramos 'ºC', buscamos '°C'
            if ($positionOfCelsius === false) {
                $positionOfCelsius = array_search('°C', $set2);
            }
    
            # Reemplazamos 'ºC' o '°C' con 'ºC' si se encuentra
            foreach ($set2 as $key => $value) {
                if ($data[$value] === '°C') {
                    $data[$value] = 'ºC';
                }
            }
    
            if (array_values($set1) != array_values($set2)) {
                Log::info("Los Datos no son iguales");
                Log::info(print_r(array_values($set1), true));
                Log::info(print_r(array_values($set2), true));
                return response(['error' => 'invalid_data', 'message' => 'Invalid data values, json key values do not fit with the ones assigned when creating the dataset.'], 400);
            }
    
            # We check if the data has the correct typing
            $correctTyping = Dataread::checkDataTyping($data, $engine_template);
    
            if (gettype($correctTyping) == "string") {
                return response(['error' => 'invalid_data', 'message' => $correctTyping], 400);
            }
    
            # We create the dataread
            $dataread = new Dataread();
            $dataread->dataset_id = $dataset_id;
            $dataread->data = $dataread->serialize($data);
            $dataread->longitude = $request['longitude'] ?? null;
            $dataread->latitude = $request['latitude'] ?? null;
    
            $dataread->save();
    
            Log::info('Dataread creado');
    
            return response("OK", 200);
        } catch (\Exception $e) {
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
}
