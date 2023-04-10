<?php

namespace App\Http\Controllers;

use App\Models\Engine;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class EngineController extends Controller
{

    public function createEngine(Request $request)
    {
        try{
            $engine_id = Str::uuid()->toString();
            $engine = new Engine();
            $engine->id = $engine_id;
            $engine->template = json_encode($request->all());

            $engine->save();

            return response(['engine_id' => $engine_id], 200);

        }catch (\Exception $e){
            Log::error($e->getMessage());
            return response(['error' => 'internal_error', 'message' => 'Ha ocurrido un error interno.'], 500);
        }
    }
}
