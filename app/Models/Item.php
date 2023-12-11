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

    protected $attributes = ['current_quantity' => 0];
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
    public function moderate()
    {
        dd($this);
    }
 
}
