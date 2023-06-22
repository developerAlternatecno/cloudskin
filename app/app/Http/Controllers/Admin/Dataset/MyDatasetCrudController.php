<?php

namespace App\Http\Controllers\Admin\Dataset;

use App\Http\Controllers\Admin\Dataset\Operations\MyDatasetShowOperation;
use App\Models\Dataset;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Illuminate\Support\Facades\Log;


/**
 * Class DatasetCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class MyDatasetCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
//    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;
    use MyDatasetShowOperation;

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     *
     * @return void
     */
    public function setup()
    {
        CRUD::setModel(\App\Models\Dataset::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/mydataset');
        CRUD::setEntityNameStrings('My dataset', 'My datasets');
    }

    /**
     * Define what happens when the List operation is loaded.
     *
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        $user_id = auth()->user()->id;
        $model = $this->crud->getModel();
        $this->crud->query = $model::whereHas('purchases', function ($query) use ($user_id) {
            $query->where('user_id', $user_id)->where('is_validated', true);
        });

        CRUD::column('name');
        CRUD::column('description');
        $this->crud->addColumn([
            'label' => 'URL',
            'type' => 'text',
            'name' => 'url'
        ]);
        CRUD::column('type');
        CRUD::column('updated_at');

        /**
         * Columns can be defined using the fluent syntax or array syntax:
         * - CRUD::column('price')->type('number');
         * - CRUD::addColumn(['name' => 'price', 'type' => 'number']);
         */
    }

    /**
     * Define what happens when the Create operation is loaded.
     *
     * @see https://backpackforlaravel.com/docs/crud-operation-create
     * @return void
     */
    protected function setupCreateOperation()
    {

    }


    public function store()
    {

    }

    /**
     * Define what happens when the Update operation is loaded.
     *
     * @see https://backpackforlaravel.com/docs/crud-operation-update
     * @return void
     */
    protected function setupUpdateOperation()
    {
        $this->setupCreateOperation();
    }

    protected function setupShowOperation()
    {
        CRUD::column('created_at');
        CRUD::column('description');
        CRUD::column('url');
        CRUD::column('type');


        // Agregar la tabla a la configuración de la vista de detalle
        $this->crud->addColumns([
            [
                'name' => 'table_html',
                'label' => 'Últimos 5 registros',
                'type' => 'custom_html',
                'value' => Dataset::where('id', $this->crud->getCurrentEntry()->id)->first()->generateLastDataReadsTable(),
            ]
        ]);
    }
}
