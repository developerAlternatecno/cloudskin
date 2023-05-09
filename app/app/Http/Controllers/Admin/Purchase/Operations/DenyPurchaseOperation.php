<?php

namespace App\Http\Controllers\Admin\Purchase\Operations;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Route;
use Prologue\Alerts\Facades\Alert;

trait DenyPurchaseOperation
{
    /**
     * Define which routes are needed for this operation.
     *
     * @param string $segment    Name of the current entity (singular). Used as first URL segment.
     * @param string $routeName  Prefix of the route name.
     * @param string $controller Name of the current CrudController.
     */
    protected function setupDenyPurchaseRoutes($segment, $routeName, $controller)
    {
        Route::get($segment.'/{id}/deny_purchase', [
            'as'        => $routeName.'.deny_purchase',
            'uses'      => $controller.'@deny_purchase',
            'operation' => 'deny_purchase',
        ]);
    }

    /**
     * Add the default settings, buttons, etc that this operation needs.
     */
    protected function setupDenyPurchaseDefaults()
    {
        $this->crud->allowAccess(['deny_purchase']);

        $this->crud->operation('deny_purchase', function () {
            $this->crud->loadDefaultOperationSettingsFromConfig();
        });

        $this->crud->operation('list', function () {
            $this->crud->addButton('line', 'deny_purchase', 'view', 'crud::buttons.deny_purchase', 'end');
        });
    }

    public function deny_purchase()
    {
        try
        {
            $this->crud->hasAccessOrFail('deny_purchase');

            $purchase = $this->crud->getCurrentEntry();

            if (!$purchase){
                Alert::error("Purchase does not exist")->flash();
                return Redirect::to(backpack_url('/purchase'));
            }

            $purchase->delete();
            Alert::success("Purchase denied correctly")->flash();

            return Redirect::to(backpack_url('/purchase'));
        }
        catch (\Exception $e)
        {
            Log::info($e->getMessage());
            Alert::error("Error while denying the purchase")->flash();
            return Redirect::to(backpack_url('/purchase'));
        }
    }
}