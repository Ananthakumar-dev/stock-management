<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
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
}
