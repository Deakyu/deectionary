<?php 

// USER APIS
Route::get('/verify/{email}/{verifyToken}', 'UserController@sendEmailDone')->name('sendEmailDone');

Route::post('/register', 'UserController@register');
Route::post('/login', 'UserController@login');
Route::post('/logout', 'UserController@logout');

// WORD APIS
Route::post('/save', 'WordController@save');
Route::delete('/delete/{word}', 'WordController@destroy');
Route::post('/isliked', 'WordController@isliked');
Route::post('/userword', 'WordController@userword');

// EXAMPLE APIS
Route::post('/example/save', 'ExampleController@save');
Route::post('/examples', 'ExampleController@index');
Route::post('/example-by-date', 'ExampleController@exampleByDate');

