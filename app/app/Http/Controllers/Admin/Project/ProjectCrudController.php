<?php

namespace App\Http\Controllers\Admin\Project;

use App\Models\Project;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

class ProjectCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;

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
        CRUD::column('description');
        CRUD::column('entity');
        CRUD::column('url');
        CRUD::column('access');
        CRUD::column('data_source');
    }

    public function setupCreateOperation(){
        $this->crud->setValidation(
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
    }
}
