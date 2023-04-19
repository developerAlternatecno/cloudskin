<?php

namespace App\Http\Controllers\Admin\Dataset;

use App\Http\Requests\DatasetRequest;
use App\Models\Dataset;
use App\Models\Engine;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
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
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     *
     * @return void
     */
    public function setup()
    {
        CRUD::setModel(\App\Models\Dataset::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/dataset');
        CRUD::setEntityNameStrings('dataset', 'datasets');
    }

    /**
     * Define what happens when the List operation is loaded.
     *
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        CRUD::column('created_at');
        CRUD::column('description');
        CRUD::column('url');
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
            'label' => 'Nombre del dataset',
            'type' => 'text',
            'required' => true,
        ]);

        $this->crud->addField([
            'name' => 'dataset_type',
            'label' => 'Modo de venta',
            'type' => 'select_from_array',
            'required' => true,
            'options' => Dataset::DATASET_TYPES,
        ]);

        $this->crud->addField([
            'name' => 'dataset_price',
            'label' => 'Precio de venta (€)',
            'type' => 'number',
            'required' => true,
            'hint' => 'El precio de venta para casos de alquiler será mensual',
        ]);

        $this->crud->addField([
            'name' => 'dataset_license',
            'label' => 'Licencia',
            'type' => 'select_from_array',
            'required' => true,
            'options' => Dataset::DATASET_LICENSES,
        ]);

        $this->crud->addField([
            'name' => 'dataset_description',
            'label' => 'Descripción del dataset',
            'type' => 'textarea',
            'required' => true,
        ]);

        $this->crud->addField([
            'name' => 'dataset_checkbox',
            'label' => '¿ Datos geo-referenciados ?',
            'type' => 'checkbox',
            'hint' => 'Si los datos son geo-referenciados, a la hora del envío de datos vía API será necesario indicar la latitud y longitud del punto de interés, mediante los campos "latitude" y "longitude".',
        ]);

        $this->crud->addField([
            'name' => 'engine_template',
            'label' => 'Formato de los datos generados',
            'type' => 'repeatable', // tipo de campo
            'fields' => [
                [
                    'name' => 'field_name',
                    'label' => 'Nombre del campo',
                    'type' => 'text',
                    'required' => true,
                    'allows_null' => false,
                    'wrapperAttributes' => [
                        'class' => 'col-md-3',
                    ],
                ],
                [
                    'name' => 'type',
                    'label' => 'Tipo',
                    'type' => 'select_from_array',
                    'options' => array_keys(Engine::ENGINE_TYPING),
                    'required' => true,
                    'allows_null' => false,
                    'wrapperAttributes' => [
                        'class' => 'col-md-3',
                    ],
                ],
                [
                    'name' => 'field_unit',
                    'label' => 'Unidad del valor',
                    'type' => 'text',
                    'wrapperAttributes' => [
                        'class' => 'col-md-3',
                    ],
                ],
                [
                    'name' => 'length',
                    'label' => 'Longitud',
                    'type' => 'number',
                    'wrapperAttributes' => [
                        'class' => 'col-md-3',
                    ],
                ],
            ],
            'new_item_label' => 'Añadir dato',
            'hint' => 'El campo unidad es solo si es necesario, el campo longitud solo si el tipo es string',
        ]);
    }


    public function store()
    {
        $request = $this->crud->getStrippedSaveRequest();

        $engine_id = Engine::createEngineFromCrudController($request['engine_template']);
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
                'label' => 'Últimos 5 registros',
                'type' => 'custom_html',
                'value' => Dataset::where('id', $this->crud->getCurrentEntry()->id)->first()->generateLastDataReadsTable(),
            ]
        ]);

    }
}
