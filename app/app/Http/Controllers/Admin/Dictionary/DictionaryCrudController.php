<?php
namespace App\Http\Controllers\Admin\Dictionary;

use App\Http\Controllers\Admin\Dictionary\Operations\ShowDictionaryOperation;
use App\Http\Requests\DictionaryRequest;
use App\Models\Dictionary;
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
class DictionaryCrudController extends CrudController{

    use ShowDictionaryOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;

    public function setup()
    {
        CRUD::setModel(\App\Models\Dictionary::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/dictionary');
        CRUD::setEntityNameStrings('Entry', 'Dictionary');
    }

    /**
     * Undocumented function
     *
     * @return void
     */
    protected function setupListOperation(){

        CRUD::column('data_type');
        CRUD::column('default_unit');
        CRUD::column('description');
    }

    protected function setupCreateOperation(){
        $this->crud->setSaveActions(
            [
                'name' => 'Guardar',
                'visible' => function ($crud) {
                    return True;
                },
                'redirect' => function ($crud, $request, $itemId) {
                    return $crud->route;
                },
            ]);

            $this->crud->addField([
                'name' => 'dictionary_data_type',
                'label' => 'Data Type',
                'type' => 'text',
                'required' => true,
            ]);
    
            $this->crud->addField([
                'name' => 'dictionary_default_unit',
                'label' => 'Default unit',
                'type' => 'text',
                'required' => true,
            ]);

            $this->crud->addField([
                'name' => 'dictionary_description',
                'label' => 'Description',
                'type' => 'textarea',
                'required' => false,
            ]);
    }

    protected function store(DictionaryRequest $request)
    {
        $isDictionaryCreated = Dictionary::createDictionary($request, $request->input('engine_id'));
        if ($isDictionaryCreated) {
            Alert::success("Se ha creado correctamente")->flash();
            return Redirect::to(backpack_url('/dictionary'));
        }
        Alert::error("No se ha creado correctamente")->flash();
        return Redirect::to(backpack_url('/dictionary'));
    }

    protected function setupUpdateOperation(){

        $this->setupCreateOperation();
    }

    protected function setupShowOperation(){
        // CRUD::column('name');
        CRUD::column('data_type');
        CRUD::column('default_unit');
        CRUD::column('description');
        CRUD::column('created_at');
        CRUD::column('updated_at');
    }

}