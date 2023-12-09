<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\GroupsRequest;
use App\Models\group;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class GroupsCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class GroupsCrudController extends CrudController
{
    // use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation {
        index as parentIndex;
    }
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
        CRUD::setModel(\App\Models\Groups::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/groups');
        CRUD::setEntityNameStrings('group', 'groups');
    }

    /**
     * Define what happens when the List operation is loaded.
     * 
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        CRUD::column(['name' => 'name'])->label('Name');
        CRUD::column(['name' => 'code'])->label('Code');
        CRUD::column(['name' => 'created_at'])->label('Created At');


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
        CRUD::setValidation(GroupsRequest::class);
        CRUD::field(['name' => 'name']);
        CRUD::field(['name' => 'code']);

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


    public function destroy($id)
    {
        $count = group::withCount('items')->find($this->crud->getCurrentEntryId())->items_count;
        if ($count > 0) {
            return ["error" => ['This group cannot be deleted there is : ' . $count . ' Item connect to it']];

        } else {

            return $this->parentDestroy($id);
        }
    }
}
