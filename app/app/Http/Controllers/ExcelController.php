<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ExcelController extends Controller
{
    public static function processExcel($filePath = null, $url = null, $latitude = 0, $longitude = 0)
    {
        try {
            $output = [];
            $returnVar = 0;
    
            // Ejecutar el script de prueba
            exec('/usr/bin/python3 python/excel-to-json.py 2>&1', $output, $returnVar);
    
            if ($returnVar === 0) {
                // Éxito
                return implode(PHP_EOL, $output);
            } else {
                // Error
                $errorOutput = implode(PHP_EOL, $output);
                Log::error("Error al ejecutar el script de Python. Código de retorno: $returnVar");
                Log::error("Salida del comando: $errorOutput");
                return "Error al ejecutar el script de Python. Código de retorno: $returnVar. Salida del comando: $errorOutput";
            }
        } catch (\Exception $e) {
            // Manejar excepciones
            Log::error("Excepción: " . $e->getMessage());
            return "Excepción: " . $e->getMessage();
        }
    }
    
}
