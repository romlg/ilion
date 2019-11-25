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

Route::group(['prefix' => 'admin'], function () {


    Route::resource('contract', 'ASystem\Document\ContractController')->names('contract');
    Route::resource('object', 'ASystem\Catalog\ObjectController')->names('object');
    Route::get('/object/{object}/upload', ['as' =>'object.upload', 'uses' => 'ASystem\Catalog\ObjectController@upload']);
    Route::post('/object/{object}/upload', ['as' =>'object.upload', 'uses' => 'ASystem\Catalog\ObjectController@uploadSave']);
    Route::resource('material', 'ASystem\Catalog\MaterialController')->names('material');

    Route::get('/order/step1', ['as' => 'aorder.step1', 'uses' => 'ASystem\Catalog\OrderController@step1']);

    Route::resource('order', 'ASystem\Catalog\OrderController')->names('aorder');

    Route::get('/factsheet/index', ['as' => 'factsheet.index', 'uses' => 'ASystem\Report\FactSheetController@index']);

});




Route::group(['prefix' => 'cabinet'], function () {
    Route::resource('order', 'PSystem\OrderController')->names('order');
    Route::resource('fact', 'PSystem\FactController')->names('fact');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
