<?php

namespace App\Http\Controllers;

use App\Models\Purchase;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;

class PurchaseController extends Controller
{
    public function createPurchase(Request $request, $dataset_id)
    {
        try{
            $request = $request->all();

            Log::info($request);
            $expire_date = null;
            if($request['type'] == 'Rental'){
                $expire_date = Carbon::now()->addMonths($request['rental_period']);
            }

            $purchase = new Purchase();
            $purchase->dataset_id = $dataset_id;
            $purchase->user_id = $request['user_id'];

            $purchase->is_active = true;
            $purchase->expire_date = $expire_date;
            $purchase->type = $request['type'];

            $purchase->save();

            return response(['message' => 'Purchase accomplished'], 200);
        }catch (\Exception $e){
            Log::error($e->getMessage());
            return response(['error' => 'internal_error', 'message' => 'Ha ocurrido un error interno.'], 500);
        }

    }
}
