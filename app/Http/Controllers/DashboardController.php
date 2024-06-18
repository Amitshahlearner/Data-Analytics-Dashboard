<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Data;

class DashboardController extends Controller
{
    public function index()
    {
	$data = Data::all();
        return view('dash', compact('data'));
    }
}

