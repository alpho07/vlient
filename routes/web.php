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

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::get('/tracker', 'HomeController@tracker')->name('tracker');
Route::get('/finance', 'HomeController@finance')->name('finance');
Route::get('/samples', 'HomeController@samples')->name('samples');
Route::get('/new', 'HomeController@request')->name('new');
Route::get('/profile', 'HomeController@profile')->name('profile');
