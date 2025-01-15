<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

class AttributeService
{
    /**
     * get all Attributess
     */
    public function getAttributes()
    {
        $attributes = DB::table('attributes');

        return $attributes;
    }
}