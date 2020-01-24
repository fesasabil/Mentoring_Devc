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

Route::group(['middleware' => 'api'], function () {
    Route::post('/signup', 'Api\AuthController@signup');
    Route::post('/signin', 'Api\AuthController@signin');
    Route::post('/product/create', 'Api\ProductController@createProducts');
    Route::get('/product/show', 'Api\ProductController@showProducts');
    Route::put('product/update/{product}', 'Api\ProductController@update');
    Route::delete('product/delete/{product}', 'Api\ProductController@delete');
});
