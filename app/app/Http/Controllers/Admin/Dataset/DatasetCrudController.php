<?php

namespace App\Http\Controllers\Admin\Dataset;

use App\Http\Controllers\Admin\Dataset\Operations\DatasetPurchaseOperation;
use App\Models\Dataset;
use App\Models\Engine;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use Prologue\Alerts\Facades\Alert;

/**
 * Class DatasetCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class DatasetCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
//    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
//    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;
    use DatasetPurchaseOperation;

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     *
     * @return void
     */
    public function setup()
    {
        CRUD::setModel(\App\Models\Dataset::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/dataset');
        CRUD::setEntityNameStrings('Dataset', 'Available Datasets');
    }

    /**
     * Define what happens when the List operation is loaded.
     *
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        CRUD::column('name');
        CRUD::column('description');
        CRUD::column('location');
        CRUD::column('url');
        CRUD::column('type');
        CRUD::column('updated_at');

        $this->crud->addClause('whereDoesntHave', 'purchases');

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
        $this->crud->setSaveActions([
            'name' => 'Guardar',
            'visible' => function ($crud) {
                return True;
            },
            'redirect' => function ($crud, $request, $itemId) {
                return $crud->route;
            },
        ]
        );

        $this->crud->addField([
            'name' => 'dataset_name',
            'label' => 'Dataset name',
            'type' => 'text',
            'required' => true,
        ]);

        $this->crud->addField([
            'name' => 'dataset_type',
            'label' => 'Sales method',
            'type' => 'select_from_array',
            'required' => true,
            'options' => Dataset::DATASET_TYPES,
        ]);

        $this->crud->addField([
            'name' => 'dataset_price',
            'label' => 'Selling price (€)',
            'type' => 'number',
            'required' => true,
            'hint' => 'The sales price for rental cases will be on a monthly basis.',
        ]);

        $this->crud->addField([
            'name' => 'dataset_description',
            'label' => 'Dataset description',
            'type' => 'textarea',
            'required' => true,
        ]);

        $this->crud->addField([
            'name' => 'dataset_checkbox',
            'id' => 'dataset_checkbox',  
            'label' => 'Geo-referenced data ?',
            'type' => 'checkbox',
            'hint' => 'If the data are geo-referenced, when sending data via API it will be necessary to indicate the latitude and longitude of the point of interest, using the "latitude" and "longitude" fields.',
            'default' => false,
        ]);

        $this->crud->addField([
            'name' => 'latitude',
            'label' => 'Latitude',
            'type' => 'text',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-6', // Ajusta la clase de ancho según tus necesidades
                'style' => 'display:none;', // Oculta el campo por defecto
                'id' => 'latitude-field',
            ],
            'attributes' => [
                'class' => 'form-control',
            ],
        ]);

        $this->crud->addField([
            'name' => 'longitude',
            'label' => 'Longitude',
            'type' => 'text',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-6', // Ajusta la clase de ancho según tus necesidades
                'style' => 'display:none;', // Oculta el campo por defecto
                'id' => 'longitude-field',
            ],
            'attributes' => [
                'class' => 'form-control',
            ],
        ]);

        $this->crud->addField([
            'name' => 'dataset_license',
            'label' => 'License',
            'type' => 'select_from_array',
            'required' => true,
            'options' => Dataset::DATASET_LICENSES,
        ]);

        $this->crud->addField([
            'name' => 'provider_doc',
            'label' => 'Data use contract',
            'type' => 'upload',
            'upload' => true,
            'hint'=> 'Upload a optional data use contract, if you do, you will have to validate manually that the buyer has signed the contract.',
        ]);

        $this->crud->addField([
            'name' => 'autovalidate_sales',
            'label' => 'Auto-validate sales',
            'type' => 'checkbox',
            'hint'=> 'Automatically validates sales if this field is checked.',
        ]);

        $this->crud->addField([
            'name' => 'engine_template',
            'label' => 'Format of generated data',
            'type' => 'repeatable', // tipo de campo
            'fields' => [
                [
                    'name' => 'field_name',
                    'label' => 'Field name',
                    'type' => 'text',
                    'required' => true,
                    'allows_null' => false,
                    'wrapperAttributes' => [
                        'class' => 'col-md-3',
                    ],
                ],
                [
                    'name' => 'type',
                    'label' => 'Type',
                    'type' => 'select_from_array',
                    'options' => array_keys(Engine::ENGINE_TYPING),
                    'required' => true,
                    'allows_null' => false,
                    'wrapperAttributes' => [
                        'class' => 'col-md-3',
                    ],
                ],
                [
                    'name' => 'description',
                    'label' => 'Description',
                    'type' => 'textarea',
                    'wrapperAttributes' => [
                        'class' => 'col-md-3',
                    ],
                ],
            ],
            'new_item_label' => 'Add data',
        ]);
    }


    public function store(Request $request)
    {
        $request_array = $this->crud->getStrippedSaveRequest();

        $engine_id = Engine::createEngineFromCrudController($request_array['engine_template']);
        if (!$engine_id) {
            Log::error("Error creando el engine");
            Alert::error("No se ha creado correctamente");
            return Redirect::to(backpack_url('/engine'));
        }

        $isDatasetCreated = Dataset::createDataset($request, $engine_id);

        if ($engine_id && $isDatasetCreated) {
            Alert::success("Se ha creado correctamente");
            return Redirect::to(backpack_url('/dataset'));
        }
        Alert::error("No se ha creado correctamente");
        return Redirect::to(backpack_url('/dataset'));

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
                'label' => 'Last 5 records',
                'type' => 'custom_html',
                'value' => Dataset::where('id', $this->crud->getCurrentEntry()->id)->first()->generateLastDataReadsTable(),
            ]
        ]);

    }
}
