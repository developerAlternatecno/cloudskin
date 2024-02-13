<?php

namespace App\Http\Controllers;

use App\Models\Dictionary;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class DictionaryController extends Controller
{
    public function createDictionary(Request $request, $dictionary_id)
    {
        try{
            $request_data = $request->all();

            $dictionary = new Dictionary();

            //$dictionary->dictionary_id = $dictionary_id;
            $dictionary->name = $request_data['name'];
            $dictionary->input_type = $request['dictionary_input_type'];
            $dictionary->data_type = $request['dictionary_data_type'];
            $dictionary->default_unit = $request_data['default_unit'];
            $dictionary->description = $request_data['description'];

            $dictionary->save();

            return response(['message' => 'Dictionary created'], 200);

        }catch (\Exception $e){
            Log::error($e->getMessage());
        }
    }

    public function getDictionary(Request $request, string $dictionary_id){
        $start_time = $request->input('start_time') ?? null;
        $end_time = $request->input('end_time') ?? null;
        $pageSize = $request->input('pageSize') ?? 1000;

        $dictionary = Dictionary::where('dictionary_id', $dictionary_id)->first();

        if (!$dictionary){
            return response(['error' => 'dictionary_not_found', 'message' => 'The dictionary does not exist'], 404);
        }

        # si start_time o end_time son null devolver error
        if ($start_time and $end_time){
            //$datareads = $dictionary->datareads()->whereBetween('created_at', [$start_time, $end_time])->paginate($pageSize);
            Log::info($dictionary);
        }else{
            Log::info($dictionary);
            //$datareads = $dictionary->datareads()->paginate($pageSize);
        }
        
    }
}
