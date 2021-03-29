<?php

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
    return view('index');
});

Route::get('/apex', function() {
    return view('layouts.master-apex');
});

Route::get('/pcc', function() {
    return view('layouts.master-pcc');
});

Route::get('/follow_up', function() {
    return view('layouts.master-ffup');
});

// no defined path
Route::get('{path}', "IndexController@index")->where('path', '([A-z\d-\/_.]+)?');
// Route::get('{path}', "IndexController@index");


