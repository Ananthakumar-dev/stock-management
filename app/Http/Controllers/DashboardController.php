<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class DashboardController extends Controller
{
    /**
     * index page
     */
    public function index()
    {
        return Inertia::render('Dashboard');
    }

    /**
     * Stats
     */
    public function stats()
    {
        return response()->json([
            'users' => DB::table('users')->count(),
            'stores' => DB::table('stores')->count(),
            'items' => DB::table('items')->count(),
            'inventories' => DB::table('inventories')->count(),
        ]);
    }
}
