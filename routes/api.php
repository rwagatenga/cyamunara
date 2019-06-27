<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::prefix('v1')->namespace('API')->group(function () {
  // Login
  Route::post('/login','RegLogController@postLogin');
  // Register
  Route::post('/register','RegLogController@postRegister');
//---Product Display--
  	Route::get('ProductDisplay', 'UploadingController@ProductDisplay');
  	
  Route::middleware('APIToken')->group(function () {
  	//----Uploading Brands----
  	Route::get('/ProductForm', 'UploadingController@ProductForm');
  	Route::post('/ProductInsert', 'UploadingController@ProductInsert');

  	//----Logout---
  	Route::post('/logout','RegLogController@postLogout');
  });
});
