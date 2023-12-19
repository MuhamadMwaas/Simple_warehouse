<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\CustomerRequest;
use App\Models\customer;
use App\Models\export;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class CustomerCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class CustomerCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation {
        destroy as parentDestroy;
    }
    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     * 
     * @return void
     */
    public function setup()
    {
        CRUD::setModel(\App\Models\Customer::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/customer');
        CRUD::setEntityNameStrings('customer', 'customers');
    }

    /**
     * Define what happens when the List operation is loaded.
     * 
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        $this->customercolumns();
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
        CRUD::setValidation(CustomerRequest::class);
        $this->customerfields();
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


    protected function setupShowOperation()
    {
        $this->customercolumns();
    }



    // delete element
    public function destroy($id)
    {
        $count = customer::withCount('exports')->find($this->crud->getCurrentEntryId())->exports_count;
        if ($count > 0) {
            return ["error" => ['This customer cannot be deleted there is : ' . $count . ' export record connect to it']];
        } else {

            return $this->parentDestroy($id);
        }
    }

    protected function customercolumns()
    {
        CRUD::column(['name' => 'name', 'label' => 'Name', 'type' => 'string']);
        CRUD::column(['name' => 'email', 'label' => 'Email', 'type' => 'email']);
        CRUD::column(['name' => 'phone', 'label' => 'Phone', 'type' => 'phone']);
        CRUD::column(['name' => 'active', 'label' => 'status', 'type' => 'boolean']);
        CRUD::column(['name' => 'created_at', 'label' => 'created_at', 'type' => 'date']);
    }

    protected function customerfields()
    {
        CRUD::field(['name' => 'name', 'label' => 'Name', 'type' => 'text']);
        CRUD::field(['name' => 'email', 'label' => 'Email', 'type' => 'email']);
        CRUD::field(['name' => 'phone', 'label' => 'Phone', 'type' => 'number']);
        CRUD::field(['name' => 'active', 'label' => 'status', 'type' => 'checkbox', 'default' => true]);
    }

    
}
