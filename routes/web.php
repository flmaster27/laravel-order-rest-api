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

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');


Route::prefix('/api/v1')->middleware('auth')->group(function () {
    Route::get('order/create', 'OrderController@create');
    Route::get('order/change/{order_id}/{status_id}', 'OrderController@change');
    Route::get('product/add/{order_id}', 'ProductController@add');
    Route::get('product/paid', 'ProductController@paid');
});