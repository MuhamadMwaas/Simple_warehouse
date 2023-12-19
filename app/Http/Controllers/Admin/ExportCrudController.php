<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\ExportRequest;
use App\Models\customer;
use App\Models\export;

use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class ExportCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class ExportCrudController extends CrudController
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
        CRUD::setModel(\App\Models\Export::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/export');
        CRUD::setEntityNameStrings('export', 'exports');
    }

    /**
     * Define what happens when the List operation is loaded.
     * 
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        $this->exportcolumns();
        // CRUD::setFromDb(); // set columns from db columns.

        /**
         * Columns can be defined using the fluent syntax:
         * - CRUD::column('price')->type('number');
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
        CRUD::setValidation(ExportRequest::class);

        $this->exportfields();
        /**
         * Fields can be defined using the fluent syntax:
         * - CRUD::field('price')->type('number');
         */
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


    protected function exportfields()
    {
        CRUD::field([
            'type' => 'select',
            'name' => 'customer_id',
            'entity' => 'customer',
            'attribute' => 'name',

        ]);
        CRUD::field([
            'type' => 'select',
            'name' => 'item_id',
            'entity' => 'item',
            'attribute' => 'name',
            'pivot' => true,
        ]);

        CRUD::field([
            'name' => 'quantity',
            'label' => 'quantity',
            'type' => 'number',
        ]);
    }

    protected function exportcolumns()
    {
        CRUD::column([
            'name' => 'item',
            'type' => 'select',
            'label' => 'Item Name',
            'entity' => 'item',
            'attribute' => 'name',
            'model' => export::class,
        ]);

        CRUD::column([
            'name' => 'customer',
            'type' => 'select',
            'label' => 'customer Name',
            'entity' => 'customer',
            'attribute' => 'name',
            'model' => export::class,
        ]);
        CRUD::column([
            'name' => 'quantity',
            'label' => 'quantity',
            'type' => 'number',
        ]);
        CRUD::column([
            'name' => 'price',
            'label' => 'price',
            'type' => 'number',
        ]);

        CRUD::column(['name' => 'created_at', 'label' => 'created_at', 'type' => 'date']);
    }
}
