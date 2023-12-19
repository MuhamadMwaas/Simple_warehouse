<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class customer extends Model
{
    use HasFactory;
    use CrudTrait;

    protected $fillable = ['name', 'phone', 'email', 'active'];

    public function exports()
    {
        return $this->hasMany(export::class, 'customer_id', 'id');
    }

    public function items()
    {
        return $this->belongsToMany(Item::class, 'exports', 'customer_id', 'item_id');
    }

 
}
