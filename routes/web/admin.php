<?php


Route::group(['prefix' => "admin/errors", 'namespace' => 'Admin'],function(){
	Route::get('/', 'ErrorController@show');
	Route::get('/php', 'ErrorController@getPhpErrors');
	Route::get('/js', 'ErrorController@getJsErrors');
	Route::delete('/{error}', 'ErrorController@delete');
});