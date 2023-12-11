<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class group extends Model
{
    use CrudTrait;
    use HasFactory;
    protected $guarded = ['id'];

    protected $fillable = ['code', 'name'];

    public function items()
    {
        return $this->hasMany(Item::class, 'group_id', 'id');
    }

  
}
