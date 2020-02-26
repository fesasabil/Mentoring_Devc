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
   
    Route::group(['middleware' => ['jwt.verify']], function () {
        Route::get('/user', 'Api\AuthController@getAuthenticatedUser');
        
        // Product
        Route::post('/product/create', 'Api\ProductController@createProducts');
        Route::get('/product/detailProduct', 'Api\ProductController@detailProduct');
        Route::put('/product/update/{product}', 'Api\ProductController@update');
        Route::delete('/product/delete/{product}', 'Api\ProductController@delete');

        // Category
        Route::post('/category/create', 'Api\CategoryController@createCategory');
        Route::put('/category/update/{category}', 'Api\CategoryController@updateCategory');
        Route::delete('/category/delete/{category}', 'Api\CategoryController@deleteCategory');

        // Order
        Route::post('/order/create', 'Api\OrderController@createOrder');

        // Price
        Route::get('/price/show', 'Api\PriceController@showPrice');
    });

    Route::get('/product/show', 'Api\ProductController@showProducts');
    Route::get('/category/show', 'Api\CategoryController@showCategory');
});

