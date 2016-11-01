<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your application. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
*/

//Route::get('/', function () {
//    return view('welcome');
//});

Auth::routes();

//Route::get('/home', 'HomeController@index');
Route::get('/', ['as' => 'messages', 'uses' => 'MessageController@index']);
Route::post('/message/{message}', 'MessageController@show');
Route::get('invalid', ['as' => 'messages.invalid', 'uses' => 'MessageController@invalid']);

$router->resource('message', 'MessageController');