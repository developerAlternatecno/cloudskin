<?php

namespace App\Http\Controllers\Admin\Sales;

use App\Http\Controllers\Admin\Sales\Operations\DenySalesOperation;
use App\Http\Controllers\Admin\Sales\Operations\ValidateSalesOperation;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Illuminate\Support\Facades\Auth;


/**
 * Class SaleCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class SalesCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use ValidateSalesOperation;
    use DenySalesOperation;
    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     *
     * @return void
     */
    public function setup()
    {
        CRUD::setModel(\App\Models\Sale::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/sale');
        CRUD::setEntityNameStrings('Sale', 'Sales');
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
        $this->crud->addButton('line', 'buyer_doc', 'view', 'crud::buttons.buyer_doc', 'beginning');

        $this->crud->addClause('whereHas', 'dataset', function($query) use($user_id) {
            $query
                ->where('user_id', $user_id)
                ->where('is_validated', false);
        });

        $this->crud->addColumn([
            'label' => 'Buyer',
            'type' => 'text',
            'name' => 'buyerName'
        ]);

        $this->crud->addColumn([
            'label' => 'Dataset',
            'type' => 'text',
            'name' => 'datasetName'
        ]);

        CRUD::column('type');

        /**
         * Columns can be defined using the fluent syntax or array syntax:
         * - CRUD::column('price')->type('number');
         * - CRUD::addColumn(['name' => 'price', 'type' => 'number']);
         */
    }
}
