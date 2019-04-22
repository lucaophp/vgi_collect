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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
Route::group(['middlewareGroups' => []], function () {
    Route::post('/', 'HomeController@marcar');
    Route::post('/photo', 'HomeController@photo');
    Route::get('/points','HomeController@getPoints');
    Route::get('/proximos','HomeController@neighbor');
    Route::get('/proximos/{active}','HomeController@neighbor');
    Route::delete('/eventRep','HomeController@deleteEvent');
    Route::delete('/event','HomeController@finallyEvent');
    Route::get('/event','HomeController@getEvent');
    Route::put('/event','HomeController@updateEvent');
    Route::post('/confirmEvent','HomeController@confirmEvent');
    Route::get('/user/session','HomeController@getDataBySession');
    Route::get('/address','HomeController@address');
});

