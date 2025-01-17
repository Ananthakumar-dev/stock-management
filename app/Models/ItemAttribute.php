<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ItemAttribute extends Model
{
    protected $table = 'item_attributes';

    public function attribute()
    {
        return $this->belongsTo(Attribute::class);
    }
}
