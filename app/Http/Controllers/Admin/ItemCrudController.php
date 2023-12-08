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
        CRUD::column(['name' => 'code']);
        CRUD::column(['name' => 'name']);
        $this->crud->addColumn([
            'name' => 'groups',
            'type' => 'select',
            'label' => 'Group Name',
            'entity' => 'group',
            'attribute' => 'name',
            'model' => group::class,
        ]);
        CRUD::column(['name' => 'min_quantity']);
        CRUD::column(['name' => 'current_quantity']);
        CRUD::column(['name' => 'price']);
        CRUD::column([
            'name' => 'active',
            'label' => "Active",
            'type' => 'model_function',
            'function_name' => 'getStatusIcon',
            'escaped' => false,
        ]);

        CRUD::column(['name' => 'created_at']);
        $this->crud->addColumn([
            'type' => 'image',
            'name' => 'image',
            'label' => 'image',
            'disk' => 'public',
            'path' => 'images',
        ]);

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
        //group list filed
        CRUD::field([
            'type' => 'select',
            'name' => 'group_id',
            'entity' => 'group',
            'attribute' => 'name',
            'pivot' => true,
        ]);

        //image uload filed
        CRUD::field('image')
            ->type('upload')
            ->withFiles([
                'disk' => 'public', // the disk where file will be stored
                'path' => 'images', // the path inside the disk where file will be stored
            ]);

        //add validation
        CRUD::setValidation(ItemRequest::class);


        CRUD::setFromDb(); // set fields from db columns.

        CRUD::field('current_quantity')->remove();

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
        //group list filed
        CRUD::field([
            'type' => 'select',
            'name' => 'group_id',
            'entity' => 'group',
            'attribute' => 'name',
            'pivot' => true,
        ]);
        CRUD::field([
            'type' => 'upload',
            'name' => 'image',
            'disk' => 'public',
            'path' => 'images',
            'withFiles' => true,
            'files' => true


        ]);



        CRUD::setFromDb(); // set fields from db columns.

        CRUD::field('current_quantity')->remove();
    }

    protected function setupShowOperation()
    {

        CRUD::setFromDb(); // set fields from db columns.
        CRUD::column('image')->remove();

        //make image column view the image
        CRUD::column([
            'type' => 'image',
            'name' => 'image',
            'label' => 'image',
            'disk' => 'public',
            'path' => 'images',
            'height' => '120px',
            'width' => '120px',
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

    //update action
    public function update()
    {
        //custom validation for update
        CRUD::setValidation(
            [
                'name' => 'required|min:5|max:255|unique:items,name,' . $this->crud->getRequest()->request->get('id'),
                'code' => 'required|unique:items,code,' . $this->crud->getRequest()->request->get('id'),
                'min_quantity' => 'required|integer|min:1',
                'image' => 'mimes:jpeg,png,jpg,svg,.png',
                'price' => 'required|numeric|min:0.1',
            ]
        );
        // dd($this->crud->getRequest()->request);
        $response = $this->parentUpdate();
        return $response;
    }
}
