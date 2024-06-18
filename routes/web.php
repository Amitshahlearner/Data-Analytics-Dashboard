<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DataController;

Route::get('/', [DashboardController::class, 'index']);

Route::get('/data', [DataController::class, 'index']);

/*Route::get('/', function () {
    return view('dashboard');
});
 */
