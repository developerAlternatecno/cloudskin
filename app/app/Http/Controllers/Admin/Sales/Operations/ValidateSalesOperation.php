<?php

namespace App\Http\Controllers\Admin\Sales\Operations;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Route;
use Prologue\Alerts\Facades\Alert;

trait ValidateSalesOperation
{
    /**
     * Define which routes are needed for this operation.
     *
     * @param string $segment    Name of the current entity (singular). Used as first URL segment.
     * @param string $routeName  Prefix of the route name.
     * @param string $controller Name of the current CrudController.
     */
    protected function setupValidatePurchaseRoutes($segment, $routeName, $controller)
    {
        Route::get($segment.'/{id}/validate_sale', [
            'as'        => $routeName.'.validate_sale',
            'uses'      => $controller.'@validate_sale',
            'operation' => 'validate_sale',
        ]);
    }

    /**
     * Add the default settings, buttons, etc that this operation needs.
     */
    protected function setupValidatePurchaseDefaults()
    {
        $this->crud->allowAccess(['validate']);

        $this->crud->operation('validate', function () {
            $this->crud->loadDefaultOperationSettingsFromConfig();
        });

        $this->crud->operation('list', function () {
            $this->crud->addButton('line', 'validate_sale', 'view', 'crud::buttons.validate_sale', 'beginning');
        });
    }

    public function validate_sale()
    {
        try
        {
            $this->crud->hasAccessOrFail('validate');

            $purchase = $this->crud->getCurrentEntry();

            if (!$purchase){
                Alert::error("Sale does not exist")->flash();
                return Redirect::to(backpack_url('/sale'));
            }

            $purchase->is_validated = true;
            $purchase->save();

            Alert::success("Sale validated correctly")->flash();

            return Redirect::to(backpack_url('/sale'));
        }
        catch (\Exception $e)
        {
            Log::info($e->getMessage());
            Alert::error("Error while validating the sale")->flash();
            return Redirect::to(backpack_url('/sale'));
        }
    }
}
