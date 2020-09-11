<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/activity', 'ActivityManagementController@storeActivity'); //สร้าง activity
Route::post('/activity/edit', 'ActivityManagementController@editActivity'); //edit activity
Route::post('/activity/delete', 'ActivityManagementController@deleteActivity'); // delete activity

Route::get('/activity', 'ActivityManagementController@indexAllActivity'); //ดู Activity
Route::get('/activity/{activity_id}', 'ActivityManagementController@indexActivity'); //ดู Activity