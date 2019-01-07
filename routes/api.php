<?php

use Illuminate\Http\Request;


// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::post('login', 'PassportController@login');
Route::post('register', 'PassportController@register');
 
Route::middleware('auth:api')->group(function() 
{

    Route::get('user', 'PassportController@details');
  	Route::post('login/pwdchange', 'PassportController@changePassword');

    Route::resource('products', 'ProductController');

});

Route::group([    
    //'namespace' => 'Auth',    
    'middleware' => 'api',    
    'prefix' => 'password'
], function () {    
    Route::post('create', 'PasswordResetController@create');
    Route::get('find/{token}', 'PasswordResetController@find');
    Route::post('reset', 'PasswordResetController@reset');
});