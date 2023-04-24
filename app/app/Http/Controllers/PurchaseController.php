<?php

namespace App\Http\Controllers;

use App\Models\Purchase;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class PurchaseController extends Controller
{
    public function createPurchase(Request $request, $dataset_id)
    {
        try{
            $request_data = $request->all();

            $expire_date = null;
            if($request_data['type'] == 'Rental'){
                $expire_date = Carbon::now()->addMonths($request_data['rental_period']);
            }

            $purchase = new Purchase();

            $file = $request->file('buyer_doc') ?? null;
            if ($file){
                $filePath = $file->store($dataset_id);
                $fileUrl = Storage::url($filePath);
            }

            $purchase->dataset_id = $dataset_id;
            $purchase->user_id = $request_data['user_id'];

            $purchase->is_active = true;
            $purchase->expire_date = $expire_date;
            $purchase->type = $request_data['type'];
            $purchase->buyer_doc = $fileUrl ?? null;

            $purchase->save();

            return response(['message' => 'Purchase accomplished'], 200);
        }catch (\Exception $e){
            Log::error($e->getMessage());
            return response(['error' => 'internal_error', 'message' => 'Ha ocurrido un error interno.'], 500);
        }

    }
}
