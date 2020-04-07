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

    Route::get('/order/step1', ['as' => 'aorder.step1', 'uses' => 'ASystem\Catalog\OrderController@step1']);

    Route::resource('order', 'ASystem\Catalog\OrderController')->names('aorder');

    //Route::get('/factsheet/index', ['as' => 'factsheet.index', 'uses' => 'ASystem\Report\FactSheetController@index']);

    Route::resource('users','ASystem\Report\UsersSheetController');

    Route::resource('group', 'ASystem\Catalog\GroupController')->names('group');

    Route::resource('producer', 'ASystem\Catalog\ProducerController')->names('producer');

/*    Route::resource('pattern', 'ASystem\PatternController')->names('pattern');
    Route::post('/pattern_copy', ['as' =>'pattern.copy', 'uses' => 'ASystem\PatternController@copy']);*/

    Route::resource('pattern_materials', 'ASystem\Catalog\PatternMaterialsController')->names('patternMaterials');
    Route::post('/pattern_materials_copy', ['as' =>'patternMaterials.copy', 'uses' => 'ASystem\PatternMaterialsController@copy']);

    Route::resource('pattern_prices', 'ASystem\Catalog\PatternPricesController')->names('patternPrices');
    Route::post('/pattern_prices_copy', ['as' =>'patternPrices.copy', 'uses' => 'ASystem\PatternPricesController@copy']);

    Route::resource('material', 'ASystem\Catalog\MaterialController')->names('material');
    Route::post('/material_copy', ['as' =>'material.copy', 'uses' => 'ASystem\Catalog\MaterialController@copy']);

    Route::resource('nomenclature', 'ASystem\Catalog\NomenclatureController')->names('nomenclature');
    Route::get('/nomenclature_upload', ['as' => 'nomenclature.upload', 'uses' => 'ASystem\NomenclatureController@upload']);
    Route::post('/nomenclature_upload', ['as' =>'nomenclature.upload', 'uses' => 'ASystem\NomenclatureController@uploadSave']);

    Route::resource('work', 'ASystem\Catalog\WorkController')->names('work');
    Route::get('/work_upload', ['as' => 'work.upload', 'uses' => 'ASystem\Catalog\WorkController@upload']);
    Route::post('/work_upload', ['as' =>'work.upload', 'uses' => 'ASystem\Catalog\WorkController@uploadSave']);

    Route::resource('specification', 'ASystem\SpecificationController')->names('specification');
    Route::get('/specification/{specification}/upload', ['as' => 'specification.upload', 'uses' => 'ASystem\SpecificationController@upload']);
    Route::post('/specification/upload', ['as' =>'specification.uploadSave', 'uses' => 'ASystem\SpecificationController@uploadSave']);
});

Route::group(['prefix' => 'cabinet',  'middleware' => ['auth', CheckActive::class]], function () {
    Route::resource('order', 'PSystem\OrderController')->names('order');
    Route::resource('fact', 'PSystem\FactController')->names('fact');
});

Route::get('/home', 'HomeController@index')->name('home');

