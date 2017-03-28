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

Auth::routes();

Route::group(['prefix' => "matches"],function(){
	Route::get('/', 'Match\PagesController@welcome');
	Route::post('/', 'Match\MatchController@create');
});

Route::get('search', 'Match\PagesController@search');
Route::post('search', 'Match\MatchController@search');


Route::get('/home', 'HomeController@index');
Route::get('/', 'HomeController@index');
Route::get('lang/{lang}', 'LanguageController@switchLang');
