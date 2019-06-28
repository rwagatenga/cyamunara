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
  Route::post('/test', 'UploadingController@test');
  // Home
  Route::get('home', 'OrderingController@index');
  // Login
  Route::post('/login','RegLogController@postLogin');
  // Register
  Route::post('/register','RegLogController@postRegister');
  // Show More Details
  Route::get('/moreDetails/{id}', 'OrderingController@show');
  // Order
  Route::post('/order/{id}', 'OrderingController@update');
  	
  Route::middleware('APIToken')->group(function () {
  	//----Uploading Brands----
  	Route::get('/productForm', 'UploadingController@ProductForm');
    //-----Product Insert-----
  	Route::post('/productInsert', 'UploadingController@ProductInsert');
    //---Product Display----
    Route::get('/productDisplay', 'UploadingController@ProductDisplay');
    //---Product Edit--
    Route::get('/productShow/{id}', 'UploadingController@ProductShow');
    //---Product Update---
    Route::post('/productUpdate/{id}', 'UploadingController@ProductUpdate');
  	//----Logout---
  	Route::post('/logout','RegLogController@postLogout');
  });
});
