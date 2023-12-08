<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use CrudTrait;
    use HasFactory;
    protected $guarded = ['id'];

    protected $fillable = ['code', 'name', 'min_quantity', 'current_quantity', 'price', 'group_id', 'image', 'active'];

    public function group()
    {
        return $this->belongsTo(group::class, 'group_id', 'id');
    }

    public function customers()
    {
        return $this->belongsToMany(customer::class, 'exports', 'item_id', 'customer_id');
    }

    public function suppliers()
    {
        return $this->belongsToMany(supplier::class, 'imports', 'item_id', 'supplier_id');
    }


    public function getStatusIcon()
    {



        // check the status and return the appropriate icon and route
        switch ($this->active) {

            case 0:
                return '<i class="las la-times"></i>';
            case 1:
                return  '<i class="las la-check"></i>';
        }
    }
}
