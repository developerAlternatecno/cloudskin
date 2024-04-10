<?php

namespace App\Http\Controllers\Admin\Purchase\Operations;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Route;
use Prologue\Alerts\Facades\Alert;

trait ValidatePurchaseOperation
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
        Route::get($segment.'/{id}/validate_purchase', [
            'as'        => $routeName.'.validate_purchase',
            'uses'      => $controller.'@validate_purchase',
            'operation' => 'validate_purchase',
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
            $this->crud->addButton('line', 'validate_purchase', 'view', 'crud::buttons.validate_purchase', 'beginning');
        });
    }

    public function validate_purchase()
    {
        try
        {
            $this->crud->hasAccessOrFail('validate');

            $purchase = $this->crud->getCurrentEntry();

            if (!$purchase){
                Alert::error("Purchase does not exist")->flash();
                return Redirect::to(backpack_url('/purchase'));
            }

            $purchase->is_validated = true;
            $purchase->save();

            Alert::success("Purchase validated correctly")->flash();

            return Redirect::to(backpack_url('/purchase'));
        }
        catch (\Exception $e)
        {
            Log::info($e->getMessage());
            Alert::error("Error while validating the purchase")->flash();
            return Redirect::to(backpack_url('/purchase'));
        }
    }
}
