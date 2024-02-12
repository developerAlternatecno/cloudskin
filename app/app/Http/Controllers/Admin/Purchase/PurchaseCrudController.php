<?php

namespace App\Http\Controllers\Admin\Purchase;

use App\Http\Controllers\Admin\Purchase\Operations\ShowPurchaseOperation;
use App\Http\Requests\PurchaseRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Event;

use App\Models\Purchase;
use Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

/**
 * Class PurchaseCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class PurchaseCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    // use ValidatePurchaseOperation;
    // use DenyPurchaseOperation;
    use ShowPurchaseOperation;
    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     *
     * @return void
     */
    public function setup()
    {
        CRUD::setModel(\App\Models\Purchase::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/purchase');
        CRUD::setEntityNameStrings('purchase', 'purchases');
    }

    /**
     * Define what happens when the List operation is loaded.
     *
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        $user_id = Auth::id();

        // añadir un boton en línea
        //$this->crud->addButton('line', 'buyer_doc', 'view', 'crud::buttons.buyer_doc', 'beginning');

        $this->crud->addClause('where', 'user_id', $user_id);
        $this->crud->addClause('where', 'is_validated', true);

        // $this->crud->addColumn([
        //     'label' => 'Comprador',
        //     'type' => 'text',
        //     'name' => 'buyerName'
        // ]);

        $this->crud->addColumn([
            'label' => 'Dataset',
            'type' => 'text',
            'name' => 'datasetName'
        ]);

        $this->crud->addColumn([
            'name' => 'url',
            'label' => 'URL',
            'type' => 'model_function',
            'function_name' => 'getDatasetUrlAttribute',
        ]);

        CRUD::column('type');

        /**
         * Columns can be defined using the fluent syntax or array syntax:
         * - CRUD::column('price')->type('number');
         * - CRUD::addColumn(['name' => 'price', 'type' => 'number']);
         */
    }
}
