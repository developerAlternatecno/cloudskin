<?php

namespace App\Http\Controllers\Admin\Sales\Operations;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Route;
use Prologue\Alerts\Facades\Alert;

trait DenySalesOperation
{
    /**
     * Define which routes are needed for this operation.
     *
     * @param string $segment    Name of the current entity (singular). Used as first URL segment.
     * @param string $routeName  Prefix of the route name.
     * @param string $controller Name of the current CrudController.
     */
    protected function setupDenysaleRoutes($segment, $routeName, $controller)
    {
        Route::get($segment.'/{id}/deny_sale', [
            'as'        => $routeName.'.deny_sale',
            'uses'      => $controller.'@deny_sale',
            'operation' => 'deny_sale',
        ]);
    }

    /**
     * Add the default settings, buttons, etc that this operation needs.
     */
    protected function setupDenysaleDefaults()
    {
        $this->crud->allowAccess(['deny_sale']);

        $this->crud->operation('deny_sale', function () {
            $this->crud->loadDefaultOperationSettingsFromConfig();
        });

        $this->crud->operation('list', function () {
            $this->crud->addButton('line', 'deny_sale', 'view', 'crud::buttons.deny_sale', 'end');
        });
    }

    public function deny_sale()
    {
        try
        {
            $this->crud->hasAccessOrFail('sale_sale');

            $sale = $this->crud->getCurrentEntry();

            if (!$sale) {
                Alert::error("Sale does not exist")->flash();
                return Redirect::to(backpack_url('/sale'));
            }

            $sale->delete();
            Alert::success("Sale denied correctly")->flash();

            return Redirect::to(backpack_url('/sale'));
        }
        catch (\Exception $e)
        {
            Log::info($e->getMessage());
            Alert::error("Error while denying the sale")->flash();
            return Redirect::to(backpack_url('/sale'));
        }
    }
}