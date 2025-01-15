<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

class StoreService
{
    /**
     * get all stores
     */
    public function getStores()
    {
        $stores = DB::table('stores');

        return $stores;
    }
}
