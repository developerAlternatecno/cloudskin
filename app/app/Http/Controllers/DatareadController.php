<?php

namespace App\Http\Controllers;

use App\Models\Dataread;
use App\Models\Engine;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class DatareadController extends Controller
{

    public function getMapDatareads(){
        try{
            $datareads = Dataread::whereIn('id', function ($query) {
                $query->selectRaw('MIN(id)')
                    ->from('datareads')
                    ->groupBy('latitude', 'longitude');
            })
            ->get();

            Log::info($datareads);

            $data = [];

            foreach ($datareads as $dataread){
                $data[] = [
                    'id' => $dataread->id,
                    'latitude' => $dataread->latitude,
                    'longitude' => $dataread->longitude,
                    'created_at' => $dataread->created_at,
                    'dataset_name' => $dataread->dataset->name,
                    'dataset_id' => $dataread->dataset_id,
                ];
            }

            return response($data, 200);
        }catch (\Exception $e){
            Log::error($e->getMessage());
            return response(['error' => 'internal_error', 'message' => 'Ha ocurrido un error interno.'], 500);
        }
    }
}
