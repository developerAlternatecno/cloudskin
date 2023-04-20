<?php

namespace App\Http\Controllers\Admin\Dataset\Operations;

use Illuminate\Support\Facades\Route;

trait DatasetPurchaseOperation
{
    /**
     * Define which routes are needed for this operation.
     *
     * @param string $segment    Name of the current entity (singular). Used as first URL segment.
     * @param string $routeName  Prefix of the route name.
     * @param string $controller Name of the current CrudController.
     */
    protected function setupDatasetPurchaseRoutes($segment, $routeName, $controller)
    {
        Route::get($segment.'/{id}/purchase', [
            'as'        => $routeName.'.purchase',
            'uses'      => $controller.'@purchase',
            'operation' => 'purchase',
        ]);
    }

    /**
     * Add the default settings, buttons, etc that this operation needs.
     */
    protected function setupDatasetPurchaseDefaults()
    {
        $this->crud->allowAccess(['purchase']);
        $this->crud->setOperationSetting('setFromDb', true);

        $this->crud->operation('purchase', function () {
            $this->crud->loadDefaultOperationSettingsFromConfig();
        });
        $this->crud->operation('list', function () {
            $this->crud->addButton('line', 'purchase', 'view', 'crud::buttons.purchase', 'beginning');
        });
    }


    public function purchase($dataset_id)
    {
        // get the info for that entry
        $this->data['entry'] = $this->crud->getEntry($dataset_id);
        $this->data['title'] = $this->crud->getTitle() ?? trans('backpack::crud.preview').' '.$this->crud->entity_name;

        $dataset = $this->crud->getCurrentEntry();

        $this->crud->addField([
            'name' => 'name',
            'label' => 'Name',
            'type' => 'text',
            'value' => $dataset->name,
            'attributes' => [
                'readonly' => 'readonly',
            ]
        ]);

        $this->crud->addField([
            'name' => 'type',
            'label' => 'Type',
            'type' => 'text',
            'value' => ucfirst($dataset->type),
            'attributes' => [
                'readonly' => 'readonly',
            ]
        ]);

        $this->crud->addField([
            'name' => 'price',
            'label' => 'Price (€)',
            'type' => 'text',
            'value' => $this->crud->getCurrentEntry()->price,
            'attributes' => [
                'readonly' => 'readonly',
            ]
        ]);

        if($dataset->type == 'rental'){
            $this->crud->addField([
                'name' => 'rental_period',
                'label' => 'Rental period',
                'type' => 'select_from_array',
                'options' => [
                    '1' => '1 month',
                    '3' => '3 months',
                    '6' => '6 months',
                    '12' => '12 months',
                ],
                'allows_null' => false,
                'default' => '1',
                'attributes' => [
                    'required' => 'required',
                ]
            ]);
        }

        $this->crud->addField([
            'name' => 'user_id',
            'type' => 'hidden',
            'value' => auth()->user()->id,
        ]);

        $this->crud->addField([
            'name' => 'custom_button',
            'type' => 'custom_html',
            'value' => '<a href="' . backpack_url('dataset/' . $this->crud->getCurrentEntryId() . '/show') . '" class="btn btn-sm btn-link"><i class="las la-redo"></i> Dataset Preview</a>',
        ]);

        $this->data['crud'] = $this->crud;
        return view('crud::purchase', $this->data);
    }
}
