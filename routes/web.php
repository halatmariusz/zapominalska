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
    return view('welcome');
});

Route::get('/waluty', 'CurrencyController@show')->name('currencies.show');


Route::get('/webhook', 'MainController@receive')->middleware('verify');
Route::post('/webhook', 'MainController@receive');
