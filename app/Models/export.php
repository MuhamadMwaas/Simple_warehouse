<?php

namespace App\Models;

use App\Events\deleteExport;
use App\Events\ExportCreating;
use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class export extends Model
{
    use HasFactory;
    use CrudTrait;

    protected $fillable = ['item_id', 'quantity', 'customer_id', 'price',];

    protected $dispatchesEvents = [
        'creating' => ExportCreating::class,
        'deleting' => deleteExport::class
    ];

    public function customer()
    {
        return $this->belongsTo(customer::class, 'customer_id', 'id');
    }

    public function item()
    {
        return $this->belongsTo(Item::class, 'item_id', 'id');
    }

    public function getCurrentQuantity()
    {
        return $this->item->current_quantity;
    }




    // protected static function boot()
    // {
    //     parent::boot();

    //     static::saving(function ($model) {

    //     });
    // }

}
