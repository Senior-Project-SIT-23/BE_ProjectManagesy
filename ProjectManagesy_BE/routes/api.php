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
Route::get('/activity/readfile','ActivityManagementController@readActivity');//อ่านfile

//Admission
Route::post('/admission', 'AdmissionManagementController@storeAdmission'); //สร้าง admission
Route::post('/admission/edit', 'AdmissionManagementController@editAdmission'); //edit admision
Route::post('/admission/delete', 'AdmissionManagementController@deleteAdmission'); // delete admission

Route::get('/admission', 'AdmissionManagementController@indexAllAdmission'); //ดู Admission
Route::get('/admission/{activity_id}', 'AdmissionManagementController@indexAdmission'); //ดู Admisssion_id