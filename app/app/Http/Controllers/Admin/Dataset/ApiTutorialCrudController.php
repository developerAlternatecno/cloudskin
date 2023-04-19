<?php

namespace App\Http\Controllers\Admin\Dataset;

use App\Http\Requests\EngineRequest;
use App\Models\Dataset;
use App\Models\Engine;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use Prologue\Alerts\Facades\Alert;

/**
 * Class EngineCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class ApiTutorialCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;


    public function index()
    {
        $this->crud->setListView('my_custom_view');
        $this->data['title'] = 'My Custom View';
        return view('crud::apitutorial', $this->data);
    }


}

