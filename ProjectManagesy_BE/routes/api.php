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


// Route::group(['middleware' => ['checkauth']], function () {

    //Activity
    //Student
    Route::post('/activity/student', 'ActivityManagementController@storeStudentActivity'); //สร้าง activity
    Route::post('/activity/student/edit', 'ActivityManagementController@editStudentActivity'); //edit activity
    Route::post('/activity/student/delete', 'ActivityManagementController@deleteStudentActivity'); // delete activity

    Route::get('/activity/student', 'ActivityManagementController@indexStudentAllActivity'); //ดู Activity
    Route::get('/activity/student/{activity_id}', 'ActivityManagementController@indexStudentActivity'); //ดู Activity_id
    Route::get('/activity/student/readfilename/{activity_id}', 'ActivityManagementController@readFileStudentActivity'); //ดึงข้อมูลในไฟล์มาแสดง


    //count
    // Route::get('/activity/student/count', 'ActivityManagementController@countStudentAllActivity'); //count ข้อมูล
    

    //Admission
    Route::post('/admission', 'AdmissionManagementController@storeAdmission'); //สร้าง admission
    Route::post('/admission/edit', 'AdmissionManagementController@editAdmission'); //edit admision
    Route::post('/admission/delete', 'AdmissionManagementController@deleteAdmission'); // delete admission

    Route::get('/admission', 'AdmissionManagementController@indexAllAdmission'); //ดู Admission
    Route::get('/admission/{activity_id}', 'AdmissionManagementController@indexAdmission'); //ดู Admisssion_id
// });

#ยิงLogin เพื่อเช็ด auth
Route::post('/check-authenication', 'LoginController@checkAuthentication');
Route::get('/check-me', 'LoginController@checkMe'); //ยืนยันตัวตน