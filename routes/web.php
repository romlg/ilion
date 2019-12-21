<?php

use App\Http\Middleware\CheckActive;
use App\Http\Middleware\CheckAdmin;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::group(['prefix' => 'admin', 'middleware' => ['auth', CheckAdmin::class]], function () {

    Route::resource('contract', 'ASystem\Document\ContractController')->names('contract');
    Route::resource('object', 'ASystem\Catalog\ObjectController')->names('object');
    Route::resource('material', 'ASystem\Catalog\MaterialController')->names('material');
    Route::resource('users','ASystem\Report\UsersSheetController');

    Route::resource('stage', 'ASystem\Catalog\StageController');

    Route::get('/stage/{stage}/upload', ['as' =>'stage.upload', 'uses' => 'ASystem\Catalog\StageController@upload']);
    Route::post('/stage/{stage}/upload', ['as' =>'stage.upload', 'uses' => 'ASystem\Catalog\StageController@uploadSave']);

//    Route::get('/object/{object}/upload', ['as' =>'object.upload', 'uses' => 'ASystem\Catalog\ObjectController@upload']);
//    Route::post('/object/{object}/upload', ['as' =>'object.upload', 'uses' => 'ASystem\Catalog\ObjectController@uploadSave']);

    Route::get('/order/step1', ['as' => 'aorder.step1', 'uses' => 'ASystem\Catalog\OrderController@step1']);
    Route::resource('order', 'ASystem\Catalog\OrderController')->names('aorder');

    Route::get('/factsheet/index', ['as' => 'factsheet.index', 'uses' => 'ASystem\Report\FactSheetController@index']);


});

Route::group(['prefix' => 'cabinet',  'middleware' => ['auth', CheckActive::class]], function () {
    Route::resource('order', 'PSystem\OrderController')->names('order');
    Route::resource('fact', 'PSystem\FactController')->names('fact');
});

Route::get('/home', 'HomeController@index')->name('home');
