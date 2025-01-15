<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description', 'quantity', 'measurement_id', 'attributes', 'availability'];

    public function measurement()
    {
        return $this->belongsTo(Measurement::class);
    }

    public function inventories()
    {
        return $this->hasMany(Inventory::class);
    }

    public function item_attributes()
    {
        return $this->hasMany(ItemAttribute::class);
    }
}
