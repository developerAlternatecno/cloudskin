<?php

namespace App\Http\Controllers\Admin\Project;

use App\Http\Controllers\Admin\Project\Operations\ShowProjectOperation;
use App\Models\Project;
use App\Models\Dataset;
use App\Models\Purchase;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Prologue\Alerts\Facades\Alert;

class ProjectCrudController extends CrudController
{
    use ShowProjectOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;


    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     *
     * @return void
     */
    public function setup()
    {
        $this->crud->setModel(Project::class);
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/project');
        $this->crud->setEntityNameStrings('Project', 'Projects');
    }

    public function setupListOperation()
    {
        CRUD::column('name');
        CRUD::column('type');
        // CRUD::column('description');
        CRUD::column('entity');
        CRUD::column('url');
        CRUD::column('access');
        // CRUD::column('data_source');
    }

    public function setupCreateOperation()
    {

        $this->crud->setSaveActions(
            [
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
            'name' => 'project_name',
            'label' => 'Project name',
            'type' => 'text',
            'required' => true,
        ]);

        $this->crud->addField([
            'name' => 'project_type',
            'label' => 'Type',
            'type' => 'select_from_array',
            'required' => true,
            'options' => Project::PROJECT_TYPES,
        ]);

        $this->crud->addField([
            'name' => 'project_description',
            'label' => 'Description',
            'type' => 'textarea',
            'required' => false,
        ]);

        $this->crud->addField([
            'name' => 'project_entity',
            'label' => 'Entity',
            'type' => 'text',
            'required' => true,
        ]);

        $this->crud->addField([
            'name' => 'project_url',
            'label' => 'Url',
            'type' => 'text',
            'required' => true,
        ]);

        $this->crud->addField([
            'name' => 'project_access',
            'label' => 'Access',
            'type' => 'select_from_array',
            'required' => true,
            'options' => Project::PROJECT_ACCESS,
        ]);

        $this->crud->addField([
            'name' => 'project_data_source',
            'label' => 'Data Source',
            'type' => 'text',
            'required' => true,

        ]);

        $this->crud->addField([
            'name' => 'dataset_id',
            'label' => 'Dataset Associated',
            'type' => 'select2_from_array',
            'options' => $this->getSelectOptions(),
            'allows_null' => true,
        ]);

        $this->crud->addField([
            'name' => 'custom_hint',
            'label' => 'Custom Hint',
            'type' => 'custom_html',
            'value' => '<p>If you need to create a new dataset, click <a href="' . backpack_url('/dataset/create') . '">here</a>.</p>',
        ]);
    }

    public function store(Request $request)
    {

        $isProjectCreated = Project::createProject($request);
        if ($isProjectCreated) {
            Alert::success("Se ha creado correctamente");
            return Redirect::to(backpack_url('/project'));
        }
        Alert::error("No se ha creado correctamente");
        return Redirect::to(backpack_url('/project'));
    }

    protected function setupUpdateOperation()
    {

        $this->setupCreateOperation();
    }

    protected function setupShowOperation()
    {

        CRUD::column('name');
        CRUD::column('type');
        CRUD::column('description');
        CRUD::column('entity');
        CRUD::column('url');
        CRUD::column('access');
        CRUD::column('data_source');
    }

    private function getSelectOptions()
    {
        $user_id = Auth::id();
        $myDatasets = Dataset::where('user_id', $user_id)->pluck('name', 'id')->toArray();
        $purchaseData = Purchase::where('user_id', $user_id)->with('dataset')->get();
        $purchaseOptions = $purchaseData->mapWithKeys(function ($purchase) {
            return [$purchase->dataset->id => $purchase->dataset->name];
        })->toArray();

        $selectOptions = array_merge($myDatasets, $purchaseOptions);

        return $selectOptions;
    }
}
