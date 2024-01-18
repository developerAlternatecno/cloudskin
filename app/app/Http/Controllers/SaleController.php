<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class SaleController extends Controller
{
    public function createSale(Request $request, $dataset_id)
    {
        try{
            $request_data = $request->all();

            $expire_date = null;
            if($request_data['type'] == 'Rental'){
                $expire_date = Carbon::now()->addMonths($request_data['rental_period']);
            }

            $purchase = new Sale();

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

    public function getBuyerDoc($purchase_id){
        try{
            $purchase = Sale::where('id', $purchase_id)->first();

            if (!$purchase){
                return response(['error' => 'purchase_not_found', 'message' => 'The purchase does not exist'], 404);
            }

            $filePath = str_replace(Storage::url(''), '', $purchase->buyer_doc);
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
}
