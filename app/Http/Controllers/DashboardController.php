<?php

namespace App\Http\Controllers;

use App\Models\WorkingSite;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $sites = WorkingSite::all();

        return view('dashboard', compact('sites'));
    }
}
