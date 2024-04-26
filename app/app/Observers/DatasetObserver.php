<?php

namespace App\Observers;

use App\Jobs\ProcessExcelJob;
use App\Models\Dataset;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DatasetObserver
{
    /**
     * Handle the Dataset "created" event.
     *
     * @param  \App\Models\Dataset  $dataset
     * @return void
     */
    public function created(Dataset $dataset)
    {
        Log::info('Dataset created: ' . $dataset->id);
    }

    /**
     * Handle the Dataset "updated" event.
     *
     * @param  \App\Models\Dataset  $dataset
     * @return void
     */
    public function updated(Dataset $dataset)
    {
        $dataset->refresh();
        Log::info('Dataset updatedd: ' . $dataset->id);
        if ($this->checkIfDatasetHasStaticDataAndDataUrl($dataset)) {
            Log::info('Dataset has static data and data url');
            ProcessExcelJob::dispatch($dataset)->delay(Carbon::now()->addSeconds(10));
        }
    }

    /**
     * Handle the Dataset "deleted" event.
     *
     * @param  \App\Models\Dataset  $dataset
     * @return void
     */
    public function deleted(Dataset $dataset)
    {
        //
    }

    /**
     * Handle the Dataset "restored" event.
     *
     * @param  \App\Models\Dataset  $dataset
     * @return void
     */
    public function restored(Dataset $dataset)
    {
        //
    }

    /**
     * Handle the Dataset "force deleted" event.
     *
     * @param  \App\Models\Dataset  $dataset
     * @return void
     */
    public function forceDeleted(Dataset $dataset)
    {
        //
    }

    private function checkIfDatasetHasStaticDataAndDataUrl(Dataset $dataset): bool
    {
        Log::info('Dataset start upload: ');
        if ($dataset->id && !empty($dataset->data_type) && $dataset->data_type == 'Static Data' && !empty($dataset->data_url)) {
            Log::info('Dataset checkIfDatasetHasStaticDataAndDataUrl');
            $fileExtensions = ['xlsx', 'xls', 'csv'];
            $urlPath = pathinfo($dataset->data_url, PATHINFO_EXTENSION);
            Log::info('urlPath: ' . $urlPath);
            if (in_array($urlPath, $fileExtensions)) {
                Log::info('Fichero OK');
                return true;
            }
        }
        return false;
    }
}
