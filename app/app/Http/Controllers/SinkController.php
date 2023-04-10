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
        $dataread = new Dataread();
        $dataread->sink_id = $sink_id;
        $dataread->data = $dataread->serialize($request->all());

        $dataread->save();

        return response(utf8_encode($dataread->serialize($request->all())), 200);
    }

}
