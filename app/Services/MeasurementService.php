<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

class MeasurementService
{
    /**
     * get all measurements
     */
    public function getMeasurements()
    {
        $measurements = DB::table('measurements');

        return $measurements;
    }
}