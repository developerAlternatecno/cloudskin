<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ExcelController extends Controller
{
    public static function processExcel($filePath, $url, $latitude, $longitude)
    {
        try {
            // Ruta al script de Python
            $pythonScript = base_path("python/excel-to-json.py");

            // Convierte las barras invertidas a barras inclinadas
            $pythonScript = str_replace('\\', '/', $pythonScript);

            // Verificar que el script de Python existe
            if (!file_exists($pythonScript)) {
                Log::error("El script de Python no existe en la ruta especificada: $pythonScript");
                return;
            }

            // Especificar la ruta completa al script de Python
            $pythonScript = realpath($pythonScript);

            // Especificar la ruta completa al ejecutable de Python en el comando
            $command = "python \"{$pythonScript}\" \"{$filePath}\" \"{$url}\" \"{$latitude}\" \"{$longitude}\" 2>&1";

            // Ejecutar el comando y obtener la salida y el código de retorno
            exec($command, $outputArray, $resultCode);

            // Imprimir la salida y el código de retorno para depuración
            Log::info("Ejecutando comando: $command");
            Log::info("Salida del comando: " . implode("\n", $outputArray));

            // Analizar la salida para obtener información adicional si es necesario
            if (strpos(strtolower(implode("\n", $outputArray)), 'error') !== false) {
                Log::error("La salida del comando contiene un mensaje de error.");
            } else {
                Log::info("La salida del comando no contiene mensajes de error.");
            }

            // Imprimir un mensaje de éxito
            Log::info("Procesamiento del archivo Excel completado exitosamente");

        } catch (\Exception $e) {
            Log::error("Error while processing Excel file");
            Log::error($e->getMessage());
        }
    }
}
