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
    return view('auth.login')->with('title','LOGIN');
});

Auth::routes();


Route::post('/saveq', 'RequestController@sendQuotationRequest')->name('saveq');
Route::post('/cupdate', 'HomeController@update')->name('cupdate');
Route::post('/updatec', 'HomeController@updatec')->name('updatec');
Route::post('/logincustom', 'Auth\LoginController@getLogin')->name('logincustom');
Route::post('/regcontact', 'Auth\ContactController@reg')->name('regcontact');
Route::post('/update_password', 'HomeController@updatePassword')->name('update_password');
Route::post('/update_passwordc', 'HomeController@updatePasswordc')->name('update_passwordc');
Route::post('/create', 'RequestController@store')->name('create');
Route::get('/edit/{request_id}', 'RequestController@show')->name('show');
Route::post('/request_update/', 'RequestController@update')->name('request_update');


Route::get('/home', 'HomeController@index')->name('home');
Route::get('/newc', 'HomeController@newc')->name('newc');
Route::get('contact_persons', 'HomeController@cperson')->name('contact_persons');
Route::get('/q_request', 'RequestController@q_request')->name('q_request');
Route::get('/tracker', 'HomeController@tracker')->name('tracker');
Route::get('/finance', 'HomeController@finance')->name('finance');
Route::get('/samples', 'HomeController@samples')->name('samples');
Route::get('/new', 'HomeController@request')->name('new');
Route::get('/profile', 'HomeController@profile')->name('profile');
Route::get('/client/{id}', function($id){
    $details = DB::select("SELECT * FROM clients where id='$id'");
    return response()->json($details);
    
});
Route::get('/q_request_edit/{quotation_no}', 'RequestController@q_request_edit')->name('edit_quote');
Route::post('/updateQuote', 'RequestController@updateQuote')->name('updateQuote');

Route::get('/sample/{id}', 'RequestController@show')->name('sample');
Route::get('/search', 'HomeController@search')->name('search');
Route::get('/requestchange', 'HomeController@sendPasswordEmail');
Route::get('/changepassword', 'HomeController@changepassword');
