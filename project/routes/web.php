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
Route::group(['middlewareGroups' => []], function () {
    Route::get('/', 'HomeController@index');
    Route::get('/admin','HomeController@admin');
    Route::get('/login','HomeController@login');
    Route::post('/login','HomeController@validaLogin');
    Route::get('/logout','HomeController@logout');
    Route::get('/registrar','HomeController@register');
    Route::post('/registrar','HomeController@registerSave');
    Route::post('/registrar','HomeController@registerSave');
    Route::post('/forget','HomeController@forgetPassword');
    Route::get('/registrar/update','HomeController@updateUserForm');
    Route::post('/registrar/update','HomeController@updateUser');
});
