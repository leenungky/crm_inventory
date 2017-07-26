<?php


/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
// */

Route::get('/', [
    'as' => 'index', 'uses' => 'UserController@getLogin'
]);

Route::group(['middleware' => 'logic'], function(){
	Route::controller('/user', 'UserController');
	Route::controller('/courier', 'CourierController');
	Route::controller('/merchant', 'MerchantController');
});


// Route::get('/signin', [
//     'as' => 'signin', 'uses' => 'InventoryController@signin'
// ]);



 Route::group(['middleware' => 'logic'], function(){
	Route::get('/inbound', [
	    'as' => 'inbound', 'uses' => 'InventoryController@inbound'
	]);

	Route::get('/outbound', [
	    'as' => 'outbound', 'uses' => 'InventoryController@outbound'
	]);

	// Route::get('/history', [
	//     'as' => 'history', 'uses' => 'InventoryController@history'
	// ]);

	Route::any('/allorder', [
	    'as' => 'allorder', 'uses' => 'InventoryController@allorder'
	]);

	Route::any('/detailorder', [
	    'as' => 'detailorder', 'uses' => 'InventoryController@detailorder'
	]);	


	Route::get('/download', [
	    'as' => 'download', 'uses' => 'InventoryController@download'
	]);	

	Route::get('/downloadall', ['as' => 'downloadall', 'uses' => 'InventoryController@downloadall']);	

	Route::post('/insert', [
	    'as' => 'insert', 'uses' => 'InventoryController@insert'
	]);

	Route::get('/read', [
	    'as' => 'readinventory', 'uses' => 'InventoryController@readInventory'
	]);	

	Route::get('/readprint', [
	    'as' => 'readpring', 'uses' => 'InventoryController@readAfterPrint'
	]);	

	Route::post('/insertajax', [
	    'as' => 'insertajax', 'uses' => 'InventoryController@insertajax'
	]);

	Route::get('/find', [
	    'as' => 'find', 'uses' => 'InventoryController@find'
	]);

	Route::get('/find_status', [
	    'as' => 'find_status', 'uses' => 'InventoryController@find_status'
	]);

	Route::get('/find_latest_status', [
	    'as' => 'find_latest_status', 'uses' => 'InventoryController@find_latest_status'
	]);

	Route::get('/readHistory/{type}', [
	    'as' => 'readHistory', 'uses' => 'InventoryController@readHistory'
	]);

	Route::get('/edit', [
	    'as' => 'edit', 'uses' => 'InventoryController@edit'
	]);

	Route::get('/deleteconf', [
	    'as' => 'deleteconf', 'uses' => 'InventoryController@deleteconf'
	]);

	Route::post('/deleted', [
	    'as' => 'deleted', 'uses' => 'InventoryController@deleted'
	]);

	Route::post('/update', [
	    'as' => 'update', 'uses' => 'InventoryController@update'
	]);

	Route::get('/courier', [
	    'as' => 'courier', 'uses' => 'InventoryController@courier'
	]);


	Route::get('/generaterwb', [
	    'as' => 'generaterwb', 'uses' => 'InventoryController@generaterwb'
	]);

	Route::post('/uploadawb', [
	    'as' => 'uploadawb', 'uses' => 'InventoryController@uploadawb'
	]);

	Route::get('/getweight', [
	    'as' => 'getweight', 'uses' => 'InventoryController@getweight'
	]);

	Route::get('/test', [
	    'as' => 'test', 'uses' => 'InventoryController@test'
	]);

	Route::get('/dashboard', [
	    'as' => 'dashboard', 'uses' => 'DashboardController@index'
	]);	
	
});

Route::get('/printdata', [
    'as' => 'printdata', 'uses' => 'InventoryController@printdata'
]);

Route::get('/qrcode', [
    'as' => 'qrcode', 'uses' => 'InventoryController@qrcode'
]);

Route::group(['prefix' => 'customer', 'middleware' => 'logic'], function(){
	Route::any('/', ['as' => 'index_customer', 'uses' => 'CustomerController@index']);	
	Route::any('/new', ['as' => 'new_customer', 'uses' => 'CustomerController@newcustomer']);	
	Route::any('/create', ['as' => 'create_customer', 'uses' => 'CustomerController@create']);	
	Route::any('/edit/{id}', ['as' => 'edit', 'uses' => 'CustomerController@edit']);	
	Route::any('/update/{id}', ['as' => 'edit', 'uses' => 'CustomerController@update']);
	Route::any('/delete/{id}', ['as' => 'delete', 'uses' => 'CustomerController@delete']);	
});

