<?php

namespace App\Jobs;

use App\Models\Dataread;
use App\Models\Dataset;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\LazyCollection;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Reader\Exception as ReaderException;

class ProcessExcelJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $dataset;

    public function __construct(Dataset $dataset)
    {
        $this->dataset = $dataset;
    }

    public function handle()
    {
        try {
            Log::info("ProcessExcelJob started: " . $this->dataset->id . " " . Carbon::now()->toDateTimeString());
            DB::beginTransaction();

            $filePath = $this->getFilePath($this->dataset->data_url);

            if (!file_exists($filePath)) {
                $this->release(30);
                return;
            }

            $excelInfo = $this->getExcelFileInfo($filePath);
            
            if (!$excelInfo) {
                throw new \Exception("Failed to read Excel file info.");
            }

            $this->processRows($excelInfo);
            DB::commit();
            Log::info("ProcessExcelJob started: " . $this->dataset->id . " " . Carbon::now()->toDateTimeString());
        } catch (\Exception $e) {
            Log::error("Exception: " . $e->getMessage());
        }
    }

    private function getFilePath($dataUrl)
    {
        return str_replace("/storage/", "storage/app/public/", $dataUrl);
    }

    private function getExcelFileInfo($filePath)
    {
        try {
            $reader = IOFactory::createReaderForFile($filePath);
            $reader->setReadDataOnly(true);
            $spreadsheet = $reader->load($filePath);
            $sheet = $spreadsheet->getActiveSheet();
            $highestRow = $sheet->getHighestDataRow();
            $highestColumn = $sheet->getHighestDataColumn();
            $headers = $sheet->rangeToArray('A1:' . $highestColumn . "1", null, true, true, true)[1];

            return [
                'sheet' => $sheet,
                'highestRow' => $highestRow,
                'highestColumn' => $highestColumn,
                'headers' => $headers,
            ];
        } catch (ReaderException $e) {
            Log::error("Error reading Excel file: " . $e->getMessage());
            return false;
        }
    }

    private function processRows($excelInfo)
    {
        LazyCollection::make(function () use ($excelInfo) {
            for ($currentRow = 2; $currentRow <= $excelInfo['highestRow']; $currentRow++) {
                yield $excelInfo['sheet']->rangeToArray('A' . $currentRow . ':' . $excelInfo['highestColumn'] . $currentRow, null, true, true, true);
            }
        })->chunk(5000)->each(function ($rows) use ($excelInfo) {
            $this->createDatareadFromExcelFile($excelInfo['headers'], $rows);
        });
    }

    private function createDatareadFromExcelFile($headers, $rows)
    {
        $datareads = collect($rows)->map(function ($row) use ($headers) {
            $row = array_shift($row);
            $rowData = $this->parseRowData($row, $headers);
            $serializeData = $this->serialize($rowData);
            return [
                'dataset_id' => $this->dataset->id,
                'data' => $serializeData,
                'longitude' => $this->dataset->longitude,
                'latitude' => $this->dataset->latitude,
            ];
        });

        Dataread::insert($datareads->toArray());
    }

    private function parseRowData($row, $headers)
    {
        $rowData = [];
        foreach ($headers as $key => $header) {
            // $value = $row[$key] ?? null;
            // $rowData[$header] = $this->parseDate($value) ?? $value;
            $rowData[$header] = $row[$key] ?? null;
        }
        return $rowData;
    }

    private function parseDate($value)
    {
        $formats = [
            'm/d/Y', 'm/d/Y H:i', 'm/d/Y H:i:s',
            'm-d-Y', 'm-d-Y H:i', 'm-d-Y H:i:s',
            'Y-m-d\TH:i', 'Y-m-d\TH:i:s', 'Y/m/d H:i',
            'Y/m/d H:i:s', 'Y-m-d H:i', 'Y-m-d H:i:s',
            'd/m/Y H:i', 'd/m/Y H:i:s', 'd-m-Y H:i',
            'd-m-Y H:i:s', 'Y-m-d', 'Y/m/d', 'd/m/Y',
            'd-m-Y',
        ];

        foreach ($formats as $format) {
            try {
                $date = Carbon::createFromFormat($format, $value);
                if ($date !== false) {
                    $newDate = Carbon::parse($value);
                    return $newDate->format(strpos($format, 'H') === false ? 'Y-m-d' : 'Y-m-d H:i:s');
                }
            } catch (\Exception $e) {
                // No se pudo parsear el valor como fecha, intentar con el siguiente formato
            }
        }
        return null;
    }

    private function serialize($data)
    {
        $serialized = json_encode($data);
        return iconv('UTF-8', 'ASCII//TRANSLIT', $serialized);
    }
}