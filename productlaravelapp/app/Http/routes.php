<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', function () {
    return view('index');
});
Route::get('/api/products/{id?}', 'Products@index');
Route::post('/api/products', 'Products@save');
Route::post('/api/products/{id}', 'Products@update');
Route::delete('/api/products/{id}', 'Products@remove');
