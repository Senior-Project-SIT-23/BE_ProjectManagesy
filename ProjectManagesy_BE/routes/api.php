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


Route::group(['middleware' => ['checkauth']], function () {

    // Activity
    //Student
    Route::post('/activity/student', 'ActivityManagementController@storeStudentActivity'); //สร้าง activity
    Route::post('/activity/student/edit', 'ActivityManagementController@editStudentActivity'); //edit activity
    Route::post('/activity/student/delete', 'ActivityManagementController@deleteStudentActivity'); // delete activity

    Route::get('/activity/student', 'ActivityManagementController@indexStudentAllActivity'); //ดู Activity ทั้งหมด
    Route::get('/activity/student/name-list', 'ActivityManagementController@indexALLActiviyNameList'); //ดูรายชื่อ Activity ที่เลือก

    //count
    // Route::get('/activity/student/count', 'ActivityManagementController@countStudentAllActivity'); //count ข้อมูล

    //Admission
    Route::post('/admission', 'AdmissionManagementController@storeAdmission'); //สร้าง admission
    Route::post('/admission/edit', 'AdmissionManagementController@editAdmission'); //edit admision
    Route::post('/admission/delete', 'AdmissionManagementController@deleteAdmission'); // delete admission

    Route::get('/admission', 'AdmissionManagementController@indexAllAdmission'); //ดู Admission
    Route::get('/admission/{admission_id}', 'AdmissionManagementController@indexAdmission'); //ดู Admisssion_id
    Route::get('/admission/readfilename/{activity_id}', 'AdmissionManagementController@readFileAdmission'); //ดู Admisssion_id

    //analyze
    Route::get('/analyze/{year}', 'AnalyzeController@indexNumOfActivityAndAdmission');
    Route::get('/student', 'AnalyzeController@indexAllStudent');

    //matching
    Route::get('/matchingfile', 'MatchingController@indexmatchingActivityAndAdimssion');

});

#ยิงLogin เพื่อเช็ด auth
Route::post('/check-authenication', 'LoginController@checkAuthentication');
Route::get('/check-me', 'LoginController@checkMe'); //ยืนยันตัวตน