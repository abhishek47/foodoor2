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

Route::post('login', 'Auth\LoginController@login');
Route::post('logout', 'Auth\LoginController@logout');

Route::post('/phone/sendotp', 'Api\ProfileController@sendOTP');
Route::post('/phone/store', 'Api\ProfileController@store');




Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware('auth:api')->post('/driver/location/update', 'Api\DriverLocationController@update');

Route::middleware('auth:api')->post('/driver/device_token', 'Api\DriverController@updateDeviceToken');

Route::middleware('auth:api')->post('/driver/order/{order}/accept', 'Api\DriverController@acceptOrder');

Route::middleware('auth:api')->post('/driver/order/{order}/reached_restaurant', 'Api\DriverController@reachedRestaurant');

Route::middleware('auth:api')->post('/driver/order/{order}/picked', 'Api\DriverController@orderPicked');

Route::middleware('auth:api')->post('/driver/order/{order}/delivered', 'Api\DriverController@orderDelivered');

Route::middleware('auth:api')->post('/driver/orders/new', 'Api\DriverController@newOrders');

Route::middleware('auth:api')->post('/driver/orders/pre', 'Api\DriverController@preOrders');

Route::middleware('auth:api')->post('/driver/orders/history', 'Api\DriverController@orderHistory');

Route::middleware('auth:api')->post('/driver/orders/{order}', 'Api\DriverController@getOrder');

Route::middleware('auth:api')->post('/driver/order/{order}/picked', 'Api\DriverController@orderPicked');

Route::middleware('auth:api')->post('/driver/order/{order}/delivered', 'Api\DriverController@orderDelivered');


Route::middleware('auth:api')->post('/restaurant/order/{order}/confirm', 'Api\RestaurantController@confirmOrder');

Route::middleware('auth:api')->post('/restaurant/order/{order}/cancel', 'Api\RestaurantController@cancelOrder');

Route::middleware('auth:api')->post('/restaurant/order/{order}/ready', 'Api\RestaurantController@orderReady');

Route::middleware('auth:api')->post('/restaurant/orders/new', 'Api\RestaurantController@newOrders');
Route::middleware('auth:api')->post('/restaurant/orders', 'Api\RestaurantController@orders');
Route::middleware('auth:api')->post('/restaurant/orders/history', 'Api\RestaurantController@history');
Route::middleware('auth:api')->post('/restaurant/orders/confirmed', 'Api\RestaurantController@confirmOrders');
Route::middleware('auth:api')->post('/restaurant/orders/ready', 'Api\RestaurantController@readyOrders');

Route::middleware('auth:api')->post('/restaurant/orders/create', 'Api\OrdersController@store');

Route::post('/restaurant/upload/bill', 'Api\OrdersController@storeBill');

Route::middleware('auth:api')->post('/restaurant/orders/{order}', 'Api\RestaurantController@getOrder');



Route::middleware('auth:api')->post('/restaurant/{restaurant}/items', 'Api\RestaurantController@getItems');

Route::middleware('auth:api')->post('/restaurant/items/{item}/change-avaibility', 'Api\ItemsController@changeAvaibility');




Route::post('password/email', 'Api\ProfileController@sendResetLinkEmail');
Route::post('password/reset', 'Auth\ResetPasswordController@reset');




