<?php

namespace App\Http\Controllers;

use App\Models\Dataread;
use App\Models\Dataset;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
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
            # We check if the dataset exists
            $dataset = Dataset::where('id', $dataset_id)->first();

            if (!$dataset){
                return response(['error' => 'dataset_not_found', 'message' => 'The dataset does not exist'], 404);
            }
            # We check if the entyr json has the same keys that the json stored in the Engine
            $engine_template = $dataset->engine->template;

            $set1 = array_keys(json_decode($engine_template, true));
            asort($set1);

            $set2 = array_keys($request->all(), true);
            asort($set2);

            if(array_values($set1) != array_values($set2)){
                return response(['error' => 'invalid_data', 'message' => 'Invalid data values'], 400);
            }

            # We check if the data has the correct typing
            $correctTyping = Dataread::checkDataTyping($request->all(), $engine_template);

            if (!$correctTyping){
                return response(['error' => 'invalid_data', 'message' => 'Invalid data typing'], 400);
            }

            # We create the dataread
            $dataread = new Dataread();
            $dataread->dataset_id = $dataset_id;
            $dataread->data = $dataread->serialize($request->all());

            $dataread->save();

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

            $dataset = Dataset::where('id', $dataset_id)->first();

            # si start_time o end_time son null devolver error
            if ($start_time and $end_time){
                $datereads = $dataset->datareads()->whereBetween('created_at', [$start_time, $end_time])->limit(1000)->get();
            }else{
                $datereads = $dataset->datareads()->limit(1000)->get();
            }

            $data = [];
            foreach ($datereads as $dataread){
                $dataread->created_at = Carbon::parse($dataset->created_at)->format('d-m-Y H:i:s');
                $dataread->updated_at = Carbon::parse($dataset->updated_at)->format('d-m-Y H:i:s');
                $dataread->data = $dataread->deserialize($dataread->data);
                $data[] = $dataread;
            }

            return $data;

        }catch (\Exception $e){
            Log::error($e->getMessage());
            return response(['error' => 'internal_error', 'message' => 'Ha ocurrido un error interno.'], 500);
        }
    }

}
