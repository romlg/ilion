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
    Route::get('/object/{object}/upload', ['as' =>'object.upload', 'uses' => 'ASystem\Catalog\ObjectController@upload']);
    Route::post('/object/{object}/upload', ['as' =>'object.upload', 'uses' => 'ASystem\Catalog\ObjectController@uploadSave']);
    Route::resource('material', 'ASystem\Catalog\MaterialController')->names('material');

    Route::get('/order/step1', ['as' => 'aorder.step1', 'uses' => 'ASystem\Catalog\OrderController@step1']);

    Route::resource('order', 'ASystem\Catalog\OrderController')->names('aorder');

    Route::get('/factsheet/index', ['as' => 'factsheet.index', 'uses' => 'ASystem\Report\FactSheetController@index']);

    Route::resource('users','ASystem\Report\UsersSheetController');

});

Route::group(['prefix' => 'cabinet',  'middleware' => ['auth', CheckActive::class]], function () {
    Route::resource('order', 'PSystem\OrderController')->names('order');
    Route::resource('fact', 'PSystem\FactController')->names('fact');
});

Route::get('/home', 'HomeController@index')->name('home');
