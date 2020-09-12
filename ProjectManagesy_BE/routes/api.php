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
//Activity
Route::post('/activity', 'ActivityManagementController@storeActivity'); //สร้าง activity
Route::post('/activity/edit', 'ActivityManagementController@editActivity'); //edit activity
Route::post('/activity/delete', 'ActivityManagementController@deleteActivity'); // delete activity

Route::get('/activity', 'ActivityManagementController@indexAllActivity'); //ดู Activity
Route::get('/activity/{activity_id}', 'ActivityManagementController@indexActivity'); //ดู Activity_id

//Admission
Route::post('/admission', 'AdmissionManagementController@storeAdmission'); //สร้าง admission
// Route::post('/admission/edit', 'ActivityManagementController@editActivity'); //edit admision
// Route::post('/admission/delete', 'ActivityManagementController@deleteActivity'); // delete admission

Route::get('/admission', 'AdmissionManagementController@indexAllAdmission'); //ดู Admission
Route::get('/admission/{activity_id}', 'ActivityManagementController@indexAdmission'); //ดู Admisssion_id