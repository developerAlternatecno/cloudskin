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
        try{
            Log::info("Entramos en el CreateDataRead");
            Log::info("El Dataset es ".$dataset_id);
            # We check if the dataset exists
            $dataset = Dataset::where('id', $dataset_id)->first();

            if (!$dataset){
                Log::error("El Dataset no existe");
                return response(['error' => 'dataset_not_found', 'message' => 'The dataset does not exist'], 404);
            }

            if ($dataset->is_geolocated){
                Log::error("El Dataset no esta geolocalizado");
                if (!isset($request['longitude']) or !isset($request['latitude'])){
                    return response(['error' => 'invalid_data', 'message' => 'The dataset is geolocated, so you must provide longitude and latitude values.'], 400);
                }
            }

            # We check if the entyr json has the same keys that the json stored in the Engine
            $engine_template = $dataset->engine->template;

            $set1 = array_keys(json_decode($engine_template, true));
            asort($set1);

            Log::info("Set1: ".print_r($set1, true));

            # Creamos un nuevo array ordenado usando las claves ordenadas del template
            $sortedData = [];
            foreach ($set1 as $key) {
                $sortedData[$key] = $request['data'][$key] ?? null;
            }

            # Buscamos la posición actual de 'ºC'
            $positionOfCelsius = array_search('ºC', array_keys($sortedData));

            # Verificamos si 'ºC' está en la posición deseada
            if ($positionOfCelsius !== 2) {
                # Movemos 'ºC' a la posición deseada [2]
                $sortedData = array_slice($sortedData, 0, $positionOfCelsius, true) +
                            array_slice($sortedData, $positionOfCelsius + 1, null, true) +
                            [$positionOfCelsius + 1 => $sortedData[$positionOfCelsius]];
            }

            // $set2 = array_keys($request['data'], true);
            // asort($set2);

            // Log::info("Set2: ".print_r($set2, true));

            # if(array_values($set1) != array_values($sortedData)){
            if ($set1 !== array_keys($sortedData)) {
                Log::error("Datos invalidos");
                Log::error("Set1: " . print_r($set1, true));
                Log::error("Set2 (sorted): " . print_r(array_keys($sortedData), true));
                return response(['error' => 'invalid_data', 'message' => 'Invalid data values, json key values does not fit with the ones assigned when creating the dataset.'], 400);
            }

            # We check if the data has the correct typing
            $correctTyping = Dataread::checkDataTyping($sortedData, $engine_template);

            Log::error("Correct Typing: ".$correctTyping);

            if (gettype($correctTyping) == "string"){
                return response(['error' => 'invalid_data', 'message' => $correctTyping], 400);
            }

            # We create the dataread
            $dataread = new Dataread();
            $dataread->dataset_id = $dataset_id;
            $dataread->data = $dataread->serialize($request['data']);
            $dataread->longitude = $request['longitude'] ?? null;
            $dataread->latitude = $request['latitude'] ?? null;

            Log::error("Dataread: ".$dataread);

            $dataread->save();

            Log::info('Dataread creado');

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
}
