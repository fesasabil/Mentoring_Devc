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
    return view('welcome');
});

// Route::namespace('Categories')->group(function() {
//     Route::resource('categories', 'CategoryController');
//     Route::get('remove-image-category', 'CategoryController@removeImage')->name('category.remove.image');
// });

// Route::namespace('Orders')->group(function() {
//     Route::resource('orders', 'OrderController');
//     Route::get('orders/{id}/invoice', 'OrderController@generateInvoice')->name('orders.invoice.generate');
// });
