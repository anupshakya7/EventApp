<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Event_Record_Controller;
use App\Models\Event_Record;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});


//Events Routes//

Route::get('events',[Event_Record_Controller::class,"index"]);
Route::post('add-event',[Event_Record_Controller::class,"store"]);
Route::get('edit-event/{id}',[Event_Record_Controller::class,"edit"]);
Route::put('update-event',[Event_Record_Controller::class,"update"]);
Route::get('delete-event/{id}',[Event_Record_Controller::class,"destroy"]);
Route::get('finish-event',[Event_Record_Controller::class,"finishEvents"]);
Route::get('upcoming-event',[Event_Record_Controller::class,"upcomingEvents"]);
//End Events Routes//