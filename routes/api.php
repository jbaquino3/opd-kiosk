<?php

use Illuminate\Http\Request;

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

//followup routes
Route::get('/getFollowupPatients', 'FollowupController@getFollowupPatients');
Route::get('/logFollowup', 'FollowupController@logFollowup');
Route::get('/checkEncounter', 'FollowupController@checkEncounter');

// departments routes
Route::get('/load_departments', 'DepartmentController@loadDepartments');
Route::get('/load_hospitals', 'DepartmentController@loadHospitals');

// queue routes
Route::post('/generate_queue', 'QueueController@generateQueue');
Route::get('/cancel_queue', 'QueueController@cancelQueue');
Route::post('/print_queue', 'QueueController@printQueue');
Route::get('/set_teleconsultation', 'QueueController@setTeleconsultation');
Route::get('/rev_teleconsultation', 'QueueController@revTeleconsultation');

// patient routes
Route::post('/search_patient', 'PatientController@searchPatient');
Route::get('/log_patient', 'PatientController@logPatient');
Route::get('/get_history', 'PatientController@getHistory');
Route::get('/opd_bio', 'PatientController@opdBio');
Route::get('/get_patient_details', 'PatientController@getPatientDetails');
Route::post('/register_patient', 'PatientController@registerPatient');
Route::post('/register_patient_noimg', 'PatientController@registerPatientNoimg');
Route::get('/get_recent_logs', 'PatientController@getRecentLogs');
Route::get('/get_recent_logs_pcc', 'PatientController@getRecentLogsPcc');
Route::get('/count_patients', 'PatientController@countPatients');
Route::get('/uploadImage', 'PatientController@uploadImage');
