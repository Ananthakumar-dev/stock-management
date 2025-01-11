<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'designation', 'email', 'phone', 'status'];

    public function inventories()
    {
        return $this->hasMany(Inventory::class);
    }
}
