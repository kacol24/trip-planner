<?php

use App\Models\Itinerary;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/calendar', function (){
    $itineraries = Itinerary::orderBy('date')->get();

    return view('calendar_view', compact('itineraries'));
});
