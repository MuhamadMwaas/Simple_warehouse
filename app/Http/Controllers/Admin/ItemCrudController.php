<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\ItemRequest;
use App\Models\group;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use GuzzleHttp\Psr7\Request;
use Illuminate\Validation\Rule;

/**
 * Class ItemCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class ItemCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation {
        create as parentCreate;
    }
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation {
        show as parentShow;
    }
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation {
        update as parentUpdate;
    }
    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     * 
     * @return void
     */
    public function setup()
    {
        CRUD::setModel(\App\Models\Item::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/item');
        CRUD::setEntityNameStrings('item', 'items');
    }


    /**
     * Define what happens when the List operation is loaded.
     * 
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {

        $this->itemcolumn();
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
        //items list filed
        $this->itemfiled();

        //add validation
        CRUD::setValidation(ItemRequest::class);


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
        $this->itemcolumn();

        CRUD::column('image')->remove();

        // //make image column view the image
        CRUD::column([
            'type' => 'image',
            'name' => 'image',
            'label' => 'image',
            'disk' => 'public',
            'path' => 'images',
            'height' => '250px',

        ])->after('active');
    }


    //create action
    public function create()
    {
        if (group::count() < 1) {
            \Alert::error('there is no group')->flash();

            return redirect()->route('item.index');
        }
        $response = $this->parentCreate();

        return $response;
    }

    //show action
    // public function show($id)
    // {

    //     $response = $this->parentShow($id);

    //     return $response;
    // }

    protected function itemfiled()
    {
        CRUD::field([
            'type' => 'select',
            'name' => 'group_id',
            'entity' => 'group',
            'attribute' => 'name',
            'pivot' => true,
        ]);
        CRUD::field(['name' => 'name']);
        CRUD::field(['name' => 'code']);
        CRUD::field(['name' => 'min_quantity']);
        CRUD::field(['name' => 'price']);
        CRUD::field([
            'name' => 'image',
            'type' => 'upload',
            'withFiles' => [
                'disk' => 'public', // the disk where file will be stored
                'path' => 'images', // the path inside the disk where file will be stored
            ],
            'upload' => true,
        ]);
        CRUD::field(['name' => 'active']);
    }


    protected function itemcolumn()
    {
        CRUD::column(['name' => 'code']);
        CRUD::column(['name' => 'name']);
        $this->crud->addColumn([
            'name' => 'group',
            'type' => 'select',
            'label' => 'Group Name',
            'entity' => 'group',
            'attribute' => 'name',
            'model' => group::class,
        ]);
        CRUD::column(['name' => 'min_quantity']);
        CRUD::column(['name' => 'current_quantity', 'type' => 'number']);
        CRUD::column([
            'name' => 'price',
            'label' => "price",
            'type' => 'view',
            'view' => 'Crud.Price_column',
            'escaped' => false,
        ]);
        CRUD::column([
            'name' => 'active',
            'label' => "Active",
            'type' => 'view',
            'view' => 'Crud.Active_column',
            'escaped' => false,
        ]);
        CRUD::column(['name' => 'created_at', 'format' => 'Y-M-D - H:mm']);
        $this->crud->addColumn([
            'type' => 'image',
            'name' => 'image',
            'label' => 'image',
            'disk' => 'public',
            'path' => 'images',
        ]);
    }
}
