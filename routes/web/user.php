<?php

use App\Models\Conversation;
use App\Models\User;

Route::group(['prefix' => 'user','middleware' => 'auth','namespace' => 'User'],function(){
	Route::get('/', 'UserController@show');
	Route::delete('/', 'UserController@delete');
	Route::get('/matches', 'UserController@getMatches');
	Route::patch('/username','UserController@updateUsername');
    Route::patch('/email','UserController@updateEmail');
    Route::patch('/password','UserController@updatePassword');
    Route::patch('/language','UserController@updateLanguage');
    Route::get('/conversations', 'ConversationController@showConversations');
    Route::group(['middleware' => 'can:update,conversation'],function(){
		Route::get('/conversations/{conversation}', 'ConversationController@getConversationMessages');
		Route::post('/conversations/{conversation}', 'ConversationController@sendMessage');
		Route::post('/conversations/read/{conversation}', 'ConversationController@markAsRead');
	});
});
