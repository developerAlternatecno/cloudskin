<?php

namespace App\Http\Controllers;

use App\Models\Dataread;
use App\Models\Sink;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class SinkController extends Controller
{
    public function createSink(Request $request)
    {
        try{
            $sink_id = Str::uuid()->toString();
            $sink = new Sink();
            $sink->id = $sink_id;
            $sink->user_id = $request->user_id;
            $sink->engine_id = $request->engine_id;

            $sink->save();

            return response(['url' => url("/sinks/".$sink_id)], 200);

        }catch (\Exception $e){
            Log::error($e->getMessage());
            return response(['error' => 'internal_error', 'message' => 'Ha ocurrido un error interno.'], 500);
        }
    }

    public function addDataRead(Request $request, string $sink_id)
    {
        try{
            $sink = Sink::where('id', $sink_id)->firstOrFail();
            $engine_template = $sink->engine->template;

            $set1 = array_keys(json_decode($engine_template, true));
            asort($set1);

            $set2 = array_keys($request->all(), true);
            asort($set2);

            if(array_values($set1) != array_values($set2)){
                return response(['error' => 'invalid_data', 'message' => 'Invalid data values'], 400);
            }

            $correctTyping = Dataread::checkDataTyping($request->all(), $engine_template);

            if (!$correctTyping){
                return response(['error' => 'invalid_data', 'message' => 'Invalid data typing'], 400);
            }

            $dataread = new Dataread();
            $dataread->sink_id = $sink_id;
            $dataread->data = $dataread->serialize($request->all());

            $dataread->save();

            return response("OK", 200);
        }catch (\Exception $e){
            Log::error($e->getMessage());
            return response(['error' => 'internal_error', 'message' => 'Ha ocurrido un error interno.'], 500);
        }
    }

}
